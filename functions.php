<?php
/**
 * northwest Functions
 */

// 1. BOOT TIMBER (The New Engine)
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once( __DIR__ . '/vendor/autoload.php' );
}
Timber\Timber::init();
Timber::$dirname = ['templates','views'];

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



// function church_sermon_hero_shortcode() {
//     // This tells Timber to render the partial and return it as a string
//     return Timber::compile('partials/sermon-hero.twig', Timber::context());
// }
// add_shortcode('sermon_hero', 'church_sermon_hero_shortcode');

/**
 * Shortcode to render the Services Section
 * Usage: [services_section]
 */
// add_shortcode('services_section', function() {

//     // We return the render as a string for the shortcode
//     return Timber::compile('partials/services-section.twig', Timber::context());
// });


// function church_register_patterns() {
//     register_block_pattern(
//         'church/sermon-hero',
//         array(
//             'title'       => __( 'Sermon Hero Block', 'northwest' ),
//             'categories'  => array( 'header' ),
//             'content'     => '[sermon_hero]',
//         ),
//          'my-theme/services-section',
//         array(
//             'title'       => __('Services with Image Bleed', 'textdomain'),
//             'categories'  => array('church-layouts'),
//             // This 'content' is the raw block markup that Gutenberg uses
//             'content'     => '[services_section]',
//         )
//     );
    
// }
// add_action('init', 'church_register_patterns');


add_filter('timber/context', function( $context ) {
    // Grab the current URL path and ensure it's not null
    $current_path = $_SERVER['REQUEST_URI'] ?? '';

    // Check if the URL contains '/es/' or '/es' at the end
    // We include the slashes to prevent matching words like "business" or "forest"
    if (str_contains($current_path, '/es/') || str_ends_with($current_path, '/es')) {
        $context['menu'] = Timber::get_menu('header_es'); 
        $context['lang'] = 'es';
    } else {
        // Default to English if '/es/' is not found
        $context['menu'] = Timber::get_menu('header_en'); 
        $context['lang'] = 'en';
    }

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

    return $context;
});

/**
 * Register Custom Post Type: Ministry
 */
function nw_register_ministry_post_type() {
    $labels = [
        'name'                  => 'Ministries',
        'singular_name'         => 'Ministry',
        'menu_name'             => 'Ministries',
        'add_new'               => 'Add New Ministry',
        'add_new_item'          => 'Add New Ministry',
        'edit_item'             => 'Edit Ministry',
        'new_item'              => 'New Ministry',
        'view_item'             => 'View Ministry',
        'search_items'          => 'Search Ministries',
        'not_found'             => 'No Ministries found',
        'all_items'             => 'All Ministries',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
       'hierarchical'       => true, // Allows parent-child relationships
        'supports'           => ['title', 'thumbnail', 'revisions', 'page-attributes'], // 'page-attributes' adds the Parent dropdown
        'rewrite'            => [
            'slug'       => 'ministries',
            'with_front' => false,
            'hierarchical' => true // Tells WordPress to include the parent slug in the URL
        ], 
        'menu_icon'          => 'dashicons-groups', // Church/Groups icon
        'show_in_rest'       => true,
    ];

    register_post_type('ministry', $args);
}
add_action('init', 'nw_register_ministry_post_type');

/**
 * Add Meta Boxes for Ministry Details
 */
