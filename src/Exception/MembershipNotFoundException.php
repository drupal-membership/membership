<?php

namespace Drupal\membership\Exception;

use Drupal\user\Entity\User;

/**
 * Class MembershipNotFoundException
 *
 * @package Drupal\membership\Exception
 */
class MembershipNotFoundException extends \Exception {

  /**
   * MembershipNotFoundException constructor.
   *
   * @param \Drupal\user\Entity\User|NULL $user
   *   The user account associated with the membership.
   * @param string $membership_type
   *   The membership type (bundle machine id).
   */
  public function __construct(User $user = null, $membership_type = '') {
    if (empty($user)) {
      $message = 'No membership found, no user specified.';
    } else {
      $message = sprintf(
        'No %s membership found for "%s".',
        $membership_type,
        $user->label()
      );
    }
    parent::__construct($message);
  }
}
