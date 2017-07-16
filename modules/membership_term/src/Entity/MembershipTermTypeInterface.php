<?php

namespace Drupal\membership_term\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Membership term type entities.
 */
interface MembershipTermTypeInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * @return string
   */
  public function getWorkflowId();

  /**
   * @param string $workflow_id
   * @return mixed
   */
  public function setWorkflowId($workflow_id);

  /**
   * @return string
   */
  public function getMembershipType();

  /**
   * @return string
   */
  public function getTermLength();

  /**
   * @return string
   */
  public function getGracePeriod();
  /**
   * @param string $membership_type
   * @return string
   */
  public function setMembershipType($membership_type);

  /**
   * @return array
   */
  public function getMessages();

  /**
   * @param $messages
   *
   * @return mixed
   */
}
