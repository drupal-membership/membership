<?php

namespace Drupal\membership\Entity;

use Drupal\entity\Entity\RevisionableEntityBundleInterface;
use Drupal\membership\Plugin\MembershipProviderInterface;

/**
 * Provides an interface for defining Membership type entities.
 */
interface MembershipTypeInterface extends RevisionableEntityBundleInterface {

  /**
   * Report whether the membership of this type is considered expired.
   * @param \Drupal\membership\Entity\MembershipInterface $membership
   * 
   * @return boolean|null True or false, or NULL if not able to determine.
   */
  public function isExpired(MembershipInterface $membership);

  /**
   * @return string
   */
  public function getWorkflowId();

  /**
   * @param string $workflow_id
   * @return MembershipTypeInterface This object
   */
  public function setWorkflowId($workflow_id);

  /**
   * Get the provider plugin for this membership type.
   *
   * @return MembershipProviderInterface
   */
  public function getProcessorPlugin();

  /**
   * Set the processor plugin type and instance id.
   *
   * @param $processor_plugin_id string
   * @return MembershipTypeInterface This object
   */
  public function setProcessorPluginId($processor_plugin_id);

}
