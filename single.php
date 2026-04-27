<?php
/**
 * Controller for single posts
 */
$context = Timber::context();
$post = Timber::get_post();
$context['post'] = $post;

// We use a specific template for these hard-coded posts
Timber::render( 'single-full-width.twig', $context );