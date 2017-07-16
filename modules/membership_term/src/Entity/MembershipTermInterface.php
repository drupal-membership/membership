<?php

namespace Drupal\membership_term\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership term entities.
 *
 * @ingroup membership
 */
interface MembershipTermInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.


  /**
   * Gets the membership for a term
   *
   * @return Membership
   *   The membership entity
   */
  public function getMembership();

  /**
   * Set a membership on a term.
   *
   * @param \Drupal\membership\Entity\MembershipInterface $membership
   *
   * @return \Drupal\membership\Entity\MembershipTermInterface
   *   The called Membership term.
   */
  public function setMembership(MembershipInterface $membership);
  /**
   * Gets the membership id for a term
   *
   * @return integer
   *   The membership entity_id
   */
  public function getMembershipId();

  /**
   * Gets the Membership term type.
   *
   * @return string
   *   The Membership term type.
   */
  public function getType();

  /**
   * Gets the Membership term creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Membership term.
   */
  public function getCreatedTime();

  /**
   * Sets the Membership term creation timestamp.
   *
   * @param int $timestamp
   *   The Membership term creation timestamp.
   *
   * @return \Drupal\membership\Entity\MembershipTermInterface
   *   The called Membership term.
   */
  public function setCreatedTime($timestamp);

  /**
   * @return bool
   *   Whether this term is pending.
   */
  public function isPending();

  /**
   * @return bool
   *   Whether this term is active.
   */
  public function isActive();

  /**
   * @return bool
   *   Whether this term is expiring.
   */
  public function isExpiring();

  /**
   * @return bool
   *   Whether this term is expired.
   */
  public function isExpired();

  /**
   * @return bool
   *   Whether this term is renewed.
   */
  public function isRenewed();

  /**
   * Set the start date of this term to $date.
   *
   * Updates the end date and revoke date according to membership_term type.
   *
   * @param string $date
   *
   * @param bool $update_end_date
   *   Whether to update end date or only start date.
   *
   * @return void
   */
  public function setStartDate($date, $update_end_date = FALSE);

  /**
   * Updates the revokeDate.
   *
   * @return int
   *   Timestamp of new revoke date.
   */
  public function updateRevokeDate();

  /**
   * Cancel a membership term by setting dates to yesterday.
   *
   * @return void
   */
  public function cancel();

  /**
   * Returns the Membership Type (bundle) that this membership term is associated with.
   *
   * Should be implemented per bundle.
   *
   * @return string
   */
  public function getMembershipType();

  /**
   * Gets the workflow ID for the state field.
   *
   * @param \Drupal\membership\MembershipTermInterface $membership_term
   *   The membership.
   *
   * @return string
   *   The workflow ID.
   */
  static public function getWorkflowId(MembershipTermInterface $membership_term);

}

