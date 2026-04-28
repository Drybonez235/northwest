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

add_filter('timber/context', function( $context ) {
   $current_path = $_SERVER['REQUEST_URI'] ?? '';

    // 1. Language Detection Logic
    // We determine the language string once to use for both Menus and Meta Queries
    if (str_contains($current_path, '/es/') || str_ends_with($current_path, '/es')) {
        $lang_code = 'es';
        $menu_id = 'header_es';
        $meta_value = 'ES'; // Matches the 'Language Setting' meta box value
    } else {
        $lang_code = 'en';
        $menu_id = 'header_en';
        $meta_value = 'EN';
    }

    // 2. Set Context Variables
    $context['lang'] = $lang_code;
    $context['menu'] = Timber::get_menu($menu_id);

    // 3. Pull Filtered Ministries
    // This pulls only ministries assigned to the detected language
    $context['nav_ministries'] = Timber::get_posts([
        'post_type' => 'ministry',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key'     => '_ministry_en_es', // The key from your nw_save_ministry_meta function
                'value'   => $meta_value,
                'compare' => '=',
            ],
        ],
    ]);

    // Fetch the 5 latest regular blog posts
    $context['nav_posts'] = Timber::get_posts([
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $lang_code,
            ],
        ],
    ]);

    // 3. Pull Filtered Events
// This pulls only events assigned to the detected language (English or Spanish)
$context['nav_events'] = Timber::get_posts([
    'post_type'      => 'nw_event',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'meta_query'     => [
        [
            'key'     => 'nw_event_bilingual', // Matches the key from nw_save_event_meta
            'value'   => $meta_value,          // Ensure this matches '1' for the toggle
            'compare' => '=',
        ],
    ],
]);


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
        'leader_title'      => get_post_meta($post->ID, '_ministry_leader_title', true),
        'leader_photo'     => get_post_meta($post->ID, '_ministry_leader_photo', true),
        'hero_image'       => get_post_meta($post->ID, '_ministry_hero_image', true),
        'hero_desc'        => get_post_meta($post->ID, '_ministry_hero_desc', true),
        'description'      => get_post_meta($post->ID, '_ministry_description', true),
        'involved_head'    => get_post_meta($post->ID, '_ministry_involved_head', true),
        'involved_text'    => get_post_meta($post->ID, '_ministry_involved_text', true),
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
    <div class="nw-admin-field"><label>Ministry Leader Title</label><input type="text" name="ministry_leader_title" value="<?php echo esc_attr($values['leader_title']); ?>"></div>
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
        '_ministry_leader_title' =>  'ministry_leader_title',
        '_ministry_leader_photo'  => 'ministry_leader_photo',
        '_ministry_hero_image'    => 'ministry_hero_image',
        '_ministry_hero_desc'     => 'ministry_hero_desc',
        '_ministry_description'   => 'ministry_description',
        '_ministry_involved_head' => 'ministry_involved_head',
        '_ministry_involved_text' => 'ministry_involved_text',
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

/**
 * Register 'Home Page' Custom Post Type
 */
function nw_register_homepage_cpt() {
    $args = [
        'labels' => ['name' => 'Home Page', 'singular_name' => 'Home Page'],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-admin-home',
        'hierarchical'       => true, // Allows parent-child relationships
        'supports'           => ['title', 'thumbnail', 'revisions', 'page-attributes'], // 'page-attributes' adds the Parent dropdown
        'rewrite'            => [
            'slug'       => 'home',
            'with_front' => false,
            'hierarchical' => true // Tells WordPress to include the parent slug in the URL
        ], 
    ];
    register_post_type('nw_homepage', $args);
}
add_action('init', 'nw_register_homepage_cpt');

/**
 * Register Meta Boxes for Northwest Community Church Home Page
 */
function nw_register_home_meta_boxes() {
    add_meta_box(
        'nw_home_fields',
        'Homepage Content Sections',
        'nw_home_meta_box_callback',
        'nw_homepage', // Target the new CPT
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nw_register_home_meta_boxes');

function nw_home_meta_box_callback($post) {
    $fields = [
        'Church_Name', 'Church_Hero_Image_URL', 'Church_Intro_Text', 
        'Church_Mission_Statement_Heading', 'Church_Mission_Statement_Text',
        'Church_Services_Header', 'Church_Services_Background_Image_URL',
        'Service_Sunday_School_Header', 'Service_Sunday_School_Time', 'Service_Sunday_School_Header_Text',
        'Service_Sunday_Worship_Header', 'Service_Sunday_Worship_Header_Time', 'Service_Sunday_Worship_Header_Text',
        'Service_Small_Group_Header', 'Service_Small_Group_Time', 'Service_Small_Group_Text',
        'Church_About_Us_Header', 'Church_About_Us_Background_Image_URL', 'Church_About_Us_Text', 
        'Church_About_Us_CTA_Text', 'Church_About_Us_CTA_URL',
        'Church_Ministries_Header',
        'Church_Ministry_Header_1', 'Church_Ministry_URL_1', 'Church_Ministry_Background_Image_URL_1',
        'Church_Ministry_Header_2', 'Church_Ministry_URL_2', 'Church_Ministry_Background_Image_URL_2',
        'Church_Ministry_Header_3', 'Church_Ministry_URL_3', 'Church_Ministry_Background_Image_URL_3'
    ];

    echo '<div style="display: grid; gap: 15px; padding: 10px;">';
    foreach ($fields as $field) {
        $value = get_post_meta($post->ID, '_' . $field, true);
        echo '<div>';
        echo '<label style="font-weight:bold; display:block; margin-bottom:5px;">' . str_replace('_', ' ', $field) . '</label>';
        if (strpos($field, '_Text') !== false || strpos($field, '_Statement') !== false) {
            echo '<textarea name="' . $field . '" style="width:100%" rows="3">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="text" name="' . $field . '" value="' . esc_attr($value) . '" style="width:100%" />';
        }
        echo '</div>';
    }
    echo '</div>';
}

function nw_save_home_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'Church_') === 0 || strpos($key, 'Service_') === 0) {
            update_post_meta($post_id, '_' . $key, sanitize_text_field($value));
        }
    }
}
add_action('save_post_nw_homepage', 'nw_save_home_meta');

