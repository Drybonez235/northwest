<?php
/**
 * northwest Functions
 */

// 1. BOOT TIMBER (The New Engine)
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once( __DIR__ . '/vendor/autoload.php' );
}
Timber\Timber::init();
Timber::$dirname = ['views'];

add_action( 'wp_enqueue_scripts', function() {
    // Make sure this path matches where your Tailwind CLI outputs the file
    wp_enqueue_style( 'theme-styles', get_template_directory_uri() . '/dist/style.css', [], '1.0' );
});

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

function church_register_patterns() {
    register_block_pattern(
        'church/sermon-hero',
        array(
            'title'       => __( 'Sermon Hero', 'church-one-body' ),
            'description' => _x( 'A hero section that pulls from sermon meta.', 'Block pattern description', 'church-one-body' ),
            'content' => '<h1></h1>',
            'categories'  => array( 'header' ),
        )
    );
}
add_action( 'init', 'church_register_patterns' );