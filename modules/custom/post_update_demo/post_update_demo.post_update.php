<?php

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

function post_update_demo_post_update_demo() {

  page_create();
  vocabulary_create();
  taxonomy_create();

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

function taxonomy_create() {

  \Drupal::logger('post_update_demo')->notice('Adding taxonomy to vocabulary...');

  // function that will process data from the given array
  function addTaxonomyData($imagePath, $imageNode, $data) {
    $icon_file = File::create([ 'uri' => $imagePath ]);
    $icon_file->save();
    $data[$imageNode]['target_id'] = $icon_file->id();
    $data[$imageNode]['alt'] = $data['name'];

    Term::create($data)->save();
  }

  // Set the module and image path
  $module_path = drupal_get_path('module', 'post_update_demo');
  $images = $module_path . '/images/';

  // Set vocabulary machine name to use
  $vid = 'drupal_gov_con';

  // Taxonomy data array
  $taxonomies = [
    ['image1.jpg', 'Category1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
    ['image2.jpg', 'Category2', 'Phasellus ac leo ipsum.'],
    ['image3.jpg', 'Category3', 'Curabitur est nibh, suscipit et laoreet vitae, tristique in purus.']
  ];

  // loop through the data array and add taxonomies
  foreach($taxonomies as $key => $array) {
    addTaxonomyData(

      $images . $array[0],
      'field_category_image',
      // pass array that matches the data stucture of the taxonomy term
      [
        'parent' => array(),
        'name' => $array[1],
        'vid' => $vid,
        'description' => [
          'value' => isset($array[2]) ? $array[2] : '',
          'format' => 'formatted',
        ]
      ]
    ); 
  }
}