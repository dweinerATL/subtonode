<?php

/**
 * This does whatever it wants to.
 */

namespace Drupal\subtonode\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime;

class SubToNodeController extends ControllerBase {
  public function subtonode($webform_submission) {
    //$sid = 2;
    $node_details = WebformSubmission::load($webform_submission);
    $wf_changed = $node_details->getChangedTime();
    $submission_array = $node_details->getOriginalData();
    $title = $submission_array['panel_title'];
    $body = $submission_array['panel_description'];
    $facilator = $submission_array['facilator'];

// Create node object with attached file.
    $node = Node::create([
      'type' => 'panel',
      'title' => $title,
      'body' => [
        'value' => $body,
        'summary' => '',
        'format' => 'markdown',
      ],
      'field_facilator' => $facilator,
    ]);

    $node->save();

    return drupal_set_message(t('You have successfully created a node from webform submission @sid', array('@sid' => $webform_submission)), 'success');
  }
}

