<?php

use Drupal\taxonomy\Entity\Term;

function post_update_demo_post_update_vocabulary() {

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