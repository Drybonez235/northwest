<?php
// Gather the global context (menus, site title, etc.)
$context = Timber::context();

// Gather the posts for this specific page/view
$context['posts'] = Timber::get_posts();

// Tell Timber to look for 'index.twig' and pass it the data
Timber::render( 'index.twig', $context );