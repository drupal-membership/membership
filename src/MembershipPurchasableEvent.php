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
   * The price.
   *
   * @var \Drupal\commerce_price\Price|null
   */
  protected $price;

  /**
   * Applicable stores.
   *
   * @var \Drupal\commerce_store\Entity\StoreInterface[]
   */
  protected $stores = [];

  /**
   * @return \Drupal\commerce_store\Entity\StoreInterface[]
   */
  public function getStores() {
    return $this->stores;
  }

  /**
   * @param \Drupal\commerce_store\Entity\StoreInterface[] $stores
   */
  public function setStores($stores) {
    $this->stores = $stores;
  }

  /**
   * @return \Drupal\commerce_price\Price|null
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * @param \Drupal\commerce_price\Price|null $price
   */
  public function setPrice($price) {
    $this->price = $price;
  }

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
