<?php

namespace Drupal\membership;

use Symfony\Component\EventDispatcher\Event;

class MembershipEvent extends Event {

  /**
   * @var \Drupal\membership\MembershipInterface
   */
  protected $membership;

  /**
   * @return \Drupal\membership\MembershipInterface
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
