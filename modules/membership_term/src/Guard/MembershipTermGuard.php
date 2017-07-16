<?php

namespace Drupal\membership_term\Guard;

use Drupal\Core\Entity\EntityInterface;
use Drupal\state_machine\Guard\GuardInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowTransition;

/**
 * Class MembershipTermGuard.
 *
 * @package Drupal\membership_term
 */
class MembershipTermGuard implements GuardInterface {

  /**
   * Constructs a new MembershipTermGuard object.
   */
  public function __construct() {

  }

  public function allowed(WorkflowTransition $transition, WorkflowInterface $workflow, EntityInterface $entity) {
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $entity->getMembership();

  }

}
