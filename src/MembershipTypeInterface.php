<?php

namespace Drupal\membership;

use Drupal\entity\Entity\RevisionableEntityBundleInterface;

/**
 * Provides an interface for defining Membership type entities.
 */
interface MembershipTypeInterface extends RevisionableEntityBundleInterface {

  /**
   * Report whether the membership of this type is considered expired.
   * 
   * @return boolean|null True or false, or NULL if not able to determine.
   */
  public function isExpired(MembershipInterface $membership);

}
