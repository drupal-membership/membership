<?php

namespace Drupal\membership\Plugin;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Membership provider plugin manager.
 */
class MembershipProviderManager extends DefaultPluginManager {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Entity query service.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

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
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler, EntityFieldManagerInterface $entityFieldManager, EntityTypeManagerInterface $entityTypeManager, QueryFactory $entityQuery) {
    parent::__construct('Plugin/MembershipProvider', $namespaces, $module_handler, 'Drupal\membership\Plugin\MembershipProviderInterface', 'Drupal\membership\Annotation\MembershipProvider');

    $this->alterInfo('membership_provider_membership_provider_info');
    $this->setCacheBackend($cache_backend, 'membership_provider_membership_provider_plugins');
    $this->entityFieldManager = $entityFieldManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->entityQuery = $entityQuery;
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
  public function findEntityByFieldProperty($plugin, $property, $value) {
    foreach ($this->getFieldInstances($plugin) as $entity_type => $def) {
      foreach ($def as $entityId => $config) {
        if ($config[$property] !== $value) {
          continue;
        }
        $entity = $this->entityTypeManager
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
  public function getFieldInstances(string $id) {
    $instances = $this->entityFieldManager->getFieldMapByFieldType('plugin:membership_provider');
    $tags = [];
    foreach ($instances as $entity_type => $def) {
      foreach ($def as $field_name => $field_config) {
        $query = $this->entityQuery->get($entity_type)->condition($field_name . '.plugin_id', $id);
        if ($entity_type == 'node') {
          $query->condition('status', NODE_PUBLISHED);
        }
        if ($result = $query->execute()) {
          $entities = $this->entityTypeManager->getStorage($entity_type)->loadMultiple($result);
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
  public function getFieldedEntities(string $id) {
    $configs = [];
    foreach ($this->getFieldInstances($id) as $entity_type => $defs) {
      $configs = array_merge($configs, $defs);
    }
    return $configs;
  }

  /**
   * @inheritDoc
   */
  public function getInstance(array $options) {
    // $options should match the structure of the Membership's provider field.
    if (empty($options['plugin_id'])) {
      return FALSE;
    }
    try {
      $instance = $this->createInstance($options['plugin_id'])
        ->configureFromId($options['remote_id']);
    }
    catch (\Exception $e) {
      return FALSE;
    }
    return $instance;
  }

}
