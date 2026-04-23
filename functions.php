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

function church_sermon_hero_shortcode() {
    // This tells Timber to render the partial and return it as a string
    return Timber::compile('partials/sermon-hero.twig', Timber::context());
}
add_shortcode('sermon_hero', 'church_sermon_hero_shortcode');

function church_register_patterns() {
    register_block_pattern(
        'church/sermon-hero',
        array(
            'title'       => __( 'Sermon Hero Block', 'northwest' ),
            'categories'  => array( 'header' ),
            'content'     => '[sermon_hero]',
        )
    );
}
add_action('init', 'church_register_patterns');

/**
 * Shortcode to render the Services Section
 * Usage: [services_section]
 */
add_shortcode('services_section', function() {

    // We return the render as a string for the shortcode
    return Timber::compile('partials/services-section.twig', Timber::context());
});

/**
 * Register the Services Section as a Block Pattern
 */
add_action('init', function() {

    register_block_pattern(
        'my-theme/services-section',
        array(
            'title'       => __('Services with Image Bleed', 'textdomain'),
            'categories'  => array('church-layouts'),
            // This 'content' is the raw block markup that Gutenberg uses
            'content'     => '[services_section]',
        )
    );
});