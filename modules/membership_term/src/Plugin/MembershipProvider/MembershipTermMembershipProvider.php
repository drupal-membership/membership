<?php

namespace Drupal\membership_term\Plugin\MembershipProvider;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\membership\Plugin\MembershipProviderBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\membership\MembershipManagerService;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * @MembershipProvider(
 *  id = "membership_term",
 *  label = @Translation("MembershipTerm provider."),
 *  description = @Translation("A plugin for setting automatic membership terms on a membership."),
 *  deriver = "Drupal\membership_term\Plugin\Deriver\MembershipTerm"
 * )
 */
class MembershipTermMembershipProvider extends MembershipProviderBase implements ContainerFactoryPluginInterface {

  /**
   * The membership_term that is currently active.
   *
   * @var int
   */
  protected $id;
  /**
   * Drupal\membership\MembershipManagerService definition.
   *
   * @var \Drupal\membership\MembershipManagerService
   */
  protected $membershipManager;
  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param MembershipManagerService $membership_manager
   *   The MembershipManager
   * @param EntityTypeManager $entity_type_manager
   *   The entity_type manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MembershipManagerService $membership_manager, EntityTypeManager $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->membershipManager = $membership_manager;
    $this->entityTypeManager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('membership.manager'),
      $container->get('entity_type.manager')
    );
  }

  public function configureFromId($id) {
    // TODO: Implement configureFromId() method.
    $this->id = $id;
    return $this;
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    /** @var EntityStorageInterface $membership_type_storage */
    $membership_type_storage = $this->entityTypeManager->getStorage('membership_type');
    $membership_type_configs = $membership_type_storage->loadMultiple();

    $membership_types = array();
    foreach ($membership_type_configs as $k => $type) {
      /** @var \Drupal\membership\Entity\MembershipTypeInterface $type */
      $membership_types[$k] = $type->label();
    }
    $values = $this->getConfiguration() + $this->defaultConfiguration();

    $form['membership_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Membership Type'),
      '#options' => $membership_types,
      '#default_value' => $values['membership_type'],
      '#description' => $this->t('Used by all membership terms of this type.'),
      '#required' => true,
    ];

    $form['term_length'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term Length'),
      '#description' => $this->t('Use a time interval or date recognized by strtotime.'),
      '#default_value' => $values['term_length'],
      '#size' => 60,
    ];
    return $form;
  }

  public function defaultConfiguration() {
    return [
      'membership_type' => '',
      'term_length' => '',
    ];
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement validateConfigurationForm() method.
    $i = $form_state->cleanValues();
  }


}
