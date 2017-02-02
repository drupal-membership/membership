<?php

namespace Drupal\membership\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'membership_provider_id' field type.
 *
 * @FieldType(
 *   id = "membership_provider_id",
 *   label = @Translation("Provider ID"),
 *   description = @Translation("Stores plugin IDs and optional IDs"),
 *   category = @Translation("Membership"),
 *   default_widget = "",
 *   default_formatter = "",
 *   list_class = "\Drupal\membership\Plugin\Field\FieldType\ProviderIdFieldItemList",
 * )
 */
class ProviderIdItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['plugin_id'] = DataDefinition::create('plugin_id')
      ->setLabel(t('Plugin ID'));
    $properties['remote_id'] = DataDefinition::create('string')
      ->setLabel(t('ID'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'plugin_id' => [
          'description' => 'The plugin ID.',
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'remote_id' => [
          'description' => 'An entity or object ID usable by the provider.',
          'type' => 'varchar_ascii',
          'length' => 255,
          'not null' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'plugin_id';
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return empty($this->plugin_id) || empty($this->remote_id);
  }

}
