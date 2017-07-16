<?php

namespace Drupal\membership;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * Defines a class to build a listing of Membership entities.
 *
 * @ingroup membership
 */
class MembershipListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'id' => $this->t('Membership ID'),
      'user_id' => $this->t('User'),
      'type' => $this->t('Type'),
      'state' => $this->t('State'),
    ];
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\membership\Entity\Membership */
    $user_id = $entity->user_id->target_id;
    /** @var \Drupal\user\Entity\User $user */
    $user = User::load($user_id);
    $row = [
      'id' => Link::createFromRoute(
        $entity->id(),
        'entity.membership.edit_form', array(
          'membership' => $entity->id(),
        )
      ),
      'user_id' => $user->toLink(),
      'type' => $entity->getType(),
      'state' => $entity->state->value,
    ];
    return $row + parent::buildRow($entity);
  }

}
