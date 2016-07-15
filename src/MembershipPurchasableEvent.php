<?php

namespace Drupal\membership;

class MembershipPurchasableEvent extends MembershipEvent {

  /**
   * The line item title.
   *
   * @var string
   */
  protected $title = '';

  /**
   * The line item type.
   *
   * @var string
   */
  protected $lineItemType;

  /**
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @param string $title
   */
  public function setTitle(string $title) {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getLineItemType() {
    return $this->lineItemType;
  }

  /**
   * @param string $lineItemType
   */
  public function setLineItemType(string $lineItemType) {
    $this->lineItemType = $lineItemType;
  }

}
