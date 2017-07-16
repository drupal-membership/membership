<?php

namespace Drupal\membership_term\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\membership\EventDispatcherTrait;
use Drupal\user\UserInterface;

/**
 * Defines the Membership term entity.
 *
 * @ingroup membership
 *
 * @ContentEntityType(
 *   id = "membership_term",
 *   label = @Translation("Membership term"),
 *   bundle_label = @Translation("Membership term type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\membership_term\MembershipTermListBuilder",
 *     "views_data" = "Drupal\membership_term\Entity\MembershipTermViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\membership_term\Form\MembershipTermForm",
 *       "add" = "Drupal\membership_term\Form\MembershipTermForm",
 *       "edit" = "Drupal\membership_term\Form\MembershipTermForm",
 *       "delete" = "Drupal\membership_term\Form\MembershipTermDeleteForm",
 *     },
 *     "access" = "Drupal\membership_term\MembershipTermAccessControlHandler",
 *   },
 *   base_table = "membership_term",
 *   admin_permission = "administer membership term entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "status" = "status",
 *   },
 *   bundle_entity_type = "membership_term_type",
 *   field_ui_base_route = "entity.membership_term_type.edit_form"
 * )
 */
class MembershipTerm extends ContentEntityBase implements MembershipTermInterface {

  use EntityChangedTrait;
  use EventDispatcherTrait;

  /**
   * {@inheritdoc}
   */
  public function getMembership() {
    return $this->get('membership_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setMembership(MembershipInterface $membership) {
    $this->set('membership_id', $membership->id());
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getMembershipId() {
    return $this->get('membership_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  public function label() {
    $active_dates = $this->get('field_active_dates');
    $label = $active_dates->value . ' - ' . $active_dates->end_value;
    return $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  public function getWorkflowState() {
    return $this->get('state')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // The membership backreference, populated by Membership::postSave().
    $fields['membership_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Membership'))
      ->setDescription(t('The parent membership.'))
      ->setSetting('target_type', 'membership')
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Created by'))
      ->setDescription(t('The user ID of the creator of the Membership term entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['state'] = BaseFieldDefinition::create('state')
      ->setLabel(t('State'))
      ->setDescription(t('The order state.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'list_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('workflow_callback', ['\Drupal\membership_term\Entity\MembershipTerm', 'getWorkflowId']);

    return $fields;
  }

  /**
   * @inheritdoc
   */
  function preSave(EntityStorageInterface $storage) {
    $oldstate = $this->getWorkflowState();
    $state = $this->getCalculatedState();
    switch ($oldstate) {
      case 'renewed':
        // Button on transition form was pressed, or term has been renewed.
        // Skip all processing.
        break;
      case 'expired':
        // Either the button on transition form was pressed, or this term has expired.
        // Check to see what it should be...
        if ($state != 'expired') {
          // then let the state event handler clean up...
          break;
        }
        $this->state->value = $state;
        break;
      case 'active':
        if ($state == 'pending') {
          // Then we are activating the membership by setting the start date to now...
          $this->setStartDate(date('Y-m-d'), FALSE);
          break;
        }
        // Else fall down into the normal processing.
      default:
        /** @var MembershipInterface $membership */
        if ($membership = $this->getMembership()) {
          if ($state != $oldstate) {
            $this->state->value = $state;
          }
          $this->setOwner($membership->getOwner());
        }
    }

    parent::preSave($storage);

  }

  protected function getCalculatedState() {
    $date = time();
    $state = 'pending';

    $active_times = $this->get('field_active_dates')->first()->getValue();
    $start = strtotime($active_times['value']);
    $end = strtotime($active_times['end_value']);
    if ($date > $start) {
      $state = 'active';
    }
    if ($date > $end) {
      $state = 'expiring';
    }
    $revokeField = $this->get('field_revoke_date')->first();
    if ($revokeField) {
      $revoke = strtotime($revokeField->value);
    }
    else {
      $revoke = $this->updateRevokeDate();
    }
    if ($date > $revoke) {
      $state = 'expired';
    }
    return $state;
  }

  /**
   * @inheritdoc
   */
  function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);
    // Go update the membership...
    /** @var MembershipInterface $membership */
    $membership = $this->getMembership();
    if (!$membership) {
      return;
    }
    if ($this->isActive()) {
      $oldprovider = $membership->getProviderPlugin();
      $provider = [
        'plugin_id' => 'membership_term',
        'remote_id' => $this->id(),
      ];
      $membership->set('provider', $provider);
    }
    $found = FALSE;
    foreach ($membership->get('field_membership_term')->getValue() as $indx => $term) {
      if ($term['target_id'] == $this->id()) {
        $found = TRUE;
      }
    }
    if (!$found) {
      $membership->field_membership_term[] = ['target_id' => $this->id()];
    }
    $membership->save();
  }

  public function getMembershipType() {
    return 'membership';
  }

  /**
   * @inheritdoc
   */
  static public function getWorkflowId(MembershipTermInterface $membership_term) {
    $workflow = MembershipTermType::load($membership_term->bundle())->getWorkflowId();
    return $workflow;
  }

  /**
   * @inheritDoc
   */
  public function isPending() {
    return $this->getWorkflowState() == 'pending';
  }

  /**
   * @inheritDoc
   */
  public function isActive() {
    return $this->getWorkflowState() == 'active';
  }

  /**
   * @inheritDoc
   */
  public function isExpiring() {
    return $this->getWorkflowState() == 'expiring';
  }

  /**
   * @inheritDoc
   */
  public function isExpired() {
    return $this->getWorkflowState() == 'expired';
  }

  /**
   * @inheritDoc
   */
  public function isRenewed() {
    return $this->getWorkflowState() == 'renewed';
  }

  /**
   * @inheritDoc
   */
  public function setStartDate($date, $update_end_date = FALSE) {
    $this->field_active_dates->value = $date;

    if ($update_end_date) {
      /** @var \Drupal\membership_term\Entity\MembershipTermTypeInterface $membership_term_type */
      $membership_term_type = $this->entityTypeManager()
        ->getStorage('membership_term_type')
        ->load($this->bundle());
      $membership_term_type->getTermLength();
      $end = strtotime($date . ' ' . $membership_term_type->getTermLength());
      $this->field_active_dates->end_value = date('Y-m-d', $end);
    }
  }

  /**
   * @inheritDoc
   */
  public function updateRevokeDate() {
    $end = $this->field_active_dates->end_value;
    /** @var \Drupal\membership_term\Entity\MembershipTermTypeInterface $membership_term_type */
    $membership_term_type = $this->entityTypeManager()
      ->getStorage('membership_term_type')
      ->load($this->bundle());
    $grace = $membership_term_type->getGracePeriod();
    $revoke = strtotime($end . ' ' . $grace);
    $this->set('field_revoke_date', ['value' => date('Y-m-d', $revoke)]);
    return $revoke;

  }

  /**
   * @inheritDoc
   */
  public function cancel() {
    $date = date('Y-m-d', strtotime('Yesterday'));

    if ($date < $this->field_active_dates->value) {
      $this->field_active_dates->value = $date;
    }
    if ($date < $this->field_active_dates->end_value) {
      $this->field_active_dates->end_value = $date;
    }
    if ($date < $this->field_revoke_date->value) {
      $this->field_revoke_date->value = $date;
    }
  }
}
