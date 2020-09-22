<?php

namespace Drupal\membership_term\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\membership\Entity\MembershipTermInterface;
use Drupal\membership\Event\MembershipEvent;
use Drupal\membership\Event\MembershipEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\membership\MembershipManagerService;

/**
 * Class MembershipSubscriber.
 *
 * @package Drupal\membership_term
 */
class MembershipSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\membership\MembershipManagerService definition.
   *
   * @var \Drupal\membership\MembershipManagerService
   */
  protected $membershipManager;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;
  /**
   * Constructor.
   */
  public function __construct(MembershipManagerService $membership_manager, LoggerChannelFactoryInterface $factory) {
    $this->membershipManager = $membership_manager;
    $this->loggerFactory = $factory;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[MembershipEvents::UPDATED][] = ['onUpdate'];
    return $events;
  }

  /**
   * Update event handler.
   *
   * @param \Drupal\membership\Event\MembershipEvent $event
   */
  public function onUpdate(MembershipEvent $event) {
    $membership = $event->getMembership();

    // Ensure there are back references on terms
    /** @var MembershipTermInterface $term */
    foreach ($membership->get('field_membership_term')->referencedEntities() as $term) {
      if (!$term->getMembership()) {
        $term->setMembership($membership);
        $term->save();
      }
    }
  }


}
