<?php

namespace Drupal\membership;

use Drupal\membership\Entity\MembershipInterface;
use Symfony\Component\EventDispatcher\Event;

class MembershipEvent extends Event {

  /**
   * @var \Drupal\membership\Entity\MembershipInterface
   */
  protected $membership;

  /**
   * @return \Drupal\membership\Entity\MembershipInterface
   */
  public function getMembership() {
    return $this->membership;
  }

  /**
   * @inheritDoc
   */
  public function __construct(MembershipInterface $membership) {
    $this->membership = $membership;
  }

}
