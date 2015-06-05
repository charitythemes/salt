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

if ( !function_exists( 'salt_enqueue_live_preview' ) ) :
/**
* This hooks into 'customize_preview_init' and enqueues scripts to be used
* within the Theme Customize screen.
* 
* @since Salt 1.1.0
*/
function salt_enqueue_live_preview() {
	wp_enqueue_script( 'salt-customizer', get_template_directory_uri() . '/core/assets/js/theme-customizer.js', array(  'jquery', 'customize-preview' ), '1.1.0');
}
add_action( 'customize_preview_init' , 'salt_enqueue_live_preview' );
endif;

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
	 * Social
	 *
	 * Social media options 
	 * 
	 * @since Salt 1.1.0
	 */
	$wp_customize->add_panel( 'social', array(
        'title'    		=> __('Social', 'salt'),
        'priority' 		=> 50,
        'description' 	=> __('Add social media icons to your website or blog.', 'salt'),
    ));
    
    // Options for the look & feel.
    $wp_customize->add_section( 'look', array(
		'title' 		=> __('Look & Feel', 'salt'),
		'description' 	=> __('Position the social media icons on your blog.', 'salt'),
		'priority' 		=> '5',
		'panel' 		=> 'social'
    ));

    // Social Icons Position
    $wp_customize->add_setting('salt_social_position', array(
    	'default'       => 'right',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_position', array(
        'label'    		=> __('Position', 'eggplant'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_position',
        'type'          => 'radio',
        'choices'       => array (
        	'above'  => __('Above the menu bar', 'eggplant'),
			'right'  => __('On the right of the menu bar', 'eggplant'),
		)
    )));
    
	// Options for the look and feel of the social icons.
    $wp_customize->add_setting('salt_social_shape', array(
    	'default'       => '',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod',
        'transport' 	=> 'postMessage'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_shape', array(
        'label'    		=> __('Shape', 'eggplant'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_shape',
        'type'          => 'select',
        'choices'       => array (
        	'circle'   => __('Circular', 'eggplant'),
			'square'   => __('Square', 'eggplant'),
			'no-shape' => __('No Background', 'eggplant')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_shape' )->transport = 'postMessage';
    
    // Social Icons Type
    $wp_customize->add_setting('salt_social_type', array(
    	'default'       => 'black',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod',
        'transport' 	=> 'postMessage'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_type', array(
        'label'    		=> __('Type', 'eggplant'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_type',
        'type'          => 'select',
        'choices'       => array (
        	'black' => __('Black', 'eggplant'),
			'white' => __('White', 'eggplant'),
			'color' => __('Color', 'eggplant')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_type' )->transport = 'postMessage'; 

    // Social Icons Size
    $wp_customize->add_setting('salt_social_size', array(
    	'default'       => 'small',
        'capability'    => 'edit_theme_options',
        'type'          => 'theme_mod',
        'transport' 	=> 'postMessage'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_size', array(
        'label'    		=> __('Size', 'eggplant'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_size',
        'type'          => 'select',
        'choices'       => array (
        	'small'  => __('Small', 'eggplant'),
			'medium' => __('Medium', 'eggplant'),
			'large'  => __('Large', 'eggplant')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_size' )->transport = 'postMessage'; 
        
    // Options the different URL's for each social media option.
    $wp_customize->add_section( 'links', array(
		'title' 		=> __('Social Links', 'salt'),
		'description' 	=> __('Add links to your social pages. You know Facebook, Instagram and all that.', 'salt'),
		'priority' 		=> '5',
		'panel' 		=> 'social'
    ));

	global $_salt_registered_social;
	$accounts = $_salt_registered_social;
    
	if ($accounts) foreach ($accounts as $account => $name) {
		
		$setting_id = 'salt_social_'.$account;
		$setting_args = array(
	    	'default'       => '',
	        'capability'    => 'edit_theme_options',
	        'type'          => 'theme_mod',
		);
		
		$wp_customize->add_setting( $setting_id, $setting_args ); 
		
		$control_id = 'salt_social_'.$account;
		$control_args = array( 
		    'label'    		=> $name,
		    'section'  		=> 'links',
		    'settings' 		=> $control_id,
		    'type'          => 'text',
		);
		
		$wp_customize->add_control( $control_id, $control_args ); 
		
	} 
	
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