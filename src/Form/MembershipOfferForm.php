<?php

namespace Drupal\membership\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Membership Offer edit forms.
 *
 * @ingroup membership
 */
class MembershipOfferForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\membership\Entity\MembershipOffer */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

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
        drupal_set_message($this->t('Created the %label Membership Offer.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Membership Offer.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.membership_offer.canonical', ['membership_offer' => $entity->id()]);
  }

}
