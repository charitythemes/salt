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

if ( !function_exists( 'salt_customize_control_js' ) ) :
/**
 * Binds JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 * 
 * @since Salt 1.4.0
 */
function salt_customize_control_js() {
	wp_enqueue_script( 'salt-color-scheme-control', get_template_directory_uri() . '/core/assets/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '1.1.0', true );
	wp_localize_script( 'salt-color-scheme-control', 'colorScheme', salt_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'salt_customize_control_js' );
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
	
	// Get the color scheme options
	// Function can be found in functions.php
	$color_scheme = salt_get_color_scheme();
	
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
		'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'salt_custom_logo', array(
        'label'    		=> __('Upload Logo', 'salt'),
        'section'  		=> 'title_tagline',
        'settings' 		=> 'salt_custom_logo',
        'priority'		=> 15,
    )));
    
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
	 * Blog
	 *
	 * Blog options 
	 * 
	 * @since Salt 1.3.0
	 */
	$wp_customize->add_section('blog', array(
        'title'    		=> __('Blog', 'salt'),
        'priority' 		=> 35,
        'description' 	=> __('Customize your blog', 'salt'),
    ));
	
	// Select a layout for the blog pages
    $wp_customize->add_setting('salt_blog_layout', array(
	    'default'           => '4',
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_blog_layout', array(
        'label'    => __( 'Layout', 'salt' ),
        'section'  => 'blog',
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
	
	// Turn on / off the about author section.
	$wp_customize->add_setting('salt_blog_about_author', array(
	    'default'           => false,
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_blog_about_author', array(
        'label'    => __( 'Hide About Author', 'salt' ),
        'description' 	=> __('Hide the "About The Author" section on the blog single post.', 'salt'),
        'section'  => 'blog',
        'settings' => 'salt_blog_about_author',
        'type'     => 'checkbox',
	)));
	
	/**
	 * Color Scheme
	 *
	 * Edit the colour scheme of the website to presets, or  
	 * select your own colors.
	 * 
	 * @since Salt 1.4.0
	 */

	$wp_customize->add_panel( 'color', array(
        'title'    		=> __('Color Scheme', 'salt'),
        'priority' 		=> 39,
        'description' 	=> __('Customize your website colors', 'salt'),
    ));
    
    // Options for color presets.
    $wp_customize->add_section( 'color_presets', array(
		'title' 		=> __('Color Presets', 'salt'),
		'description' 	=> __('Select a preset color scheme for your site.', 'salt'),
		'priority' 		=> '10',
		'panel' 		=> 'color'
    ));
	 
	// Select a color scheme.
    $wp_customize->add_setting('salt_color_scheme', array(
	    'default'           => 'light',
	    'capability'        => 'edit_theme_options',
	    'type'           	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
	));
	
	// 6 awesome color schemes to choose from
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_color_scheme', array(
        'section'  => 'color_presets',
        'settings' => 'salt_color_scheme',
        'type'	   => 'radio',
        'choices'  => array (
			'light'  => __('Light', 'salt'),
			'blue' 	 => __('Blue', 'salt'),
			'green'  => __('Green', 'salt'),
			'yellow' => __('Yellow', 'salt'),
			'red'	 => __('Red', 'salt'),
			'pink'	 => __('Pink', 'salt'),
			'orange' => __('Orange', 'salt'),
		)
	)));

    // 
    $wp_customize->add_section( 'color_select', array(
		'title' 		=> __('Override Presets', 'salt'),
		'description' 	=> __('Change the color tones below to override the preset color scheme.', 'salt'),
		'priority' 		=> '10',
		'panel' 		=> 'color'
    ));
	
	// Color keys for the available tones in this theme.
	$color_keys = array( 
		'darkest'   => __('Darkest Tone', 'salt'),
		'dark'		=> __('Dark Tone', 'salt'),
		'medium'	=> __('Medium Tone', 'salt'),
		'light'		=> __('Light Tone', 'salt'),
		'lightest'	=> __('Lightest Tone', 'salt')		
	);
	
	// Cycle through the available color tones and set a control in the customizer for each one.
	foreach ( $color_keys as $key => $label ) {

		$wp_customize->add_setting('salt_color_scheme_'.$key, array(
			'default'			=> $color_scheme[ $key ],
		    'capability'        => 'edit_theme_options',
		    'type'           	=> 'theme_mod',
		    'sanitize_callback' => 'sanitize_hex_color',
		));
		
		$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'salt_color_scheme_'.$key, array(
		    'label'    => $label,
		    'section'  => 'color_select',
		    'settings' => 'salt_color_scheme_'.$key,
		)));			
	}
	
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
    	'default'       	=> 'right',
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_position', array(
        'label'    		=> __('Position', 'salt'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_position',
        'type'          => 'select',
        'choices'       => array (
        	'' 	  		   => __('Hide', 'salt'),
        	'header' 	   => __('At the top', 'salt'),
			'menu-right'   => __('On the right of the menu', 'salt'),
			'stick-right'  => __('Stick to the right', 'salt'),
			'stick-left'   => __('Stick to the left', 'salt'),
			'footer'   	   => __('At the bottom', 'salt'),
		)
    )));
    
	// Options for the look and feel of the social icons.
    $wp_customize->add_setting('salt_social_shape', array(
    	'default'       	=> '',
        'capability'   	 	=> 'edit_theme_options',
        'type'         	 	=> 'theme_mod',
        'transport' 		=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_shape', array(
        'label'    		=> __('Shape', 'salt'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_shape',
        'type'          => 'select',
        'choices'       => array (
        	'circle'   => __('Circular', 'salt'),
			'square'   => __('Square', 'salt'),
			'no-shape' => __('No Background', 'salt')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_shape' )->transport = 'postMessage';
    
    // Social Icons Type
    $wp_customize->add_setting('salt_social_type', array(
    	'default'       	=> 'black',
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
        'transport' 		=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_type', array(
        'label'    		=> __('Type', 'salt'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_type',
        'type'          => 'select',
        'choices'       => array (
        	'black' => __('Black', 'salt'),
			'white' => __('White', 'salt'),
			'color' => __('Color', 'salt')
		)
    )));
    
    $wp_customize->get_setting( 'salt_social_type' )->transport = 'postMessage'; 

    // Social Icons Size
    $wp_customize->add_setting('salt_social_size', array(
    	'default'       	=> 'small',
        'capability'   		=> 'edit_theme_options',
        'type'          	=> 'theme_mod',
        'transport' 		=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'salt_social_size', array(
        'label'    		=> __('Size', 'salt'),
        'section'  		=> 'look',
        'settings' 		=> 'salt_social_size',
        'type'          => 'select',
        'choices'       => array (
        	'small'  => __('Small', 'salt'),
			'medium' => __('Medium', 'salt'),
			'large'  => __('Large', 'salt')
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
		
		$wp_customize->add_setting( $setting_id, array(
	    	'default'       	=> '',
	        'capability'    	=> 'edit_theme_options',
	        'type'          	=> 'theme_mod',
	        'sanitize_callback' => 'sanitize_text_field'
		) ); 
		
		$control_id = 'salt_social_'.$account;
		
		$wp_customize->add_control( $control_id, array( 
		    'label'    		=> $name,
		    'section'  		=> 'links',
		    'settings' 		=> $control_id,
		    'type'          => 'text',
		) ); 
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
	    'default'           => false,
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