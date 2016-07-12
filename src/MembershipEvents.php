<?php

namespace Drupal\membership;

/**
 * Class MembershipEvents
 * 
 * Events thrown on particular Membership transitions.
 * 
 * @package Drupal\membership
 */
final class MembershipEvents {

  /**
   * Membership expires.
   */
  const EXPIRE = 'membership.expire';

  /**
   * Membership is created.
   */
  const CREATED = 'membership.create';

  /**
   * Membership state changes.
   */
  const STATE_CHANGE = 'membership.state_change';

}
