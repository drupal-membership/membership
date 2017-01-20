<?php

/**
 * @file
 * Contains membership_term_entity.page.inc.
 *
 * Page callback for Membership term entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Membership term entity templates.
 *
 * Default template: membership_term_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_membership_term_entity(array &$variables) {
  // Fetch MembershipTermEntity Entity Object.
  $membership_term_entity = $variables['elements']['#membership_term_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
