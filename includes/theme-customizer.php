<?php
/**
 * Cusotmizer Theme Actions
 *
 * These options appear in the WordPress customizer
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * 
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
* This hooks into 'customize_register' (available as of WP 3.4) and allows
* you to add new sections and controls to the Theme Customize screen.
* 
* @see add_action('customize_register',$func)
* @param \WP_Customize_Manager $wp_customize
* @since Salt 1.0
*/
function salt_customize_register( $wp_customize ) {
	
	$image_folder_url =  get_template_directory_uri() . '/core/assets/images/';

	/**
	 * Branding
	 *
	 * Upload a logo and a favicon to make the site your own
	 * 
	 * @since Salt 1.0
	 */
    $wp_customize->add_setting('salt_custom_logo', array(
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'option',
		'transport' 		=> 'postMessage',
		'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'salt_custom_logo', array(
        'label'    		=> __('Upload Logo', 'salt'),
        'section'  		=> 'title_tagline',
        'settings' 		=> 'salt_custom_logo',
        'priority'		=> 15,
    )));
   
    $wp_customize->get_setting( 'salt_custom_logo' )->transport = 'postMessage'; 
    
    $wp_customize->add_setting('salt_custom_favicon', array(
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'option',
		'transport' 		=> 'postMessage',
		'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'salt_custom_favicon', array(
        'label'    		=> __('Upload Favicon', 'salt'),
        'section'  		=> 'title_tagline',
        'settings' 		=> 'salt_custom_favicon',
        'priority'		=> 20,
    )));

	/**
	 * General
	 *
	 * Change the site to full width or box it in to a set width 
	 * Edit the colours of the site with some presets
	 * 
	 * @since Salt 1.0
	 */
    $wp_customize->add_section('general', array(
        'title'    		=> __('General', 'salt'),
        'priority' 		=> 30,
        'description' 	=> __('Customize the general look & feel of your website', 'salt'),
    ));

	$wp_customize->add_setting('salt_layout_type', array(
    	'default'       	=> 'wide',
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
		'sanitize_callback' => 'sanitize_html_class'
    ));
	
    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_layout_type', array(
        'label'    		=> __('Layout', 'salt'),
        'section'  		=> 'general',
        'settings' 		=> 'salt_layout_type',
        'type'          => 'select',
        'choices'       => array (
	        'wide'   => __('Wide', 'salt'),
        	'boxed'  => __('Boxed', 'salt'),
		)
    )));

	/**
	 * Navigation
	 *
	 * Turn on / off the breadcrumb trail and change some settings 
	 * 
	 * @since Salt 1.0
	 */
    $wp_customize->add_section('breadcrumb', array(
        'title'    		=> __('Breadcrumb', 'salt'),
        'priority' 		=> 100,
        'description' 	=> __('Customize the breadcrumb navigation trail', 'salt'),
    ));
    
    // Turn on / off breadcrumb trail
    $wp_customize->add_setting('salt_display_breadcrumb', array(
    	'default'       	=> true,
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_display_breadcrumb', array(
        'label'    		=> __('Display breadcrumb', 'salt'),
        'section'  		=> 'breadcrumb',
        'settings' 		=> 'salt_display_breadcrumb',
        'type'          => 'checkbox',
    )));

    // Turn on / off home in breadcrumb trail
    $wp_customize->add_setting('salt_display_home', array(
    	'default'       	=> true,
        'capability'    	=> 'edit_theme_options',
        'type'         	 	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_display_home', array(
        'label'    		=> __('Display home in breadcrumb', 'salt'),
        'section'  		=> 'breadcrumb',
        'settings' 		=> 'salt_display_home',
        'type'          => 'checkbox',
    )));

    // Breadcrumb Seperator
    $wp_customize->add_setting('salt_breadcrumb_separator', array(
    	'default'       	=> '',
        'capability'    	=> 'edit_theme_options',
        'type'         	 	=> 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_breadcrumb_separator', array(
        'label'    		=> __('Breadcrumb Separator', 'salt'),
        'section'  		=> 'breadcrumb',
        'settings' 		=> 'salt_breadcrumb_separator',
        'type'          => 'text',
    )));
        
    /**
	 * Footer
	 *
	 * Footer turn on / off the various credits and change the footer widget layout 
	 * 
	 * @since Salt 1.0
	 */
    $wp_customize->add_section('footer', array(
        'title'    		=> __('Footer', 'salt'),
        'priority' 		=> 110,
        'description' 	=> __('Customize the website footer.', 'salt'),
    ));

	$wp_customize->add_setting('salt_footer_credit', array(
	    'default'           => true,
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'option',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_footer_credit', array(
        'label'    => __( 'Show your love to charity: themes and WordPress, and display their names proudly in your website footer', 'salt' ),
        'section'  => 'footer',
        'settings' => 'salt_footer_credit',
        'type'     => 'checkbox',
	)));

    // Footer Copyright Text
    $wp_customize->add_setting('salt_footer_text', array(
    	'default'       	=> 'ABC Ltd, All Rights Reserved.',
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_footer_text', array(
        'label'    		=> __('Copyright Text', 'salt'),
        'section'  		=> 'footer',
        'settings' 		=> 'salt_footer_text',
        'type'          => 'textarea',
    )));

	// Select a footer widget layout
    $wp_customize->add_setting('salt_footer_sidebars', array(
	    'default'           => '4',
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	$wp_customize->add_control( new WP_Customize_ImageSelect_Control( $wp_customize, 'salt_footer_sidebars', array(
        'label'    => __( 'Footer Widgets Areas', 'salt' ),
        'section'  => 'footer',
        'settings' => 'salt_footer_sidebars',
        'choices'  => array (
			'0' => $image_folder_url . 'footer-off.png',
			'1' => $image_folder_url . 'footer-widgets-1.png',
			'2' => $image_folder_url . 'footer-widgets-2.png',
			'3' => $image_folder_url . 'footer-widgets-3.png',
			'4' => $image_folder_url . 'footer-widgets-4.png'
		)
	)));			
    
	/**
	 * Social
	 *
	 * Change the style of the social icons 
	 * 
	 * @since Salt 1.0
	 */
    $wp_customize->add_section('social', array(
        'title'    		=> __('Social', 'salt'),
        'priority' 		=> 130,
        'description' 	=> __('Connect to your social pages from your website', 'salt'),
    ));
    
    // Social Icons Shape
    $wp_customize->add_setting('salt_social_shape', array(
    	'default'       => '',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod',
        'transport' 	=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_shape', array(
        'label'    		=> __('Social Icons Shape', 'salt'),
        'section'  		=> 'social',
        'settings' 		=> 'salt_social_shape',
        'type'          => 'select',
        'choices'       => array (
        	'circle' => __('Circular', 'salt'),
			'square' => __('Square', 'salt'),
			'' 		 => __('No Background', 'salt')
		)
    )));
    
	//$wp_customize->get_setting( 'salt_social_type' )->transport = 'postMessage';
    
    // Social Icons Type
    $wp_customize->add_setting('salt_social_type', array(
    	'default'       => 'black',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod',
        'transport' 	=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_type', array(
        'label'    		=> __('Social Icons Type', 'salt'),
        'section'  		=> 'social',
        'settings' 		=> 'salt_social_type',
        'type'          => 'select',
        'choices'       => array (
        	'black' => __('Black', 'salt'),
			'white' => __('White', 'salt'),
			'color' => __('Color', 'salt')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_type' )->transport = 'postMessage'; 
}
add_action( 'customize_register' , 'salt_customize_register' );