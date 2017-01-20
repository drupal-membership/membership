<?php

namespace Drupal\membership;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Membership term entity entity.
 *
 * @see \Drupal\membership\Entity\MembershipTermEntity.
 */
class MembershipTermEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\membership\Entity\MembershipTermEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished membership term entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published membership term entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit membership term entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete membership term entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add membership term entity entities');
  }

}
