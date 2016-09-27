<?php
if ( ! class_exists( 'Salt_Post_Types_Init' ) ) :
/**
 * Salt Post Types Init.
 *
 * This class initiates the salt post types framework.
 *
 * @package		Salt
 * @since		1.5.2
 */
class Salt_Post_Types_Init {
	
	/**
	 * Constructor.
	 *
	 * Initiate the class
	 */
	function __construct() {
		
		global $_salt_registered_post_types;
		
		// Include required files
		$this->includes();
	
		// Enqueue the required JS and CSS.
		add_action( 'admin_init', array( &$this, 'enqueue' ) );
		
		// Include post types added to the global array.
		if ( $_salt_registered_post_types ) foreach ( $_salt_registered_post_types as $file ) {
			if ( file_exists( $file ) ) 
				require_once $file;
		}
	}

	/**
	 * Includes
	 * 
	 * Include required core files.
	 */
	function includes() {

		// Load the base class for new post types.
		include_once( "base.php" );
		
		// Loads the class to create and run shortcodes for each post type.
		include_once( "shortcodes.php" );
	}
	
	/**
	 * Enqueue
	 *
	 * Enqueue the required JS and CSS.
	 */
	function enqueue() {
		
		wp_enqueue_style(  'salt-admin', SALT_TEMPLATE_URI.'/core/assets/css/meta.css', false, '1.0.0' );
		wp_enqueue_script( 'salt-admin', SALT_TEMPLATE_URI.'/core/assets/js/insert.js', array('jquery'), '1.0.0' );		
	}
}
$salt_post_types = new Salt_Post_Types_Init();
endif;