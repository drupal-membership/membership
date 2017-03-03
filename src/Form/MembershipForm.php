<?php

namespace Drupal\membership\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\entity\Form\RevisionableContentEntityForm;

/**
 * Form controller for Membership edit forms.
 *
 * @ingroup membership
 */
class MembershipForm extends RevisionableContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\membership\Entity\Membership */
    $form = parent::buildForm($form, $form_state);

    // Hide revision message and revision fields...
    $form['revision_information']['#access'] = FALSE;
    $form['revision_log_message']['#access'] = FALSE;
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Membership.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Membership.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.membership.canonical', ['membership' => $entity->id()]);
  }

}
