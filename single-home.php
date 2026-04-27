<?php
use Timber\Timber;

$context = Timber::context();

// In Timber 2.x, Timber::get_post() is the preferred way
$post = Timber::get_post();

// Check if the post exists before passing it
if ( ! $post ) {
    // This helps debug if WordPress even thinks we are on a valid single page
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    die();
}

$context['post'] = $post;

Timber::render( 'single-home.twig', $context );