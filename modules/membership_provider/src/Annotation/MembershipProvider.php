<?php

namespace Drupal\membership_provider\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Membership provider item annotation object.
 *
 * @see \Drupal\membership_provider\Plugin\MembershipProviderManager
 * @see plugin_api
 *
 * @Annotation
 */
class MembershipProvider extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
