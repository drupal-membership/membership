<?php

namespace Drupal\membership\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

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

    /* You will need additional form elements for your custom properties. */

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
    $form_state->setRedirectUrl($membership_type->urlInfo('collection'));
  }

}
