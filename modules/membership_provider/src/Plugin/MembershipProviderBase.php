<?php

namespace Drupal\membership_provider\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Membership provider plugins.
 */
abstract class MembershipProviderBase extends PluginBase implements MembershipProviderInterface {

  /**
   * @inheritDoc
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * @inheritDoc
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration;
  }

}
