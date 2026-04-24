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

define( 'MYTHEME_SUB_FUNCTIONS_DIR', get_template_directory() . '/sub-functions/' );
require_once MYTHEME_SUB_FUNCTIONS_DIR . 'home-page.php';

add_action( 'wp_enqueue_scripts', function() {
    // Make sure this path matches where your Tailwind CLI outputs the file
    wp_enqueue_style( 'theme-styles', get_template_directory_uri() . '/dist/style.css', [], '1.0' );
});

// 3. THEME SETUP
function mychurch_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'mychurch'),
    ]);
}
add_action('after_setup_theme', 'mychurch_setup');



function church_sermon_hero_shortcode() {
    // This tells Timber to render the partial and return it as a string
    return Timber::compile('partials/sermon-hero.twig', Timber::context());
}
add_shortcode('sermon_hero', 'church_sermon_hero_shortcode');

/**
 * Shortcode to render the Services Section
 * Usage: [services_section]
 */
add_shortcode('services_section', function() {

    // We return the render as a string for the shortcode
    return Timber::compile('partials/services-section.twig', Timber::context());
});


function church_register_patterns() {
    register_block_pattern(
        'church/sermon-hero',
        array(
            'title'       => __( 'Sermon Hero Block', 'northwest' ),
            'categories'  => array( 'header' ),
            'content'     => '[sermon_hero]',
        ),
         'my-theme/services-section',
        array(
            'title'       => __('Services with Image Bleed', 'textdomain'),
            'categories'  => array('church-layouts'),
            // This 'content' is the raw block markup that Gutenberg uses
            'content'     => '[services_section]',
        )
    );
    
}
add_action('init', 'church_register_patterns');


add_filter('timber/context', function( $context ) {
    $context['menu'] = Timber::get_menu('primary');
    $context['site'] = new Timber\Site();
    $context['custom_logo_url'] = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');

     // Create the 'theme' object to hold all your sub-function data
    $context['theme'] = [
        // Church Description Section
        'name'               => get_theme_mod('church_name', 'My Church'),
        'image'              => get_theme_mod('church_image'),
        'address'            => get_theme_mod('church_address'),
        'location'           => get_theme_mod('church_location'),
        'email'              => get_theme_mod('church_email'),
        'phone'              => get_theme_mod('church_phone'),
        'mission_statement'  => get_theme_mod('homepage_mission_statement'),
        'mission_subtext'    => get_theme_mod('mission_subtext'),


        // Service Information Section
        'service' => [
            'message'             => get_theme_mod('service_message'),
            'image'               => get_theme_mod('service_image'),
            'sunday_school'       => get_theme_mod('sunday_school_time'),
            'sunday_school_desc'  => get_theme_mod('sunday_school_description'),
            'sunday_morning'      => get_theme_mod('sunday_service_time'),
            'sunday_morning_desc' => get_theme_mod('sunday_service_description'),
            'night_enabled'       => get_theme_mod('enable_sunday_night'),
            'night_time'          => get_theme_mod('sunday_night_time'),
            'wednesday_enabled'   => get_theme_mod('enable_wednesday_night'),
            'wednesday_time'      => get_theme_mod('wednesday_night_time'),
        ],

        // Pastor Section
        'pastor' => [
            'name'  => get_theme_mod('pastor_name'),
            'bio'   => get_theme_mod('pastor_bio'),
            'image' => get_theme_mod('pastor_image'),
        ],

         // Social Media
        'social' => [
            'facebook'  => ['url' => get_theme_mod('church_facebook'),  'enabled' => get_theme_mod('church_facebook_enabled')],
            'instagram' => ['url' => get_theme_mod('church_instagram'), 'enabled' => get_theme_mod('church_instagram_enabled')],
            'youtube'   => ['url' => get_theme_mod('church_youtube'),   'enabled' => get_theme_mod('church_youtube_enabled')],
        ],

    ];
});
