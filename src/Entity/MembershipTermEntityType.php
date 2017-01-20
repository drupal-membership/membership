<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Membership term entity type entity.
 *
 * @ConfigEntityType(
 *   id = "membership_term_entity_type",
 *   label = @Translation("Membership term entity type"),
 *   handlers = {
 *     "list_builder" = "Drupal\membership\MembershipTermEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\membership\Form\MembershipTermEntityTypeForm",
 *       "edit" = "Drupal\membership\Form\MembershipTermEntityTypeForm",
 *       "delete" = "Drupal\membership\Form\MembershipTermEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\membership\MembershipTermEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "membership_term_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "membership_term_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "label",
 *     "id",
 *     "membership_type",
 *     "workflow",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/membership_term_type/membership_term_entity_type/{membership_term_entity_type}",
 *     "add-form" = "/admin/structure/membership_term_type/membership_term_entity_type/add",
 *     "edit-form" = "/admin/structure/membership_term_type/membership_term_entity_type/{membership_term_entity_type}/edit",
 *     "delete-form" = "/admin/structure/membership_term_type/membership_term_entity_type/{membership_term_entity_type}/delete",
 *     "collection" = "/admin/structure/membership_term_type/membership_term_entity_type"
 *   }
 * )
 */
class MembershipTermEntityType extends ConfigEntityBundleBase implements MembershipTermEntityTypeInterface {

  /**
   * The Membership term entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Membership term entity type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The membership term workflow ID.
   *
   * @var string
   */
  protected $workflow;

  /**
   * The membership type for this term type.
   *
   * @var string
   */
  protected $membership_type;

  /**
   * @inheritdoc
   */
  public function getMembershipType() {
    return $this->membership_type;
  }

  /**
   * @inheritdoc
   */
  public function setMembershipType($membership_type) {
    $this->membership_type = $membership_type;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getWorkflow() {
    return $this->workflow;
  }

  /**
   * @inheritdoc
   */
  public function setWorkflow($workflow) {
    $this->workflow = $workflow;
    return $this;
  }

}
