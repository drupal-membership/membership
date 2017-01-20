<?php

namespace Drupal\membership\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Membership term entity entities.
 */
class MembershipTermEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
