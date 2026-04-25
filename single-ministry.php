<?php
/**
 * The Template for displaying all single ministry posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Timber;

// Get the global Timber context
$context = Timber::context();

// Get the specific ministry post
$timber_post = Timber::get_post();
$context['post'] = $timber_post;

// Render the twig file
Timber::render(['single-ministry.twig', 'single.twig'], $context);