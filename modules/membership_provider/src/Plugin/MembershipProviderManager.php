<?php

namespace Drupal\membership_provider\Plugin;

use Drupal\Core\Entity\EntityFieldManager;
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

  /**
   * Find an entity containing a fielded plugin config, by config property.
   *
   * @param $plugin string The plugin ID
   * @param $property string The property name
   * @param $value mixed The property value to find
   * @return array Array of:
   *   - ContentEntityInterface The entity
   *   - array The config
   * @throws \Exception
   */
  public static function findEntityByFieldProperty($plugin, $property, $value) {
    foreach (static::getFieldInstances($plugin) as $entity_type => $def) {
      foreach ($def as $entityId => $config) {
        if ($config[$property] !== $property) {
          continue;
        }
        $entity = \Drupal::entityTypeManager()
          ->getStorage($entity_type)
          ->load($entityId);
        return [$entity, $config];
      }
    }
    throw new \Exception("No matching entity found for config property {$property} == {$value}");
  }

  /**
   * Leverages the plugin module field type to allow for querying stored configurations.
   * 
   * @param $id string The plugin ID to query.
   * @return array Array of plugin configurations
   */
  public static function getFieldInstances(string $id) {
    /* @var EntityFieldManager $manager */
    $manager = \Drupal::service('entity_field.manager');
    $instances = $manager->getFieldMapByFieldType('plugin:membership_provider');
    $tags = [];
    /* @var \Drupal\Core\Entity\EntityTypeManager $manager */
    $manager = \Drupal::service('entity_type.manager');
    foreach ($instances as $entity_type => $def) {
      foreach ($def as $field_name => $field_config) {
        $query = \Drupal::entityQuery($entity_type)->condition($field_name . '.plugin_id', $id);
        if ($entity_type == 'node') {
          $query->condition('status', NODE_PUBLISHED);
        }
        if ($result = $query->execute()) {
          $entities = $manager->getStorage($entity_type)->loadMultiple($result);
          foreach ($entities as $e) {
            foreach ($e->{$field_name} as $row) {
              if ($row->plugin_id == $id) {
                $tags[$entity_type][$e->id()] = $row->plugin_configuration;
              }
            }
          }
        }
      }
    }
    return $tags;
  }

  /**
   * Retrieve all plugin configurations stored in fields,
   * flattened from entity types.
   * 
   * @param $id string Plugin ID
   * @return array Array of plugin configs
   */
  public static function getFieldedEntities(string $id) {
    $configs = [];
    foreach (self::getFieldInstances($id) as $entity_type => $defs) {
      $configs = array_merge($configs, $defs);
    }
    return $configs;
  }

}
