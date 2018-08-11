<?php

/**
 * This does whatever it wants to.
 */

namespace Drupal\subtonode\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\node\Entity\Node;

class SubToNodeController extends ControllerBase {

  public function subtonode($webform_submission) {
    // Make sure that we're not trying to process a submission that's already
    // been added as a node

    if (empty(\Drupal::entityQuery('node')
      ->condition('field_webform_id', $webform_submission)
      ->execute())) {
      $node_details = WebformSubmission::load($webform_submission);
      $submission_array = $node_details->getOriginalData();
      $title = $submission_array['panel_title'];
      $body = $submission_array['panel_description'];
      $facilitator = $submission_array['facilitator'];

      // Create node object
      $node = Node::create([
        'type' => 'panels',
        'title' => $title,
        'field_panel_description' => $body,
        'field_moderators' => $facilitator,
        'field_webform_id' => $webform_submission,
      ]);

      $node->save();

      return drupal_set_message(t('You have successfully created a node from webform submission @sid',
        ['@sid' => $webform_submission]), 'status');

    }

    return drupal_set_message(t('A node has already been created form webform submission @sid',
      ['@sid' => $webform_submission]), 'warning');
  }
}

