<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\Revision\RevisionableContentEntityBase;
use Drupal\membership\EventDispatcherTrait;
use Drupal\membership\MembershipEvent;
use Drupal\membership\MembershipEvents;
use Drupal\membership\MembershipInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Membership entity.
 *
 * @ingroup membership
 *
 * @ContentEntityType(
 *   id = "membership",
 *   label = @Translation("Membership"),
 *   label_singular = @Translation("Membership"),
 *   label_plural = @Translation("Memberships"),
 *   label_count = @PluralTranslation(
 *     singular = "@count membership",
 *     plural = "@count memberships",
 *   ),
 *   bundle_label = @Translation("Membership type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\membership\MembershipListBuilder",
 *     "views_data" = "Drupal\membership\Entity\MembershipViewsData",
 *     "form" = {
 *       "default" = "Drupal\membership\Form\MembershipForm",
 *       "add" = "Drupal\membership\Form\MembershipForm",
 *       "edit" = "Drupal\membership\Form\MembershipForm",
 *       "delete" = "Drupal\membership\Form\MembershipDeleteForm",
 *     },
 *     "access" = "Drupal\membership\MembershipAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\membership\MembershipHtmlRouteProvider",
 *       "revision" = "Drupal\entity\Routing\RevisionRouteProvider",
 *     },
 *   },
 *   base_table = "membership",
 *   revision_table = "membership_revision",
 *   data_table = "membership_field_data",
 *   revision_data_table = "membership_field_revision",
 *   admin_permission = "administer membership entities",
 *   field_ui_base_route = "entity.membership_type.edit_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "revision" = "vid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/membership/{membership}",
 *     "add-form" = "/admin/structure/membership/add/{membership_type}",
 *     "edit-form" = "/admin/structure/membership/{membership}/edit",
 *     "delete-form" = "/admin/structure/membership/{membership}/delete",
 *     "collection" = "/admin/structure/membership",
 *     "revision" = "/admin/structure/membership/{membership}/revisions/{membership_revision}/view",
 *     "version-history" = "/admin/structure/membership/{membership}/revisions",
 *   },
 *   bundle_entity_type = "membership_type"
 * )
 */
class Membership extends RevisionableContentEntityBase implements MembershipInterface {

  use EntityChangedTrait;
  use EventDispatcherTrait;

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

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The Membership type/bundle.'))
      ->setSetting('target_type', 'membership_type')
      ->setRequired(TRUE);
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The owner of the Membership entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
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
    $fields['data'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Data'))
      ->setReadOnly(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('workflow_callback', ['\Drupal\membership\Entity\Membership', 'getWorkflowId']);
    $fields['membership_term'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Membership Term'))
      ->setDescription(t('The current term attached to this membership.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'membership_term_entity')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * @inheritDoc
   */
  public function getTerm() {
    return $this->get('membership_term')->entity;
  }

  /**
   * Gets the workflow ID for the state field.
   *
   * @param \Drupal\membership\MembershipInterface $membership
   *   The membership.
   *
   * @return string
   *   The workflow ID.
   */
  public static function getWorkflowId(MembershipInterface $membership) {
    $workflow = MembershipType::load($membership->bundle())->getWorkflowId();
    return $workflow;
  }

  /**
   * @inheritDoc
   */
  public function preSave(EntityStorageInterface $storage) {
    if (!$this->isNew() && ($storage->loadUnchanged($this->id())->state->getValue()) != $this->state->getValue()) {
      $event = new MembershipEvent($this);
      $this->getEventDispatcher()->dispatch(MembershipEvents::STATE_CHANGE, $event);
      if ($this->isExpired()) {
        $this->getEventDispatcher()->dispatch(MembershipEvents::EXPIRE, $event);
      }
    }
    parent::preSave($storage);
  }

  /**
   * Return a boolean indicating whether the membership is expired,
   * for whatever reason. Looks to the bundle for logic to decide.
   *
   * @return bool
   */
  public function isExpired() {
    return MembershipType::load($this->bundle())->isExpired($this);
  }

  /**
   * @inheritDoc
   */
  public function postCreate(EntityStorageInterface $storage) {
    $event = new MembershipEvent($this);
    $this->getEventDispatcher()->dispatch(MembershipEvents::CREATED, $event);
    parent::postCreate($storage);
  }

}
