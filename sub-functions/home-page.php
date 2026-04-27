<?php

//**This is the function that allows people to edit the front page**

function fwbsite_customize_register($wp_customize) {

    // === Panel: Church Information ===
    $wp_customize->add_panel('church_info_panel', array(
        'title'       => __('Church Information', 'fwbsite'),
        'description' => __('Customize your church details.', 'fwbsite'),
        'priority'    => 20,
    ));

    /*
    |--------------------------------------------------------------------------
    | Section: Church Description
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('church_description_section', array(
        'title'    => __('Church Information', 'fwbsite'),
        'panel'    => 'church_info_panel',
        'priority' => 10,
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