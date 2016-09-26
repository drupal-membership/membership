<?php

namespace Drupal\membership;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership Offer entities.
 *
 * @ingroup membership
 */
interface MembershipOfferInterface extends PurchasableEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Membership Offer name.
   *
   * @return string
   *   Name of the Membership Offer.
   */
  public function getName();

  /**
   * Sets the Membership Offer name.
   *
   * @param string $name
   *   The Membership Offer name.
   *
   * @return \Drupal\membership\MembershipOfferInterface
   *   The called Membership Offer entity.
   */
  public function setName($name);

  /**
   * Gets the Membership Offer creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Membership Offer.
   */
  public function getCreatedTime();

  /**
   * Sets the Membership Offer creation timestamp.
   *
   * @param int $timestamp
   *   The Membership Offer creation timestamp.
   *
   * @return \Drupal\membership\MembershipOfferInterface
   *   The called Membership Offer entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Membership Offer published status indicator.
   *
   * Unpublished Membership Offer are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Membership Offer is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Membership Offer.
   *
   * @param bool $published
   *   TRUE to set this Membership Offer to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\membership\MembershipOfferInterface
   *   The called Membership Offer entity.
   */
  public function setPublished($published);

}
