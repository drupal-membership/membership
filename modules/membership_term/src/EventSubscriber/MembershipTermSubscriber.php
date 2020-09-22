<?php

namespace Drupal\membership_term\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\membership\MembershipManagerServiceInterface;
use Drupal\membership_term\Entity\MembershipTerm;
use Drupal\membership_term\MembershipTermManagerInterface;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\membership\MembershipManagerService;

/**
 * Class MembershipTermSubscriber.
 *
 * @package Drupal\membership_term
 */
class MembershipTermSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\membership\MembershipManagerService definition.
   *
   * @var \Drupal\membership\MembershipManagerServiceInterface
   */
  protected $membershipManager;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * @var MembershipTermManagerInterface
   */
  protected $membershipTermManager;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(MembershipManagerServiceInterface $membership_manager, LoggerChannelFactoryInterface $loggerChannelFactory,
                              MembershipTermManagerInterface $membershipTermManager, EntityTypeManagerInterface $entityTypeManager) {
    $this->membershipManager = $membership_manager;
    $this->loggerFactory = $loggerChannelFactory;
    $this->membershipTermManager = $membershipTermManager;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events = [
      'membership_term.replace.pre_transition' => 'updateMembershipTerm',
      'membership_term.restart.pre_transition' => 'updateMembershipTerm',
      'membership_term.activate.post_transition' => 'activateMembership',
      'membership_term.expiring.post_transition' => 'activateMembership',
      'membership_term.expire.pre_transition' => 'cancelMembershipTerm',
      'membership_term.expire.post_transition' => 'deactivateMembership',
    ];

    return $events;
  }

  /**
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   */
  public function activateMembership(WorkflowTransitionEvent $event) {
    /** @var \Drupal\membership_term\Entity\MembershipTermInterface $membership_term */
    $membership_term = $event->getEntity();
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $membership_term->getMembership();
    $membership->state->value = 'active';
    $membership->save();
    $this->loggerFactory->get('membership_term')
      ->notice('Membership Term @mt_id activated for Membership @mid',
        [ '@mid' => $membership->id(),
          '@mt_id' => $membership_term->id()]);
  }

  public function deactivateMembership(WorkflowTransitionEvent $event) {
    /** @var \Drupal\membership_term\Entity\MembershipTermInterface $membership_term */
    $membership_term = $event->getEntity();
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $membership_term->getMembership();
    $membership->state->value = 'expired';
    $membership->save();
    $this->loggerFactory->get('membership_term')
      ->notice('Membership Term @mt_id deactivated for Membership @mid',
        [ '@mid' => $membership->id(),
          '@mt_id' => $membership_term->id()]);
  }

  /**
   * Create a new membership term, and set the membership to use the new term.
   *
   * If the previous membership is active or expiring, set it to "renewed" and
   * set the new membership term to start on the expiration date.
   *
   * If the previous membership is expired, simply save the new term with the
   * defaults, and let the postSave re-activate the membership.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   */
  public function updateMembershipTerm(WorkflowTransitionEvent $event) {
    /** @var \Drupal\membership_term\Entity\MembershipTermInterface $membership_term */
    $membership_term = $event->getEntity();
    /** @var \Drupal\membership\Entity\MembershipInterface $membership */
    $membership = $membership_term->getMembership();

    $fromstate = $event->getFromState();
    $candidate = FALSE;

    $terms = $this->membershipTermManager->getAllTermsForMembership($membership);
    // See if there's a future term...
    /** @var \Drupal\membership_term\Entity\MembershipTermInterface $term */
    foreach ($terms as $term) {
      if ($term->isPending() || $term->isActive()) {
        $candidate = $term;
      }
    }
    if (!$candidate) {
      $props = [
        'type' => $membership_term->bundle(),
        'membership_id' => $membership->id(),
      ];
      $storage = $this->entityTypeManager->getStorage('membership_term');
      $candidate = $storage->create($props);
    }
    if (in_array($fromstate->getId(), ['active', 'expiring'])) {
      $candidate->setStartDate($membership_term->field_active_dates->end_value, TRUE);
      $candidate->updateRevokeDate();
    }
    $candidate->save();
  }

  /**
   * Cancel an active or expiring membership.
   *
   * Set the end and revoke dates to today, if they are in the future.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   */
  public function cancelMembershipTerm(WorkflowTransitionEvent $event) {
    $membership_term = $event->getEntity();
    $membership_term->cancel();
  }
}
