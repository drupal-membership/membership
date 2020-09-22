<?php
/**
 * @file
 * Contains \Drupal\membership_term\Plugin\Derivative\MembershipTerm.
 */

namespace Drupal\membership_term\Plugin\Derivative;


use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MembershipTerm extends DeriverBase implements ContainerDeriverInterface {

  /**
   * List of MembershipTerm bundles.
   *
   * @var array
   */
  protected $bundleInfo;

  public function __construct($bundle_info) {
    $this->bundleInfo = $bundle_info;
  }

  /**
   * Creates a new class instance.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the fetcher.
   * @param string $base_plugin_id
   *   The base plugin ID for the plugin ID.
   *
   * @return static
   *   Returns an instance of this fetcher.
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.bundle.info')->getBundleInfo('membership_term')
    );
  }

  public function getDerivativeDefinitions($base_plugin_definition) {
    return $this->bundleInfo;
  }
}