<?php

namespace Drupal\membership;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
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
    // TODO: Implement getMembership() method.
    throw new MembershipNotFoundException('Not found.');
  }

}