function nw_add_ministry_meta_boxes() {
    add_meta_box(
        'ministry_details',
        'Ministry Information',
        'nw_render_ministry_meta_box',
        'ministry',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nw_add_ministry_meta_boxes');

/**
 * Render Meta Box Fields
 */
function nw_render_ministry_meta_box($post) {
    wp_nonce_field('nw_ministry_meta_nonce', 'nw_ministry_nonce');

    $values = [
        'caption'          => get_post_meta($post->ID, '_ministry_caption', true),
        'leader_name'      => get_post_meta($post->ID, '_ministry_leader_name', true),
        'leader_photo'     => get_post_meta($post->ID, '_ministry_leader_photo', true),
        'hero_image'       => get_post_meta($post->ID, '_ministry_hero_image', true),
        'hero_desc'        => get_post_meta($post->ID, '_ministry_hero_desc', true),
        'description'      => get_post_meta($post->ID, '_ministry_description', true),
        'involved_head'    => get_post_meta($post->ID, '_ministry_involved_head', true),
        'involved_text'    => get_post_meta($post->ID, '_ministry_involved_text', true),
        // New Fields
        'title_name'       => get_post_meta($post->ID, '_ministry_title_name', true),
        'cta_text'         => get_post_meta($post->ID, '_ministry_cta_text', true),
        'cta_url'          => get_post_meta($post->ID, '_ministry_cta_url', true),
        'en_url'           => get_post_meta($post->ID, '_ministry_en_url', true),
        'es_url'           => get_post_meta($post->ID, '_ministry_es_url', true),
        'en_es'            => get_post_meta($post->ID, '_ministry_en_es', true),
    ];

    echo '<style>.nw-admin-field { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; } .nw-admin-field label { display: block; font-weight: bold; margin-bottom: 5px; } .nw-admin-field input, .nw-admin-field textarea, .nw-admin-field select { width: 100%; }</style>';
    ?>

    <div class="nw-admin-field">
        <label>Language Setting</label>
        <select name="ministry_en_es">
            <option value="EN" <?php selected($values['en_es'], 'EN'); ?>>English</option>
            <option value="ES" <?php selected($values['en_es'], 'ES'); ?>>Spanish</option>
        </select>
    </div>

    <div class="nw-admin-field">
        <label>Title Name (Specific display title)</label>
        <input type="text" name="ministry_title_name" value="<?php echo esc_attr($values['title_name']); ?>">
    </div>

    <div class="nw-admin-field"><label>Ministry Caption</label><input type="text" name="ministry_caption" value="<?php echo esc_attr($values['caption']); ?>"></div>
    <div class="nw-admin-field"><label>Ministry Leader Name</label><input type="text" name="ministry_leader_name" value="<?php echo esc_attr($values['leader_name']); ?>"></div>
    <div class="nw-admin-field"><label>Ministry Leader Title</label><input type="text" name="ministry_title_name" value="<?php echo esc_attr($values['title_name']); ?>"></div>
    <div class="nw-admin-field"><label>Leader Photo URL</label><input type="url" name="ministry_leader_photo" value="<?php echo esc_url($values['leader_photo']); ?>"></div>
    
    <div class="nw-admin-field" style="background: #f9f9f9; padding: 10px;">
        <label><strong>Call to Action Button</strong></label>
        <label style="font-weight:normal;">Button Text</label>
        <input type="text" name="ministry_cta_text" value="<?php echo esc_attr($values['cta_text']); ?>" placeholder="e.g. Register Now">
        <label style="font-weight:normal; margin-top:10px;">Button URL</label>
        <input type="url" name="ministry_cta_url" value="<?php echo esc_url($values['cta_url']); ?>">
    </div>

    <div class="nw-admin-field">
        <label>Language Switcher Links</label>
        <label style="font-weight:normal;">English Version URL</label>
        <input type="url" name="ministry_en_url" value="<?php echo esc_url($values['en_url']); ?>">
        <label style="font-weight:normal; margin-top:10px;">Spanish Version URL</label>
        <input type="url" name="ministry_es_url" value="<?php echo esc_url($values['es_url']); ?>">
    </div>

    <div class="nw-admin-field"><label>Hero Image URL</label><input type="url" name="ministry_hero_image" value="<?php echo esc_url($values['hero_image']); ?>"></div>
    <div class="nw-admin-field"><label>Hero Image Description</label><textarea name="ministry_hero_desc" rows="2"><?php echo esc_textarea($values['hero_desc']); ?></textarea></div>
    <div class="nw-admin-field"><label>Full Ministry Description</label><textarea name="ministry_description" rows="5"><?php echo esc_textarea($values['description']); ?></textarea></div>
    <div class="nw-admin-field"><label>How to be involved (Heading)</label><input type="text" name="ministry_involved_head" value="<?php echo esc_attr($values['involved_head']); ?>"></div>
    <div class="nw-admin-field"><label>How to be involved (Text)</label><textarea name="ministry_involved_text" rows="4"><?php echo esc_textarea($values['involved_text']); ?></textarea></div>
    <?php
}

/**
 * Save Meta Box Data
 */
function nw_save_ministry_meta($post_id) {
    if (!isset($_POST['nw_ministry_nonce']) || !wp_verify_nonce($_POST['nw_ministry_nonce'], 'nw_ministry_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_ministry_caption'       => 'ministry_caption',
        '_ministry_leader_name'   => 'ministry_leader_name',
        '_ministry_leader_photo'  => 'ministry_leader_photo',
        '_ministry_hero_image'    => 'ministry_hero_image',
        '_ministry_hero_desc'     => 'ministry_hero_desc',
        '_ministry_description'   => 'ministry_description',
        '_ministry_involved_head' => 'ministry_involved_head',
        '_ministry_involved_text' => 'ministry_involved_text',
        // Save New Fields
        '_ministry_title_name'    => 'ministry_title_name',
        '_ministry_cta_text'      => 'ministry_cta_text',
        '_ministry_cta_url'       => 'ministry_cta_url',
        '_ministry_en_url'        => 'ministry_en_url',
        '_ministry_es_url'        => 'ministry_es_url',
        '_ministry_en_es'         => 'ministry_en_es',
    ];

    foreach ($fields as $key => $post_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$post_key]));
        }
    }
}
add_action('save_post', 'nw_save_ministry_meta');

register_nav_menus([
    'header_en' => 'English Header Slot',
    'header_es' => 'Spanish Header Slot',
]);