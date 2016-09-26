<?php

namespace Drupal\membership\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Membership Offer entities.
 */
class MembershipOfferViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['membership_offer']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Membership Offer'),
      'help' => $this->t('The Membership Offer ID.'),
    );

    return $data;
  }

}
