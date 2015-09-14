<?php
/**
 * Salt Typography Options
 *
 * These options appear in the WordPress customizer. And can override the  
 * themes default typography.
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * 
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.2.0
 */
 	
if ( ! class_exists( 'Salt_Typography' ) ) :
/**
* This class contains all the functions needed for 
* to show the typography options in the customizer.
* 
* @since Salt 1.2.0
*/
class Salt_Typography {
	
	/**
	 * API library to use (Google or Useso).
	 *
	 * @var string Holds the either 'googleapis' or 'useso' to load use select api. (Default is googleapis)
	 */
	public $api_libs;

	/**
	 * Users API key for googleapis
	 *
	 * @var string Holds the users googleapi key
	 */
	public $api_key;
	
	/**
	 * Google Fonts List
	 *
	 * @var array holds the google fonts list 
	 */
	public $google_fonts;	

	/**
	 * Standard Fonts
	 *
	 * @var array Holds an array of web friendly standard fonts 
	 */
	public $standard_fonts;
			
	/**
	 * Constructor Function
	 * 
	 * Initialize the class.
	 *
	 * @since Salt 1.2.0
	 * 
	 */
	function __construct() {
		
		// Set which api library to use.
		$this->api_libs = get_option( 'salt_typography_api_library', 'googleapis' );
		$this->api_key  = get_option( 'salt_typography_api_key', false );

		// Set the google fonts var.
		$this->standard_fonts = $this->get_standard_fonts();
		
		// Set the google fonts var.
		$this->google_fonts = $this->get_google_fonts();
		
		// Register the customiers settings.
		add_action( 'customize_register' , array( &$this, 'register_settings' ) );
		
		// Print the google font stylesheets. 
		add_action( 'wp_print_styles', array( &$this, 'print_styles' ) );
		
		// Add the css to override the different typography options.
		add_action( 'wp_head', array( &$this, 'css_template' ) );
		
		// Add an ajax function to return the weight list for a particular font family.
		// Used to update the font weight select box on the cutomizer.
		add_action( 'wp_ajax_salt_get_font_weight', array( &$this, 'get_font_weight_array' ) );
		
		// Enqueue the scripts to be used with the customizer.
		add_action( 'customize_controls_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
	}	
	
	/**
	 * Enqueue Scripts
	 * 
	 * Enqueue the scripts to be used with the customizer.
	 *
	 * @since Salt 1.2.0
	 */
	function enqueue_scripts() {
		
		wp_enqueue_script( 'salt-font-weight-control', get_template_directory_uri() . '/core/assets/js/font-weight-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '1.2.0', true );

		wp_localize_script( 'salt-font-weight-control', 'saltFontWeightDefault', array(
			'theme_default' => __('--- Theme Default ---', 'salt')
		));
		
		$all_fonts = $this->get_all_fonts();
		wp_localize_script( 'salt-font-weight-control', 'saltAllFonts', $all_fonts );
	}
	
	/**
	 * Get Typography Options
	 *
	 * Array of typography options for this thene. Developers can add their own
	 * by applying the filter on the array.
	 *
	 *		id			Customizer section ID
	 *		label		Customizer section label
	 *		description Customizer section description
	 *		selector	Patterns used to select the element(s) you want to style
	 *		properties	array of css properties and default settings
	 * 
	 * @return array Returns the array of typography options.
	 * @since Salt 1.2.0
	 */
	public function get_typography_options() {
		
		return apply_filters( 'salt_typography_options', array (
			'base' => array(
				'id'			=> 'salt_base_font',
				'label' 		=> __( 'Default', 'salt' ),
				'selector'		=> 'body',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-size'			=> '14px',
					'font-weight'		=> '500',
					'color'				=> '#666666',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'paragraph' => array(
				'id'			=> 'salt_paragraph_font',
				'label' 		=> __( 'Paragraph', 'salt' ),
				'selector'		=> 'body p',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#666666',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'h1' => array(
				'id'			=> 'salt_h1_font',
				'label' 		=> __( 'H1', 'salt' ),
				'selector'		=> 'body h1',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'h2' => array(
				'id'			=> 'salt_h2_font',
				'label' 		=> __( 'H2', 'salt' ),
				'selector'		=> 'body h2',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'h3' => array(
				'id'			=> 'salt_h3_font',
				'label' 		=> __( 'H3', 'salt' ),
				'selector'		=> 'body h3',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '400',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'h4' => array(
				'id'			=> 'salt_h4_font',
				'label' 		=> __( 'H4', 'salt' ),
				'selector'		=> 'body h4',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
			'h5' => array(
				'id'			=> 'salt_h5_font',
				'label' 		=> __( 'H5', 'salt' ),
				'selector'		=> 'body h5',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'uppercase'
				)
			),
			'h6' => array(
				'id'			=> 'salt_h6_font',
				'label' 		=> __( 'H6', 'salt' ),
				'selector'		=> 'body h6',
				'properties' 	=> array(
					'font-family'		=> 'helvetica',
					'font-weight'		=> '500',
					'color'				=> '#333333',
					'line-height'		=> '1.5',
					'text-transform'    => 'none'
				)
			),
		) );
	}
	
	/**
	 * CSS Property Labels
	 *
	 * For each property passed through the typography options
	 * we need a label. These are provided through this function.
	 * 
	 * @param string $property represents the CSS property being customized.
	 * @return string label to be used on the customizer field.
	 * @since  Salt 1.2.0
	 *
	 */
	public function get_css_property_label( $property ) {
		
		// Depending on the property create the label.
		switch ( $property ) {
			case 'font-family':
				$title = __('Font Family', 'salt');
				break;
				
			case 'font-size':
				$title = __('Font Size', 'salt');
				break;
				
			case 'font-weight':
				$title = __('Weight', 'salt');
				break;
				
			case 'color':
				$title = __('Color', 'salt');
				break;
			
			case 'line-height':
				$title = __('Line Height', 'salt');
				break;
				
			case 'text-transform':
				$title = __('Character', 'salt');
				break;
		}
		
		// Include a filter so the label can edited if needed.
		// Passes the $property var so the label can match the property. 
		return apply_filters( 'salt_property_label', $title, $property );
	}
	
	/**
	 * Register Settings
	 *
	 * Register the settings with the WordPress customizer.
	 * 
 	 * @see add_action('customize_register',$func)
	 * @param WP_Customize_Manager $wp_customize
	 * @since  Salt 1.2.0
	 */
	public function register_settings( $wp_customize ) {
		
		// Get the typography options for this theme.
		$theme_typography = $this->get_typography_options();

		// If there are options available.
		if ( $theme_typography ) {
		
			// Add a panel to hold the typography options.
			$wp_customize->add_panel( 'typography', array(
		        'title'    		=> __('Typography', 'salt'),
		        'priority' 		=> 38,
		        'description' 	=> __('', 'salt'),
		    ));

			// Cycle through each selector			
			foreach ( $theme_typography as $type => $options ) {
				
				// Default section arguments include a title and the panel.
				$section_args = array(
			        'title'    		=> $options['label'],
			        'panel'			=> 'typography'
			    );
			    
			    // If the description has also been set, let's add that.
			    if ( isset( $options['description'] ) ) {
					$section_args['description'] = $options['description'];
			    }
				
				// Add a section for this selector eg: paragraph or header.
			    $wp_customize->add_section( $type, $section_args );
		
				foreach ( $options['properties'] as $property => $default ) {
					
					// Create an id based on the options id and property.
					$id    = $options['id'] . '_' . strtolower( str_replace( '-', '_', $property ) );
					
					// Get the label based on the CSS property.
					$label = $this->get_css_property_label( $property );
					
					// Use the customizer color control for the css color property.
					if ( $property == 'color' ) {
						
						$wp_customize->add_setting( $id, array(
							'default'			=>  $default,
						    'capability'        => 'edit_theme_options',
						    'type'           	=> 'theme_mod',
						    'sanitize_callback' => 'sanitize_hex_color',
						));
						
						$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
						    'label'    => __('Font color', 'salt'),
						    'section'  => $type,
						    'settings' => $id,
						)));			
						
					} else {
						
						// This method call a function to return the options for this property.
						$method = 'print_' . strtolower( str_replace( '-', '_', $property ) ) . '_array';
						
						$wp_customize->add_setting( $id, array(
						    'default'           => $default,
						    'capability'        => 'edit_theme_options',
						    'type'           	=> 'theme_mod',
						    'sanitize_callback' => 'sanitize_text_field'
						));
						
						$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $id, array(
						    'label'    => $label,
						    'section'  => $type,
						    'settings' => $id,
						    'type'	   => 'select',
						    'choices'  => $this->$method( $options['id'] )
						)));
												
					}
				}
				
				// Add a section for some settings.
			    $wp_customize->add_section( 'settings', array(
			        'title'    		=> __('Settings', 'salt'),
			        'description' 	=> __('A few options to help you get the most out of Google Fonts.', 'salt'),
			        'panel'			=> 'typography'
			    ));
			    
			    // Let the user select an API library.
			    $wp_customize->add_setting( 'salt_typography_api_library', array(
				    'default'           => 'googleapis',
				    'capability'        => 'edit_theme_options',
				    'type'           	=> 'option',
				    'sanitize_callback' => 'sanitize_text_field'
				));
				
				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_typography_api_library', array(
				    'label'    	  => __('API Library', 'salt'),
				    'description' => __('In most cases the fonts should be loaded from Google, but in some cases (like in China), Google\'s API is blocked, so try using Useso instead.', 'salt'),
				    'section'  	  => 'settings',
				    'settings'    => 'salt_typography_api_library',
				    'type'	   	  => 'select',
				    'choices'  	  => array(
					    'googleapis' => __('Google (Default)', 'salt'),
					    'useso' 	 => __('Useso (China Friendly)', 'salt')
				    )
				)));
				
				// Let the user select an API library.
			    $wp_customize->add_setting( 'salt_typography_api_key', array(
				    'default'           => '',
				    'capability'        => 'edit_theme_options',
				    'type'           	=> 'option',
				    'sanitize_callback' => 'sanitize_text_field'
				));
				
				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_typography_api_key', array(
				    'label'    	  => __('API Key', 'salt'),
				    'description' => sprintf( __('Visit the <a href="%s" target="_blank">Google APIs Console</a>. Once you have entered a valid Google API key the customizer will load the latest google fonts to choose from.', 'salt'), 'https://code.google.com/apis/console' ),
				    'section'  	  => 'settings',
				    'settings'    => 'salt_typography_api_key',
				    'type'	   	  => 'text'
				)));
			}
		}
	}
	
	/**
	 * Font Family Array
	 *
	 * Create the font family array including standard and google fonts.
	 * 
	 * @return array all the available fonts.
	 * @since Salt 1.2.0
	 */
	public function print_font_family_array() {
		
		// Get an array list of all standard or websafe fonts.
		$standard_font_options = $this->standard_fonts;
		
		// Make the first option to return to theme default.
		$font_array[] = __('--- Theme Default ---', 'salt');
		
		$font_array[] = __('==== Standard Fonts ====', 'salt');
		
		// Cycle through the font options and create an new array that can be used on the Customizer.
		foreach ( $standard_font_options as $font => $option ) {
			
			$font_array[ $option["name"] ] = $option["name"];
		} 

		// Get an array list of all fonts.
		$google_font_options = $this->google_fonts;

		// Add an extra space.
		$font_array[] = '';
		
		// Make the first option to return to theme default.
		$font_array[] = __('==== Google Fonts ====', 'salt');
		
		// Cycle through the font options and create an new array that can be used on the Customizer.
		foreach ( $google_font_options as $font => $option ) {
			
			$font_array[ $option["name"] ] = $option["name"];
		} 
		
		// Return the array.
		return $font_array;
	}
	
	/**
	 * Font Weight Array
	 *
	 * Create the font weight array including standard and google fonts.
	 * 
	 * @return array all the available fonts.
	 * @since Salt 1.2.0
	 *
	 */
	public function print_font_weight_array( $option_id ) {
		
		$font_options = array();
		
 		$font_options = $this->get_all_fonts();
		$current_font = get_theme_mod( $option_id . '_font_family' );
		
		$weight_array[ '' ] = __('--- Theme Default ---', 'salt');
		
		if ( isset( $font_options[ $current_font ] ) ) {
			foreach ( $font_options[ $current_font ][ 'font_weights' ] as $weight ) {
				$weight_array[ $weight ] = $weight;
			} 
		}
		
		return $weight_array;
	}
	
	public function get_font_weight_array() {
	}
	
	/**
	 * Font Size Array
	 *
	 * An array of the various font size options to appear in the customizer dropdown.
	 * Developers can edit this list via add_filter.
	 * 
	 * @return array all the available font sizes.
	 * @since Salt 1.2.0
	 *
	 */
	public function print_font_size_array() {
		
		return apply_filters( 'salt_font_size_options', array(
			''		=> __('--- Theme Default ---', 'salt'),
			'10px'	=>	'10px',
			'11px'	=>	'11px',
			'12px'	=>	'12px',
			'14px'	=>	'14px',
			'16px'	=>	'16px',
			'18px'	=>	'18px',
			'24px'	=>	'24px',
			'30px'	=>	'30px',
			'36px'	=>	'36px',
			'48px'	=>	'48px',	
			'60px'	=>	'60px',	
			'72px'	=>	'72px'
		));
	}
	
	/**
	 * Text Transform Array
	 *
	 * An array of the various text transform CSS options to appear in the customizer dropdown.
	 * Developers can edit this list via add_filter.
	 * 
	 * @return array all the available text transform options.
	 * @since Salt 1.2.0
	 *
	 */
	public function print_text_transform_array() {
		
		return apply_filters( 'salt_text_transform_options', array(
		    'none'			=> __('None', 'salt'),
		    'uppercase'		=> __('Uppercase', 'salt'),
		    'lowercase'	 	=> __('Lowercase', 'salt'),
		    'capitalize'	=> __('Capitalize', 'salt')
		));
	}
	
	/**
	 * Line Height Array
	 *
	 * An array of the some basic line height CSS options to appear in the customizer dropdown.
	 * Developers can edit this list via add_filter.
	 * 
	 * @return array basic line height options.
	 * @since Salt 1.2.0
	 *
	 */
	public function print_line_height_array() {
		
		return apply_filters( 'salt_line_height_options', array(
			''		=> __('--- Theme Default ---', 'salt'),
			'1.2'	=>	'Single',
			'1.5'	=>	'1.5 Lines',
			'2.0'	=>	'Double',
		));
	}
	
	/**
	 * Standard Fonts Array
	 *
	 * An array of the websafe fonts available with most computer setups.
	 * 
	 * @return array of the websafe fonts available.
	 * @since Salt 1.2.0
	 *
	 */
	public function get_standard_fonts() {
		
		// List of websafe fonts and available weight options for each one.
		$font_list = array( 
			'Arial'               => array( '400', '400italic', '700', '700italic' ),
			'Century Gothic'      => array( '400', '400italic', '700', '700italic' ),
			'Courier New'         => array( '400', '400italic', '700', '700italic' ),
			'Georgia'             => array( '400', '400italic', '700', '700italic' ),
			'Helvetica'           => array( '400', '400italic', '700', '700italic' ),
			'Impact'              => array( '400', '400italic', '700', '700italic' ),
			'Lucida Console'      => array( '400', '400italic', '700', '700italic' ),
			'Lucida Sans Unicode' => array( '400', '400italic', '700', '700italic' ),
			'Palatino Linotype'   => array( '400', '400italic', '700', '700italic' ),
			'sans-serif'          => array( '400', '400italic', '700', '700italic' ),
			'serif'               => array( '400', '400italic', '700', '700italic' ),
			'Tahoma'              => array( '400', '400italic', '700', '700italic' ),
			'Trebuchet MS'        => array( '400', '400italic', '700', '700italic' ),
			'Verdana'             => array( '400', '400italic', '700', '700italic' ),			
		);
		
		// Cycle through each font and adapt the array for the WordPress Customizer.
		foreach ( $font_list as $font => $weights ) {
			
			$atts = array(
				'name'	 	   => $font,
				'font_weights' => $weights
			);
			
			$fonts[ $font ] = $atts;
		}
		
		// Return the array of fonts.
		return apply_filters( 'salt_standard_fonts', $fonts );
	}

	/**
	 * Get Default Google Fonts
	 *
	 * Fetches all of the current fonts as a JSON object using
	 * the google font API and outputs it as a PHP Array. This 
	 * is an internal function designed to flag outdated and 
	 * new fonts so that we can update the fonts array list
	 * accordingly.
	 * 
	 * DEVELOPER NOTE: 
	 * 
	 * For this function to work correctly you 
	 * would need to sign up for a google API Key and enter it 
	 * into the settings page.
	 *
	 * Custom Filters:
	 *     - 'salt_google_fonts'
	 *
	 * Transients:
	 *     - 'salt_google_fonts'
	 *
	 * @return array $fonts - All google fonts with their properties
	 *
	 * @since Salt 1.2.0
	 * 
	 */
	public function get_google_fonts() {
		
		// Variable to hold fonts;
		$fonts = array();
		$json  = array();
		
		// Check if transient is set
		if ( false === get_transient( 'salt_google_fonts' ) ) {

			/*
			 * First we want to try to update the font transient with the
			 * latest fonts if possible by sending an API request to google. 
			 * If this is not possible then the theme will just use the 
			 * current list of webfonts.
			 */

			// Get list of fonts as a JSON Object from Google's server if the API key is set.
			if ( $this->api_key ) {

				/**
				 * Google Fonts API Key
				 *
				 * Please enter the developer API Key for unlimited requests
				 * to google to retrieve all fonts. If you do not enter an API
				 * key google will
				 * 
				 * {@link https://developers.google.com/fonts/docs/developer_api}
				 */
				 	
				$response = wp_remote_get( "https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key={$this->api_key}", array( 'sslverify' => false ) );	
			
				/*
				 * Now we want to check that the request has a valid response
				 * from google. If the request is not valid then we fall back
				 * to the webfonts.json file.
				 */
				// Check it is a valid request
				if ( ! is_wp_error( $response ) ) {
	
					$font_list = $this->json_decode( $response['body'], true );
	
					// Make sure that the valid response from google is not an error message
					if ( ! isset( $font_list['error'] ) ) {
						$json = $response['body'];
	
					} else {
						$json  = wp_remote_fopen( get_template_directory_uri() . '/core/assets/fonts/webfonts.json' );	
					}
				}
				
			} else {
				$json  = wp_remote_fopen( get_template_directory_uri() . '/core/assets/fonts/webfonts.json' );
			}

			$font_output = $this->json_decode( $json, true );

			foreach ( $font_output['items'] as $item ) {
					
				$urls = array();

				// Get font properties from json array.
				foreach ( $item['variants'] as $variant ) {

					$name = str_replace( ' ', '+', $item['family'] );
					$urls[ $variant ] = "https://fonts.{$this->api_libs}.com/css?family={$name}:{$variant}";

				}

				$atts = array( 
					'name'         => $item['family'],
					'font_weights' => $item['variants'],
				);

				// Add this font to the fonts array
				$id           = strtolower( str_replace( ' ', '_', $item['family'] ) );
				$fonts[ $item['family'] ] = $atts;

			}
			
			// Filter to allow us to modify the fonts array before saving the transient
			$fonts = apply_filters( 'salt_google_fonts', $fonts );
			
			// Set transient for google fonts
			if ( ! empty( $fonts ) ) {
				set_transient( 'salt_google_fonts', $fonts, 14 * DAY_IN_SECONDS );
			}
			
		} else {
			$fonts = get_transient( 'salt_google_fonts' );
		}

		return apply_filters( 'salt_google_fonts', $fonts );
	}

	/**
	 * Get All Fonts
	 *
	 * Merges the default system fonts and the google fonts
	 * into a single array and returns it
	 *
	 * @return array All fonts with their properties
	 * @since Salt 1.2.0
	 */
	public function get_all_fonts() {
		$default_fonts = $this->standard_fonts;
		$google_fonts  = $this->google_fonts;
		
		if ( ! $default_fonts ) {
			$default_fonts = array();
		}

		if ( ! $google_fonts ) {
			$google_fonts = array();
		}

		return array_merge( $default_fonts, $google_fonts );
	}

	/**
	 * Decode JSON
	 *
	 * Attempts to decode json into an array.
	 * This new function accounts for servers
	 * running an older version of PHP with
	 * magic quotes gpc enabled.
	 * 
	 * @param  string  $str   - JSON string to convert into an array
	 * @param  boolean $accoc [- Whether to return an associative array
	 * @return array - Decoded JSON array
	 *
	 * @since Salt 1.2.0
	 */
	public static function json_decode( $str = '', $accoc = false ) {
		$json_string = get_magic_quotes_gpc() ? stripslashes( $str ) : $str;
		return json_decode( $json_string, $accoc );
	}

	/**
	 * Print Styles
	 *
	 * Prepare the font styles to be loaded in the header. For Google Fonts load
	 * the font directly from Google or Useso.
	 *
	 * @since Salt 1.2.0
	 */
	public function print_styles() {
		
		// Variable to hold fonts
		$font_list = array();
		
		// Get the typography options for this theme.
		$theme_typography = $this->get_typography_options();
		
		// If there are options available.
		if ( $theme_typography ) {

			// Cycle through each selector.
			foreach ( $theme_typography as $type => $options ) {
				
				// Check if there is font family attached to this option.
				if ( $family = get_theme_mod( $options['id'] . '_font_family' ) ) {
					
					// Get the font weight attached to this same option .
					$weight = get_theme_mod( $options['id'] . '_font_weight', 'default' );
					
					// if weight is an empty string change it to default.
					if ( $weight == '' ) 
						$weight = 'default';
					
					// Add the weights to an array.
					$font_list[ $family ][] = $weight;
				}
			}
		}
		
		// Get the google font list as array.
		$google_fonts = $this->google_fonts;

		// Cycle through the array and register the required fonts.
		if ( is_array( $font_list ) ) {
			
			// Cycle through the array and register the required fonts.
			foreach ( $font_list as $family => $variants ) {

				// Check if this is in the google fonts list.
				if ( isset( $google_fonts[ $family ] ) ) {
					
					// The family name is stored 
					$family = $google_fonts[ $family ][ 'name' ];
					
					$variants = '';
					$count = 0;
					
					// Cycle through the different available weights
					foreach ( $font_list[ $family ] as $weight ) {
						
						// Increase the count.
						$count++;
						
						// Check if font weight exists for this font family.
						if ( ! in_array( $weight, $google_fonts[ $family ][ 'font_weights' ] ) ) {
							
							// Set to the font families first font.
							$variants .=  $google_fonts[ $family ][ 'font_weights' ][0];
						} else {
							
							// Otherwise add the weight.
							$variants .= $weight;
						}
						
						if ( $count < sizeof( $font_list[ $family ] ) ) 
							$variants .= ',';
					}
					
					// Create a suitable handle for each font.
					$handle = strtolower( str_replace( ' ', '-', $family ) );
					
					// Register the google font family.
					wp_register_style( 'salt-google-fonts-'.$handle, "//fonts.{$this->api_libs}.com/css?family={$family}:{$variants}" );
					wp_enqueue_style(  'salt-google-fonts-'.$handle );				
				}
			}
		}
	}
	
	/**
	 * CSS Template
	 *
	 * Echo the CSS scheme that has been set by the users in customizer.
	 *
	 * @since Salt 1.2.0
	 */
	function css_template() {
		
		// Get the typography CSS scheme set by the user in the customizer.
		$css = $this->css_output();
		
		echo '<style type="text/css" media="screen" id="tmpl-salt-typography">'.$css.'</style>';
	}

	/**
	 * CSS Output
	 *
	 * Create the CSS output to be added in the site header.
	 *
	 * @since Salt 1.2.0
	 */
	public function css_output() {
		
		// Variable to hold css template.
		$css = '';
		
		$theme_typography = $this->get_typography_options();
		
		if ( $theme_typography ) {
		
			foreach ( $theme_typography as $type => $options ) {
				
				// Clear the line on each font cycle.
				$line = '';
				
				foreach ( $options['properties'] as $property => $default ) {
					
					$id = $options['id'] . '_' . strtolower( str_replace( '-', '_', $property ) );
					
					if ( $option = get_theme_mod( $id ) ) {
						
						// If the font weight option includes the word italic.
						if ( strpos( $option, 'italic' ) !== false ) { 
							
							// Remove the word italic from the font weight.
							$option = str_replace( 'italic', '', $option );
							
							// After the italic is removed, the font weight might be blank, if so set it to inherit
							if ( $option == '' )
								$option = 'inherit';
								
							// Add a style property to include the italics
							$line .= "  font-style: italic; \n";
						}
						
						$line .= "  $property: $option; \n";
					}
				}

				$label 	  = $options['label'];
				$selector = $options['selector'];
				
				$css .= <<<CSS

/* {$label} Override */
{$selector} { 
{$line}
}
CSS;
			}
		} 				

		return $css;
	
	}
	
}
$typography = new Salt_Typography();
endif;