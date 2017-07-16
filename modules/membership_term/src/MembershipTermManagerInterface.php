<?php

namespace Drupal\membership_term;

use Drupal\membership\Entity\MembershipInterface;

/**
 * Interface MembershipTermManagerInterface.
 *
 * @package Drupal\membership_term
 */
interface MembershipTermManagerInterface {

  /**
   * Activate membership terms passing the active start date.
   *
   * Set all membership terms past the active end date to "expiring" state.
   *
   * Expire all membership terms past the revoke date.
   *
   * @return void
   */
  public function expireTerms();


  /**
   * @param \Drupal\membership\Entity\MembershipInterface $membership
   *   The membership to grab terms from.
   *
   * @return array
   *   Array of membership terms, if any -- empty array if not.
   */
  public function getAllTermsForMembership(MembershipInterface $membership);

}
