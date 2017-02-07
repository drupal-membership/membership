<?php

namespace Drupal\membership\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Base class for Membership provider plugins.
 */
abstract class ConfigurableMembershipProviderBase extends MembershipProviderBase implements ConfigurablePluginInterface, PluginFormInterface {

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

  /**
   * @inheritDoc
   */
  public function calculateDependencies() {
    return [
      'module' => ['membership', 'plugin'],
    ];
  }

  /**
   * @inheritDoc
   *
   * @see https://www.drupal.org/node/2536646
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $config = NestedArray::getValue($form_state->getValues(), $form['#parents']);
    if ($config) {
      $this->setConfiguration($config);
    }
  }

}
