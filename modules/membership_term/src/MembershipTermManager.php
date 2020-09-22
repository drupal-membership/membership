<?php

namespace Drupal\membership_term;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\membership\Entity\MembershipInterface;

/**
 * Class MembershipTermManager.
 *
 * @package Drupal\membership_term
 */
class MembershipTermManager implements MembershipTermManagerInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Constructor.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @inheritdoc
   */
  public function expireTerms() {
    $storage = $this->entityTypeManager->getStorage('membership_term');

    /** @var DrupalDateTime $today */
    $today = new DrupalDateTime();
    $today->setTimezone(new \DateTimeZone(DATETIME_STORAGE_TIMEZONE));
    $today_date = $today->format(DATETIME_DATE_STORAGE_FORMAT);

    // Revoke all expiring memberships at revoke date
    $revoke_query = $storage->getQuery();
    $revoke_ids = $revoke_query->condition('state', 'expiring')
      ->condition('field_revoke_date.value', $today_date, '<=')
      ->execute();

    foreach ($revoke_ids as $mt_id) {
      /** @var \Drupal\membership_term\Entity\MembershipTermInterface $term */
      $term = $storage->load($mt_id);
      // resave does this already:
      //$term->set('state', 'expired');
      $term->save();
    }

    // Expire all active memberships at end of expiration
    $expire_query = $storage->getQuery();
    $expire_ids = $expire_query->condition('state', 'active')
      ->condition('field_active_dates.end_value', $today_date, '<=')
      ->execute();
    foreach ($expire_ids as $mt_id) {
      /** @var \Drupal\membership_term\Entity\MembershipTermInterface $term */
      $term = $storage->load($mt_id);
      // resave does this already:
      //$term->set('state', 'expiring');
      $term->save();
    }

    // Activate all pending memberships at the start date
    $activate_query = $storage->getQuery();
    $activate_ids = $activate_query->condition('state', 'pending')
      ->condition('field_active_dates.value', $today_date, '<=')
      ->execute();
    foreach ($activate_ids as $mt_id) {
      /** @var \Drupal\membership_term\Entity\MembershipTermInterface $term */
      $term = $storage->load($mt_id);
      // resave does this already:
      //$term->set('state', 'pending');
      $term->save();
    }

  }

  /**
   * @inheritdoc
   */
  public function getAllTermsForMembership(MembershipInterface $membership) {
    $terms = [];
    $storage = $this->entityTypeManager->getStorage('membership_term');
    $terms = $storage->loadByProperties(['membership_id' => $membership->id()]);

    return $terms;
  }

}
