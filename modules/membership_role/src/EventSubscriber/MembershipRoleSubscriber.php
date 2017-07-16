<?php

namespace Drupal\membership_role\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\membership\MembershipManagerServiceInterface;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MembershipRoleSubscriber.
 *
 * @package Drupal\membership_role
 */
class MembershipRoleSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\membership\MembershipManagerService definition.
   *
   * @var \Drupal\membership\MembershipManagerService
   */
  protected $membershipManager;

  /**
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(MembershipManagerServiceInterface $membership_manager, EntityTypeManagerInterface $entityTypeManager) {
    $this->membershipManager = $membership_manager;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events = [
      'membership.activate.post_transition' => 'addRole',
      'membership.restart.post_transition' => 'addRole',
      'membership.expire.post_transition' => 'removeRole',
    ];

    return $events;
  }

  public function addRole(WorkflowTransitionEvent $event) {
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $event->getEntity();
    /** @var \Drupal\membership\Entity\MembershipTypeInterface $membership_type */
    $membership_type = $this->entityTypeManager->getStorage('membership_type')->load($membership->bundle());
    $roleName = $membership_type->getThirdPartySetting('membership_role', 'role');

    $users = $this->membershipManager->getUsers($membership);

    /** @var \Drupal\user\Entity\User $user */
    foreach ($users as $user) {
      $user->addRole($roleName);
      $user->save();
    }
  }

  public function removeRole(WorkflowTransitionEvent $event) {
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $event->getEntity();
    /** @var \Drupal\membership\Entity\MembershipTypeInterface $membership_type */
    $membership_type = $this->entityTypeManager->getStorage('membership_type')->load($membership->bundle());
    $roleName = $membership_type->getThirdPartySetting('membership_role', 'role');

    $users = $this->membershipManager->getUsers($membership);

    /** @var \Drupal\user\Entity\User $user */
    foreach ($users as $user) {
      $user->removeRole($roleName);
      $user->save();
    }

  }

}
