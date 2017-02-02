<?php

namespace Drupal\membership\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Membership term entities.
 *
 * @ingroup membership
 */
interface MembershipTermInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Membership term type.
   *
   * @return string
   *   The Membership term type.
   */
  public function getType();

  /**
   * Gets the Membership term name.
   *
   * @return string
   *   Name of the Membership term.
   */
  public function getName();

  /**
   * Sets the Membership term name.
   *
   * @param string $name
   *   The Membership term name.
   *
   * @return \Drupal\membership\Entity\MembershipTermInterface
   *   The called Membership term entity.
   */
  public function setName($name);

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
   * Returns the Membership term published status indicator.
   *
   * Unpublished Membership term are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Membership term is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Membership term.
   *
   * @param bool $published
   *   TRUE to set this Membership term to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\membership\Entity\MembershipTermInterface
   *   The called Membership term entity.
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

