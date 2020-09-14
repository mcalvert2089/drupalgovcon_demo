<?php

use Drupal\node\Entity\Node;

function post_update_demo_post_update_demo() {

  page_create();
  vocabulary_create();

}

function page_create() {

  \Drupal::logger('post_update_demo')->notice('Page is being created...');

  // Creating landing page
  $node = Node::create(['type' => 'page']);

  // Set page title
  $title = 'DrupalGovCon Demo Page';
  $node->set('title', $title);   

  // Set body copy
  $body = [
    'value' => '<p>This is a sample page produed by the post-update script.</p>',
    'format' => 'full_html',
  ];
  $node->set('body', $body);

  // Publish Page
  $node->set('uid', 1);
  $node->status = 1;
  $node->save();
}


function vocabulary_create() {

  \Drupal::logger('post_update_demo')->notice('Vocabulary is being created...');

  $vid = 'drupal_gov_con';
  $name = 'Drupal Gov Con';
  $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
  
  if (!isset($vocabularies[$vid])) {
    $vocabulary = \Drupal\taxonomy\Entity\Vocabulary::create(array(
          'vid' => $vid,
          //'machine_name' => $vid,
          'description' => '',
          'name' => $name,
    ));
    $vocabulary->save();

  } else {

    // Vocabulary Already exist
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vid);
    $tids = $query->execute();

  }

}