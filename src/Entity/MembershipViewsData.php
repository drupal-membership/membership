<?php

namespace Drupal\membership\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Membership entities.
 */
class MembershipViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['membership']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Membership'),
      'help' => $this->t('The Membership ID.'),
    );

    return $data;
  }

}
