<?php

namespace Drupal\membership;

use Drupal\membership\Entity\MembershipInterface;
use Drupal\user\Entity\User;

/**
 * Interface MembershipManagerServiceInterface.
 *
 * @package Drupal\membership
 */
interface MembershipManagerServiceInterface {

  /**
   * Return the membership for a user, optionally of the specified type.
   *
   * @param \Drupal\user\Entity\User $user
   *   The user entity for this user.
   *
   * @param string $membership_type
   *   The membership type (bundle machine id). If blank, returns the first membership found.
   *
   * @param bool $create_if_not_found
   *   If TRUE, create a new membership of the specified type for this user if one does not exist.
   *   If FASLE, throw a MembershipNotFoundException if no membership exists.
   *
   * @return \Drupal\membership\Entity\MembershipInterface
   *   The membership for this user. If one does not exist, a blank membership object.
   *
   * @throws \Drupal\membership\Exception\MembershipNotFoundException
   *   If a membership is not found, and $create_if_not_found is false, throws this exception.
   */
  public function getMembership(User $user, $membership_type = '', $create_if_not_found = FALSE);

  /**
   * Return an array of user objects that are controlled by this membership.
   *
   * @param \Drupal\membership\Entity\MembershipInterface $membership
   *   A Membership object attached to one or more users.
   *
   * @return array
   *   An array of User objects.
   */
  public function getUsers(MembershipInterface $membership);
}
