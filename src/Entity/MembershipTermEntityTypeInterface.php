<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Membership term entity type entities.
 */
interface MembershipTermEntityTypeInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * @return string
   */
  public function getWorkflow();

  /**
   * @param string $workflow_id
   * @return mixed
   */
  public function setWorkflow($workflow_id);

  /**
   * @return string
   */
  public function getMembershipType();

  /**
   * @param string $membership_type
   * @return string
   */
  public function setMembershipType($membership_type);
}
