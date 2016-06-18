<?php

namespace Drupal\membership;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

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
    $header['id'] = $this->t('Membership ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\membership\Entity\Membership */
    $row['id'] = $this->l(
      $entity->id(),
      new Url(
        'entity.membership.edit_form', array(
          'membership' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
