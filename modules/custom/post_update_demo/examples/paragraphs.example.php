<?php
function post_update_demo_post_add_paragraphs() {

  function createAndAddParagraph($node, $array) {
    $paragraph = Paragraph::create([
      'type' => 'paragraph_machine_name',
      'field_name' => $array[0],
      'field_program_description' => [
        'value' => $array[1],
        'format' => 'formatted',
      ]
    ]);
    $paragraph->save();

    $node->field_paragraphs->appendItem($paragraph);
  }

  \Drupal::logger('custom_module')->notice('Custom Module Page Creation and Paragraphs being created...');

  // Create the page
  $node = Node::create(['type' => 'page']);

  // set data array
  $data = [
    ['Name 1', 'Aliquam porta, leo eget malesuada pulvinar, turpis mi interdum leo.'],
    ['Name 2', 'Nulla velit lorem, vulputate sed lobortis nec, posuere vel dui.'],
    ['Name 3', 'Quisque malesuada, ligula eu iaculis mollis.']
  ];

  // loop over array to add paraphs to page
  foreach($data as $array) createAndAddParagraph($node, $array);

  // Publish Page
  $node->set('uid', 1);
  $node->status = 1;
  $node->save();
}
