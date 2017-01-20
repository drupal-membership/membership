<?php

namespace Drupal\membership\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MembershipTermEntityTypeForm.
 *
 * @package Drupal\membership\Form
 */
class MembershipTermEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity_type_manager = \Drupal::service('entity_type.manager');
    $membership_type_storage = $entity_type_manager->getStorage('membership_type');
    $membership_type_configs = $membership_type_storage->loadMultiple();

    $membership_types = array();
    foreach ($membership_type_configs as $k => $type) {
      $membership_types[$k] = $type->label();
    }

    /* @var WorkflowManagerInterface $workflow_manager */
    $workflow_manager = \Drupal::service('plugin.manager.workflow');
    $workflows = $workflow_manager->getGroupedLabels('membership_term');

    $membership_term_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $membership_term_entity_type->label(),
      '#description' => $this->t("Label for the Membership term entity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $membership_term_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\membership\Entity\MembershipTermEntityType::load',
      ],
      '#disabled' => !$membership_term_entity_type->isNew(),
    ];

    $form['membership_type'] = [
      '#type' => 'select',
      '#title' => t('Membership Type'),
      '#options' => $membership_types,
      '#default_value' => $membership_term_entity_type->getMembershipType(),
      '#description' => $this->t('Used by all membership terms of this type.'),
      '#required' => true,
    ];



    $form['workflow'] = [
      '#type' => 'select',
      '#title' => t('Workflow'),
      '#options' => $workflows,
      '#default_value' => $membership_term_entity_type->getWorkflow(),
      '#description' => $this->t('Used by all membership terms of this type.'),
      '#required' => true,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $membership_term_entity_type = $this->entity;
    $status = $membership_term_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Membership term entity type.', [
          '%label' => $membership_term_entity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Membership term entity type.', [
          '%label' => $membership_term_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($membership_term_entity_type->toUrl('collection'));
  }

}
