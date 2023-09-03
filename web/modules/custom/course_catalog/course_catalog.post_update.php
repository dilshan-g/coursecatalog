<?php

/**
 * @file
 * Course Catalog module post update processess.
 */

use Drupal\node\Entity\Node;


/**
 * Create a new Basic page for /courses path.
 */
function course_catalog_post_update_create_courses_basic_page(&$sandbox) {
  $node = Node::create([
    'type' => 'page',
    'langcode' => 'en',
    'title' => 'Courses',
    'path' => '/courses',
  ]);
  $node->save();
}
