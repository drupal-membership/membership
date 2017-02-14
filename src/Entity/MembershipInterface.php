<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership entities.
 *
 * @ingroup membership
 */
interface MembershipInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Membership type.
   *
   * @return string
   *   The Membership type.
   */
  public function getType();

  /**
   * Gets the Membership creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Membership.
   */
  public function getCreatedTime();

  /**
   * Sets the Membership creation timestamp.
   *
   * @param int $timestamp
   *   The Membership creation timestamp.
   *
   * @return \Drupal\membership\Entity\MembershipInterface
   *   The called Membership entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Get the related membership Term.
   *
   * @return \Drupal\membership\Entity\MembershipTermInterface
   *   The currently active membership term.
   */
  public function getTerm();


  /**
   * Get a text description of when this membership expires.
   *
   * @return string
   *   A message for the user describing when this membership expires.
   */
  public function expireNotice();

  /**
   * Extend the membership by the default term length.
   *
   * @return \Drupal\membership\Entity\MembershipInterface
   *   The called Membership entity.
   */
  public function extend();

  /**
   * Cancel the current membership immediately.
   *
   * @return \Drupal\membership\Entity\MembershipInterface
   *   The called Membership entity.
   */
  public function cancel();

  /**
   * Return true if this membership is expired.
   *
   * @return bool
   */
  public function isExpired();

  /**
   * Return true if this membership is active.
   *
   * @return bool
   */
  public function isActive();

  /**
   * Gets the workflow ID for the state field.
   *
   * @param \Drupal\membership\Entity\MembershipInterface $membership
   *   The membership.
   *
   * @return string
   *   The workflow ID.
   */
  static public function getWorkflowId(MembershipInterface $membership);

  /**
   * Return a configured provider plugin for the membership.
   *
   * @return \Drupal\membership\Plugin\MembershipProviderInterface|NULL
   */
  public function getProviderPlugin();

}
