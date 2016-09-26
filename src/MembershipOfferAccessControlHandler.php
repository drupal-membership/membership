<?php

namespace Drupal\membership;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Membership Offer entity.
 *
 * @see \Drupal\membership\Entity\MembershipOffer.
 */
class MembershipOfferAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\membership\MembershipOfferInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished membership offer entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published membership offer entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit membership offer entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete membership offer entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add membership offer entities');
  }

}
