<?php

namespace Drupal\membership;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\membership\Exception\MembershipNotFoundException;
use Drupal\user\Entity\User;

/**
 * Class MembershipManagerService.
 *
 * @package Drupal\membership
 */
class MembershipManagerService implements MembershipManagerServiceInterface {

  /**
   * Drupal\Core\Entity\EntityManager definition.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;
  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;
  /**
   * Constructor.
   */
  public function __construct(EntityManager $entity_manager, EntityTypeManager $entity_type_manager, QueryFactory $entity_query) {
    $this->entityManager = $entity_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityQuery = $entity_query;
  }

  /**
   * @inheritDoc
   */
  public function getMembership(User $user, $membership_type = '', $create_if_not_found = FALSE) {
    $storage = $this->entityTypeManager->getStorage('membership');
    $query = [
      'user_id' => $user->id(),
    ];
    if (!empty($membership_type)) {
      $query['type'] = $membership_type;
    }
    $memberships = $storage->loadByProperties($query);
    if (count($memberships)) {
      return reset($memberships);
    }
    if ($create_if_not_found) {
      if (empty($membership_type)) {
        throw new EntityStorageException('No membership_type specified.');
      }
      $membership = $storage->create(['type' => $membership_type, 'uid' => $user->id()]);
      return $membership;
    }
    return FALSE;
  }

  /**
   * @inheritDoc
   */
  public function getUsers(MembershipInterface $membership) {
    // For now, this is a simple algorithm that loops through the fields on a membership
    // and builds an array of users from the "user_id" field and any entity reference fields
    // that reference a user entity.
    $user_ids = [
      $membership->get('user_id')->first()->target_id,
    ];
    $users = [];
    $fields = $membership->getFields(FALSE);
    foreach ($fields as $id => $field) {
      $definition = $field->getFieldDefinition();
      if (is_a($definition, '\Drupal\field\Entity\FieldConfig') && $definition->get('field_type') == 'entity_reference' && $definition->getSetting('target_type') == 'user') {
        $values = $membership->get($id)->getValue();

        foreach ($values as $value){
          $user_ids[] = $value['target_id'];
        }
      }
    }
    foreach ($user_ids as $user_id){
      $users[] = User::load($user_id);
    }
    return $users;

  }

}
