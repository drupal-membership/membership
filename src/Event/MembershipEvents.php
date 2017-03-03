<?php

namespace Drupal\membership\Event;

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
   * Membership is updated.
   */
  const UPDATED = 'membership.updated';
  /**
   * Membership state changes.
   */
  const STATE_CHANGE = 'membership.state_change';

  const PURCHASABLE_GET_TITLE = 'membership.purchasable.get_name';

  const PURCHASABLE_GET_LINE_ITEM_TYPE = 'membership.purchasable_line_item';

  const PURCHASABLE_GET_PRICE = 'membership.purchasable_price';

  const PURCHASABLE_GET_STORES = 'membership.purchasable_stores';

}
