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

  const PURCHASABLE_GET_TITLE = 'membership.purchasable.get_name';

  const PURCHASABLE_GET_LINE_ITEM_TYPE = 'membership.purchasable_line_item';

}
