<?php

namespace Drupal\membership\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\membership\Entity\MembershipTypeInterface;
use Drupal\state_machine\WorkflowManagerInterface;

/**
 * Class MembershipTypeForm.
 *
 * @package Drupal\membership\Form
 */
class MembershipTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /* @var WorkflowManagerInterface $workflow_manager */
    $workflow_manager = \Drupal::service('plugin.manager.workflow');
    $workflows = $workflow_manager->getGroupedLabels('membership');

    /** @var MembershipTypeInterface $entity */
    $entity = $this->entity;
    $membership_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $membership_type->label(),
      '#description' => $this->t("Label for the Membership type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $membership_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\membership\Entity\MembershipType::load',
      ),
      '#disabled' => !$membership_type->isNew(),
    );
    $form['workflow'] = [
      '#type' => 'select',
      '#title' => t('Workflow'),
      '#options' => $workflows,
      '#default_value' => $entity->getWorkflowId(),
      '#description' => $this->t('Used by all memberships of this type.'),
      '#required' => true,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $membership_type = $this->entity;
    $status = $membership_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Membership type.', [
          '%label' => $membership_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Membership type.', [
          '%label' => $membership_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($membership_type->toUrl('collection'));
  }

}
