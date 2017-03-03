<?php

namespace Drupal\membership_term\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\membership\Entity\MembershipTermInterface;
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
 *     "langcode" = "langcode",
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
    parent::preSave($storage);
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

}
