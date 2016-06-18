<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\Revision\RevisionableContentEntityBase;
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
 *
 *     "form" = {
 *       "default" = "Drupal\membership\Form\MembershipForm",
 *       "add" = "Drupal\membership\Form\MembershipForm",
 *       "edit" = "Drupal\membership\Form\MembershipForm",
 *       "delete" = "Drupal\membership\Form\MembershipDeleteForm",
 *     },
 *     "access" = "Drupal\membership\MembershipAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\membership\MembershipHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "membership",
 *   revision_table = "membership_revision",
 *   admin_permission = "administer membership entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
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
 *   },
 *   bundle_entity_type = "membership_type",
 *   field_ui_base_route = "entity.membership_type.edit_form"
 * )
 */
class Membership extends RevisionableContentEntityBase implements MembershipInterface {
  use EntityChangedTrait;
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
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
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
      ->setDescription(t('The user ID of author of the Membership entity.'))
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
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Membership entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('workflow_callback', ['\Drupal\membership\Entity\Membership', 'getWorkflowId']);

    return $fields;
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
}
