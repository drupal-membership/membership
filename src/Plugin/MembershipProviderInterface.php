<?php

namespace Drupal\membership\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Membership provider plugins.
 */
interface MembershipProviderInterface extends PluginInspectionInterface {

  /**
   * Attempt to configure the plugin (instantiated with a default config)
   * with data from the $id, which is a string representation that should
   * be parse-able by the plugin.
   *
   * @param $id string ID recognizable by the plugin.
   * @return self|false Returns the configured plugin, or false if could not configure.
   */
  public function configureFromId($id);

}
