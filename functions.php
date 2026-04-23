<?php
// Load Composer dependencies
if ( file_exists( $composer_autoload = __DIR__ . '/vendor/autoload.php' ) ) {
    require_once $composer_autoload;
}

// Initialize Timber
$timber = new Timber\Timber();

function church_register_sermon_meta() {
    register_post_meta('page', 'sermon_title', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ]);
    register_post_meta('page', 'preacher_name', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ]);
}
add_action('init', 'church_register_sermon_meta');