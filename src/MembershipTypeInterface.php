<?php

namespace Drupal\membership;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Membership type entities.
 */
interface MembershipTypeInterface extends ConfigEntityInterface {

  /**
   * Report whether the membership of this type is considered expired.
   * 
   * @return boolean|null True or false, or NULL if not able to determine.
   */
  public function isExpired(MembershipInterface $membership);

}