// functions.php or inc/post-types.php

function nw_register_event_post_type() {
    $args = [
        'labels' => [
            'name' => 'Events',
            'singular_name' => 'Event',
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'], // page-attributes enables parent support
        'hierarchical' => true, // Necessary for parent/child relationships
        'rewrite' => ['slug' => 'events'],
    ];
    register_post_type('nw_event', $args);
}
add_action('init', 'nw_register_event_post_type');

// functions.php or inc/meta-boxes.php

function nw_add_event_meta_boxes() {
    add_meta_box(
        'nw_event_details',
        'Event Details & Language Settings',
        'nw_render_event_meta_box',
        'nw_event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nw_add_event_meta_boxes');

function nw_render_event_meta_box($post) {
    // Retrieve existing values
    $values = get_post_custom($post->ID);
    $is_bilingual = isset($values['nw_event_bilingual']) ? $values['nw_event_bilingual'] : '0';
    
    wp_nonce_field('nw_event_meta_nonce', 'nw_event_meta_nonce_field');
    ?>
    <div class="nw-meta-container">
        <style>
            .meta-row { margin-bottom: 15px; display: flex; flex-direction: column; }
            .meta-row label { font-weight: bold; margin-bottom: 5px; }
            .meta-row input[type="text"], .meta-row input[type="url"], .meta-row textarea { width: 100%; }
            .meta-group { border: 1px solid #ccd0d4; padding: 15px; margin-bottom: 20px; background: #fff; }
        </style>

        <div class="meta-group">
            <h3>Language Settings</h3>
            <div class="meta-row">
                <label>
                    <input type="checkbox" name="nw_event_bilingual" value="1" <?php checked($is_bilingual, '1'); ?>>
                    English & Spanish Support
                </label>
            </div>
            <div class="meta-row">
                <label>English Page URL</label>
                <input type="url" name="nw_event_english_url" value="<?php echo esc_url(get_post_meta($post->ID, 'nw_event_english_url', true)); ?>">
            </div>
            <div class="meta-row">
                <label>Spanish Page URL</label>
                <input type="url" name="nw_event_spanish_url" value="<?php echo esc_url(get_post_meta($post->ID, 'nw_event_spanish_url', true)); ?>">
            </div>
        </div>

        <div class="meta-group">
            <h3>Display Content</h3>
            <div class="meta-row">
                <label>Event Title</label>
                <input type="text" name="nw_event_title" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_title', true)); ?>">
            </div>
            <div class="meta-row">
                <label>Event Subtitle</label>
                <input type="text" name="nw_event_subtitle" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_subtitle', true)); ?>">
            </div>
            <div class="meta-row">
                <label>Background Image URL</label>
                <input type="text" name="nw_event_bg_url" value="<?php echo esc_url(get_post_meta($post->ID, 'nw_event_bg_url', true)); ?>">
            </div>
        </div>

        <div class="meta-group">
            <h3>Logistics</h3>
            <div class="meta-row">
                <label>Date</label>
                <input type="date" name="nw_event_date" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_date', true)); ?>">
            </div>
            <div class="meta-row">
                <label>Time</label>
                <input type="text" name="nw_event_time" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_time', true)); ?>" placeholder="e.g. 10:00 AM">
            </div>
            <div class="meta-row">
                <label>Location</label>
                <input type="text" name="nw_event_location" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_location', true)); ?>">
            </div>
        </div>

        <div class="meta-group">
            <h3>Description & Call to Action</h3>
            <div class="meta-row">
                <label>Description</label>
                <?php wp_editor(get_post_meta($post->ID, 'nw_event_description', true), 'nw_event_description', ['textarea_name' => 'nw_event_description', 'media_buttons' => false, 'textarea_rows' => 5]); ?>
            </div>
            <div class="meta-row">
                <label>CTA Text</label>
                <input type="text" name="nw_event_cta_text" value="<?php echo esc_attr(get_post_meta($post->ID, 'nw_event_cta_text', true)); ?>">
            </div>
            <div class="meta-row">
                <label>CTA URL</label>
                <input type="url" name="nw_event_cta_url" value="<?php echo esc_url(get_post_meta($post->ID, 'nw_event_cta_url', true)); ?>">
            </div>
        </div>
    </div>
    <?php
}

function nw_save_event_meta($post_id) {
    if (!isset($_POST['nw_event_meta_nonce_field']) || !wp_verify_nonce($_POST['nw_event_meta_nonce_field'], 'nw_event_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'nw_event_bilingual', 'nw_event_english_url', 'nw_event_spanish_url',
        'nw_event_title', 'nw_event_subtitle', 'nw_event_bg_url',
        'nw_event_date', 'nw_event_time', 'nw_event_location',
        'nw_event_description', 'nw_event_cta_text', 'nw_event_cta_url'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $_POST[$field]);
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
add_action('save_post', 'nw_save_event_meta');


register_nav_menus([
    'header_en' => 'English Header Slot',
    'header_es' => 'Spanish Header Slot',
]);