<?php

//**This is the function that allows people to edit the front page**

function fwbsite_customize_register($wp_customize) {

    // === Panel: Church Information ===
    $wp_customize->add_panel('church_info_panel', array(
        'title'       => __('Edit Home Page', 'fwbsite'),
        'description' => __('Customize your church details, services, and pastor information.', 'fwbsite'),
        'priority'    => 20,
    ));

    /*
    |--------------------------------------------------------------------------
    | Section: Church Description
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('church_description_section', array(
        'title'    => __('Church Description', 'fwbsite'),
        'panel'    => 'church_info_panel',
        'priority' => 10,
    ));

    // Church Image
    $wp_customize->add_setting('church_image', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'church_image',
        array(
            'label'    => __('Upload Church Image', 'fwbsite'),
            'section'  => 'church_description_section',
            'settings' => 'church_image',
        )
    ));

    // Church Name
    $wp_customize->add_setting('church_name', array(
        'default' => 'My Church',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('church_name', array(
        'label'   => __('Church Name', 'fwbsite'),
        'section' => 'church_description_section',
        'type'    => 'text',
    ));

    // Church Description
    // $wp_customize->add_setting('church_description', array(
    //     'default' => 'Welcome to our church!',
    //     'sanitize_callback' => 'sanitize_textarea_field',
    // ));
    // $wp_customize->add_control('church_description', array(
    //     'label'   => __('Church Description', 'fwbsite'),
    //     'section' => 'church_description_section',
    //     'type'    => 'textarea',
    // ));

    // //Church Welcome message.
    //         $wp_customize->add_setting('welcome_message', array(
    //         'default'           => 'Worship with us!',
    //         'sanitize_callback' => 'sanitize_textarea_field',
    //     ));

    //     $wp_customize->add_control('welcome_message', array(
    //         'label'   => __('Welcome Message', 'fwbsite'),
    //         'section' => 'church_description_section',
    //         'type'    => 'textarea',
    //     ));


    //     //Church welcome image

    //     $wp_customize->add_setting('welcome_image', array(
    //         'sanitize_callback' => 'esc_url_raw',
    //     ));

    //     $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'welcome_image', array(
    //         'label'    => __('Welcome Image', 'fwbsite'),
    //         'section'  => 'church_description_section',
    //         'settings' => 'welcome_image',
    //     )));

        //Church adress
            $wp_customize->add_setting('church_address', array(
            'default'           => '123 Main Street, Hometown, USA',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('church_address', array(
            'label'   => __('Church Address', 'fwbsite'),
            'section' => 'church_description_section',
            'type'    => 'text',
        ));

    //Church location information
    $wp_customize->add_setting('church_location', array(
        'default' => ' 3606 West End Avanue, Nashville TN',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('church_location', array(
        'label'   => __('Church Location', 'fwbsite'),
        'section' => 'church_description_section',
        'type'    => 'textarea',
    ));

 //Church Email

        $wp_customize->add_setting('church_email', array(
            'default'           => 'info@yourchurch.org',
            'sanitize_callback' => 'sanitize_email',
        ));

        $wp_customize->add_control('church_email', array(
            'label'   => __('Church Email', 'fwbsite'),
            'section' => 'church_description_section',
            'type'    => 'email',
        ));


        //Church Phone

        $wp_customize->add_setting('church_phone', array(
            'default'           => '(555) 123-4567',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('church_phone', array(
            'label'   => __('Church Phone', 'fwbsite'),
            'section' => 'church_description_section',
            'type'    => 'text',
        ));

    /*
    |--------------------------------------------------------------------------
    | Section: Service Information
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('church_services_section', array(
        'title'    => __('Service Information', 'fwbsite'),
        'panel'    => 'church_info_panel',
        'priority' => 20,
    ));

     //Church Welcome message.
            $wp_customize->add_setting('service_message', array(
            'default'           => 'Worship with us!',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));

        $wp_customize->add_control('service_message', array(
            'label'   => __('Worship Description', 'fwbsite'),
            'section' => 'church_services_section',
            'type'    => 'textarea',
        ));


        //Church welcome image

        $wp_customize->add_setting('service_image', array(
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'service_image', array(
            'label'    => __('Worship Image', 'fwbsite'),
            'section'  => 'church_services_section',
            'settings' => 'service_image',
        )));

    // Sunday School Time
    $wp_customize->add_setting('sunday_school_time', array(
        'default' => '9:30 AM',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sunday_school_time', array(
        'label'   => __('Sunday School Time', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'text',
    ));

    // Sunday School Description
    $wp_customize->add_setting('sunday_school_description', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('sunday_school_description', array(
        'label'   => __('Sunday School Description', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'textarea',
    ));

    // Sunday Service Time
    $wp_customize->add_setting('sunday_service_time', array(
        'default' => '11:00 AM',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sunday_service_time', array(
        'label'   => __('Sunday Service Time', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'text',
    ));

    // Sunday Service Description
    $wp_customize->add_setting('sunday_service_description', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('sunday_service_description', array(
        'label'   => __('Sunday Service Description', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'textarea',
    ));

    // Toggle Sunday Night Service
    $wp_customize->add_setting('enable_sunday_night', array(
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    $wp_customize->add_control('enable_sunday_night', array(
        'label'   => __('Add Sunday Night Service', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'checkbox',
    ));

    // Sunday Night Service Time
    $wp_customize->add_setting('sunday_night_time', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sunday_night_time', array(
        'label'   => __('Sunday Night Service Time', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'text',
        // 'active_callback' => function() {
        //     return get_theme_mod('enable_sunday_night', false);
        // },
    ));

    // Sunday Night Description
    $wp_customize->add_setting('sunday_night_description', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('sunday_night_description', array(
        'label'   => __('Sunday Night Description', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'textarea',
        // 'active_callback' => function() {
        //     return get_theme_mod('enable_sunday_night', false);
        // },
    ));

    // Toggle Wednesday Night Service
    $wp_customize->add_setting('enable_wednesday_night', array(
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    $wp_customize->add_control('enable_wednesday_night', array(
        'label'   => __('Enable Wednesday Night Service?', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'checkbox',
    ));

    // Wednesday Night Service Time
    $wp_customize->add_setting('wednesday_night_time', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('wednesday_night_time', array(
        'label'   => __('Wednesday Night Service Time', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'text',
        // 'active_callback' => function() {
        //     return get_theme_mod('enable_wednesday_night', false);
        // },
    ));
    $wp_customize->add_setting('wednesday_night_description', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('wednesday_night_description', array(
        'label'   => __('Wednesday Night Description', 'fwbsite'),
        'section' => 'church_services_section',
        'type'    => 'textarea',
        // 'active_callback' => function() {
        //     return get_theme_mod('enable_sunday_night', false);
        // },
    ));


    /*
    |--------------------------------------------------------------------------
    | New Fields: Mission Statement & Subtext
    |--------------------------------------------------------------------------
    */

    // --- Mission Statement (New Field) ---
    $wp_customize->add_setting('homepage_mission_statement', array(
        'default'           => 'To serve Chirst, His church, and His world through Biblical thought and life.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('homepage_mission_statement', array(
        'label'    => __('Mission Statement/Bible Verse', 'fwbsite'),
        'section'  => 'church_description_section', // Placing it in your existing section
        'type'     => 'textarea',
        'priority' => 15, // A low priority to place it near the top of the section
    ));

    // --- Subtext at the Bottom (New Field) ---
    $wp_customize->add_setting('mission_subtext', array(
        'default'           => 'Mission statment or Bible verse reference',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mission_subtext', array(
        'label'    => __('Subtext at Bottom of Page (Call to Action)', 'fwbsite'),
        'section'  => 'church_description_section', // Placing it in your existing section
        'type'     => 'text',
        'priority' => 100, // A high priority to place it near the bottom of the section
    ));

     /*
    |--------------------------------------------------------------------------
    | Section: Ministry Section
    |--------------------------------------------------------------------------
    */

    // Add Section
    // $wp_customize->add_section('church_ministries_section', array(
    //     'title'    => __('Church Ministries', 'fwbsite'),
    //     'panel'    => 'church_info_panel',
    //     'priority' => 40,
    // ));

    // // -------- Ministry 1 --------
    // $wp_customize->add_setting('ministry1_name', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    // ));
    // $wp_customize->add_control('ministry1_name', array(
    //     'label'   => __('Ministry 1 Name', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'text',
    // ));

    // $wp_customize->add_setting('ministry1_description', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_textarea_field',
    // ));
    // $wp_customize->add_control('ministry1_description', array(
    //     'label'   => __('Ministry 1 Description', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'textarea',
    // ));

    // // -------- Ministry 2 Toggle --------
    // $wp_customize->add_setting('enable_ministry2', array(
    //     'default' => false,
    //     'sanitize_callback' => 'rest_sanitize_boolean',
    // ));
    // $wp_customize->add_control('enable_ministry2', array(
    //     'label'   => __('Enable Ministry 2', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'checkbox',
    // ));

    // // Ministry 2 Name
    // $wp_customize->add_setting('ministry2_name', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    // ));
    // $wp_customize->add_control('ministry2_name', array(
    //     'label'   => __('Ministry 2 Name', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'text',
    // ));

    // // Ministry 2 Description
    // $wp_customize->add_setting('ministry2_description', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_textarea_field',
    // ));
    // $wp_customize->add_control('ministry2_description', array(
    //     'label'   => __('Ministry 2 Description', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'textarea',
    // ));

    // // -------- Ministry 3 Toggle --------
    // $wp_customize->add_setting('enable_ministry3', array(
    //     'default' => false,
    //     'sanitize_callback' => 'rest_sanitize_boolean',
    // ));
    // $wp_customize->add_control('enable_ministry3', array(
    //     'label'   => __('Enable Ministry 3', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'checkbox',
    // ));

    // // Ministry 3 Name
    // $wp_customize->add_setting('ministry3_name', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    // ));
    // $wp_customize->add_control('ministry3_name', array(
    //     'label'   => __('Ministry 3 Name', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'text',
    // ));

    // // Ministry 3 Description
    // $wp_customize->add_setting('ministry3_description', array(
    //     'default' => '',
    //     'sanitize_callback' => 'sanitize_textarea_field',
    // ));
    // $wp_customize->add_control('ministry3_description', array(
    //     'label'   => __('Ministry 3 Description', 'fwbsite'),
    //     'section' => 'church_ministries_section',
    //     'type'    => 'textarea',
    // ));

    /*
    |--------------------------------------------------------------------------
    | Section: Pastor Information
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('pastor_section', array(
        'title'    => __('Pastor Information', 'fwbsite'),
        'panel'    => 'church_info_panel',
        'priority' => 30,
    ));

    // Pastor Image
    $wp_customize->add_setting('pastor_image', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'pastor_image',
        array(
            'label'    => __('Upload Pastor Image', 'fwbsite'),
            'section'  => 'pastor_section',
            'settings' => 'pastor_image',
        )
    ));

    // Pastor Bio
    $wp_customize->add_setting('pastor_bio', array(
        'default' => 'Our pastor is dedicated to serving the congregation.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('pastor_bio', array(
        'label'   => __('Pastor Bio', 'fwbsite'),
        'section' => 'pastor_section',
        'type'    => 'textarea',
    ));

    // Pastor Name
    $wp_customize->add_setting('pastor_name', array(
    'default'           => 'Pastor John Doe',
    'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('pastor_name', array(
    'label'   => __('Pastor Name', 'fwbsite'),
    'section' => 'pastor_section',
    'type'    => 'text',
    ));

    // === Social Media Section ===
    $wp_customize->add_section( 'church_social_section', array(
        'title'    => __( 'Social Media', 'yourtheme' ),
        'panel'    => 'church_info_panel',
        'priority' => 20,
    ) );

    // Platforms to loop through
    $social_platforms = array(
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
    );

    foreach ( $social_platforms as $slug => $label ) {

        // === Toggle Enable/Disable ===
        $wp_customize->add_setting( "church_{$slug}_enabled", array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );

        $wp_customize->add_control( "church_{$slug}_enabled", array(
            'label'    => sprintf( __( 'Enable %s', 'yourtheme' ), $label ),
            'section'  => 'church_social_section',
            'type'     => 'checkbox',
        ) );

        // === Social Link ===
        $wp_customize->add_setting( "church_{$slug}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( "church_{$slug}", array(
            'label'       => sprintf( __( '%s URL', 'yourtheme' ), $label ),
            'section'     => 'church_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => "https://www.$slug.com/yourchurch",
            ),
        ) );
    }

}
add_action('customize_register', 'fwbsite_customize_register');