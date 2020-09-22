<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 4/7/17
 * Time: 2:34 PM
 */

namespace Drupal\membership_term\Event\MembershipTermEvent;


use Drupal\membership_term\Entity\MembershipTermInterface;
use Symfony\Component\EventDispatcher\Event;

class MembershipTermEvent extends Event {

  /**
   * @var \Drupal\membership_term\Entity\MembershipTermInterface
   */
  protected $membership_type;

  /**
   * @return \Drupal\membership_term\Entity\MembershipTermInterface
   */
  public function getMembership() {
    return $this->membership_type;
  }

  /**
   * @inheritDoc
   */
  public function __construct(MembershipTermInterface $membership_term) {
    $this->membership_term = $membership_term;
  }


}