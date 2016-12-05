<?php

namespace Drupal\membership;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership entities.
 *
 * Implements PurchasableEntityInterface so as to allow the membership to
 * be the basis for a line item.
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
   * @return \Drupal\membership\MembershipInterface
   *   The called Membership entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Get the related membership offer.
   *
   * @return \Drupal\membership\MembershipOfferInterface
   */
  public function getOffer();
}
