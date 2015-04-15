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
 * @since Salt 1.0.0
 */

/**
* This hooks into 'customize_register' (available as of WP 3.4) and allows
* you to add new sections and controls to the Theme Customize screen.
* 
* @see add_action('customize_register',$func)
* @param \WP_Customize_Manager $wp_customize
* @since Salt 1.0.0
*/
function salt_customize_register( $wp_customize ) {
	
	$image_folder_url =  get_template_directory_uri() . '/includes/images/';

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

	// Select a footer widget layout
    $wp_customize->add_setting('salt_blog_layout', array(
	    'default'           => '4',
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_blog_layout', array(
        'label'    => __( 'Blog Layout', 'salt' ),
        'section'  => 'general',
        'description' => __('This option affects your category, tag, author, single and search pages.', 'salt'),
        'settings' => 'salt_blog_layout',
        'type'	   => 'select',
        'choices'  => array (
			'one-col' 		   => __('No Sidebars', 'salt'),
			'two-col-left'     => __('Righthand Sidebar', 'salt'),
			'two-col-right'    => __('Lefthand Sidebar', 'salt'),
			'three-col-middle' => __('Both', 'salt')
		)
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
        'label'    => __( 'Hide website credits.', 'salt' ),
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
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_footer_sidebars', array(
        'label'    => __( 'Footer Widgets Areas', 'salt' ),
        'section'  => 'footer',
        'settings' => 'salt_footer_sidebars',
        'type'	   => 'select',	        
        'choices'  => array (
			'0' => '0',
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4'
		)
	)));			
}
add_action( 'customize_register' , 'salt_customize_register' );