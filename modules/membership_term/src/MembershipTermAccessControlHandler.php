<?php

namespace Drupal\membership_term;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Membership term entity.
 *
 * @see \Drupal\membership_term\Entity\MembershipTerm.
 */
class MembershipTermAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\membership_term\Entity\MembershipTermInterface $entity */
    switch ($operation) {
      case 'view':
       // if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished membership term entities');
       // }
        return AccessResult::allowedIfHasPermission($account, 'view published membership term entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit membership term entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete membership term entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add membership term entities');
  }

}
