<?php
/**
 * Main Salt Theme Class
 *
 * Loads the core classes & functions for Salt Theme
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/* Set the file path based on whether the Options Framework is in a parent theme or child theme */
define('SALT_FILEPATH', get_template_directory());
define('SALT_DIRECTORY', get_template_directory_uri());

if ( ! class_exists( 'Salt_Core' ) ) {

	class Salt_Core {
	
		/**
		 * Salt_Core Constructor.
		 *
		 * @param void
		 * @return void
		 *
		 * @access public
		 */
		function __construct() {

			// Include required files
			$this->includes();
		}
	
		/**
		 * Include required core files.
		 *
		 * @param void
		 * @return void
		 *
		 * @access public
		 */
		function includes() {

			/* Get Core Includes */
			if (!class_exists('cmb_Meta_Box')) {
				include_once( "inc/cmb/init.php" );
			}

			//Load up classes
			include_once( "classes/class-customize.php" );
			include_once( "classes/class-sidebars.php" );
			include_once( "classes/class-cmb.php" );

			/* Check for & include child theme files */
			$this->frontend_includes();
			
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts') );
			}
		}
		
		/**
		 * Include admin scripts
		 *
		 * @param void
		 * @return void
		 *
		 * @access public
		 */
		function admin_scripts() {
			wp_enqueue_style( 'salt-styles', SALT_DIRECTORY.'/core/assets/css/admin.css');	
			wp_enqueue_script( 'salt-js', SALT_DIRECTORY . '/core/assets/js/admin.js', array('jquery') );
		}	
					
		/**
		 * Include required frontend files
		 *
		 * @param void
		 * @return void
		 *
		 * @access public
		 */
		function frontend_includes() {
			$includes_path        = get_template_directory() . '/includes/';
			$child_includes_path  = get_stylesheet_directory() . '/includes/';
			
			$register_files = array(
				'theme-functions.php',
				'theme-setup.php',
				'theme-actions.php',
				'theme-options.php',
				'theme-metaboxes.php',
				'theme-customizer.php'
			);
	
			// Loop through and register all the appropriate files
			foreach ($register_files as $file) {
				
				// Load customized theme options settings
				if( file_exists($includes_path . $file) ) {
					require_once($includes_path . $file);
				}
				// Load customized theme options from child theme
				if( file_exists($child_includes_path . $file) ) {
					require_once($child_includes_path . $file);
				}
			}
		}
	}
}

$GLOBALS['salt_framework'] = new Salt_Core();