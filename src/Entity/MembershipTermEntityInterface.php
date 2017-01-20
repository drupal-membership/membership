<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership term entity entities.
 *
 * @ingroup membership
 */
interface MembershipTermEntityInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Membership term entity type.
   *
   * @return string
   *   The Membership term entity type.
   */
  public function getType();

  /**
   * Gets the Membership term entity name.
   *
   * @return string
   *   Name of the Membership term entity.
   */
  public function getName();

  /**
   * Sets the Membership term entity name.
   *
   * @param string $name
   *   The Membership term entity name.
   *
   * @return \Drupal\membership\Entity\MembershipTermEntityInterface
   *   The called Membership term entity entity.
   */
  public function setName($name);

  /**
   * Gets the Membership term entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Membership term entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Membership term entity creation timestamp.
   *
   * @param int $timestamp
   *   The Membership term entity creation timestamp.
   *
   * @return \Drupal\membership\Entity\MembershipTermEntityInterface
   *   The called Membership term entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Membership term entity published status indicator.
   *
   * Unpublished Membership term entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Membership term entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Membership term entity.
   *
   * @param bool $published
   *   TRUE to set this Membership term entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\membership\Entity\MembershipTermEntityInterface
   *   The called Membership term entity entity.
   */
  public function setPublished($published);

  /**
   * Returns the Membership Type (bundle) that this membership term is associated with.
   *
   * Should be implemented per bundle.
   *
   * @return string
   */
  public function getMembershipType();
}

