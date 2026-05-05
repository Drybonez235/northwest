<?php
$context = Timber::context();
$timber_post = Timber::get_post();
$context['post'] = $timber_post;

Timber::render( 'page.twig', $context );