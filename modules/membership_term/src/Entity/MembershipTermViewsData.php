<?php

namespace Drupal\membership_term\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Membership term entities.
 */
class MembershipTermViewsData extends EntityViewsData {

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
