<?php

namespace Drupal\membership_provider\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Membership provider plugin manager.
 */
class MembershipProviderManager extends DefaultPluginManager {

  /**
   * Constructor for MembershipProviderManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/MembershipProvider', $namespaces, $module_handler, 'Drupal\membership_provider\Plugin\MembershipProviderInterface', 'Drupal\membership_provider\Annotation\MembershipProvider');

    $this->alterInfo('membership_provider_membership_provider_info');
    $this->setCacheBackend($cache_backend, 'membership_provider_membership_provider_plugins');
  }

}
