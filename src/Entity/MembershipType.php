<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Membership type entity.
 *
 * @ConfigEntityType(
 *   id = "membership_type",
 *   label = @Translation("Membership type"),
 *   handlers = {
 *     "list_builder" = "Drupal\membership\MembershipTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\membership\Form\MembershipTypeForm",
 *       "edit" = "Drupal\membership\Form\MembershipTypeForm",
 *       "delete" = "Drupal\membership\Form\MembershipTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\membership\MembershipTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "membership_type",
 *   bundle_of = "membership",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "label",
 *     "id",
 *     "workflow",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/membership_type/{membership_type}",
 *     "add-form" = "/admin/structure/membership_type/add",
 *     "edit-form" = "/admin/structure/membership_type/{membership_type}/edit",
 *     "delete-form" = "/admin/structure/membership_type/{membership_type}/delete",
 *     "collection" = "/admin/structure/membership_type"
 *   }
 * )
 */
class MembershipType extends ConfigEntityBase implements MembershipTypeInterface {

  /**
   * The Membership type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Membership type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The membership type workflow ID.
   *
   * @var string
   */
  protected $workflow;

  /**
   * The processor plugin string ID.
   *
   * @var string
   */
  protected $processor_plugin_id;

  /**
   * {@inheritdoc}
   */
  public function getWorkflowId() {
    return $this->workflow;
  }

  /**
   * {@inheritdoc}
   */
  public function setWorkflowId($workflow_id) {
    $this->workflow = $workflow_id;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getProcessorPlugin() {
    // TODO: Implement getProcessorPlugin() method.
  }

  /**
   * @inheritdoc
   */
  public function setProcessorPluginId($processor_plugin_id) {
    // TODO: Implement setProcessorPluginId() method.
  }

  /**
   * @inheritDoc
   * 
   * @todo Implement configurable options for expired state determination.
   */
  public function isExpired(MembershipInterface $membership) {
    return $membership->get('state')->getValue() === 'expired';
  }

  /**
   * @inheritDoc
   */
  public function shouldCreateNewRevision() {
    return true;
  }

}
