<?php

namespace Drupal\membership\Exception;

/**
 * Class MembershipFeatureNotImplementedException
 *
 * @package Drupal\membership\Exception
 */
class MembershipFeatureNotImplementedException extends \Exception {

  /**
   * MembershipFeatureNotImplementedException constructor.
   *
   * @param string $feature_name
   */
  public function __construct($feature_name) {
    $message = sprintf(
      'Feature "%s" is not supported by this membership class.',
      $feature_name
    );
    parent::__construct($message);
  }
}
