<?php

/**
 * @file
 * Contains membership_term.page.inc.
 *
 * Page callback for Membership term entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Membership term templates.
 *
 * Default template: membership_term.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_membership_term(array &$variables) {
  // Fetch MembershipTerm Entity Object.
  $membership_term= $variables['elements']['#membership_term'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
