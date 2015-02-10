<?php
if (!class_exists('Theme_Options')) :
/**
 * CMB Class API
 *
 * A class to load general media options into the Apperance panel using CMB options
 * 
 * @link https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Using-CMB-to-create-an-Admin-Theme-Options-Page 
 * @since Salt 1.0
 */ 
class Theme_Options {
 
 	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	protected static $key = 'theme_options';
 
	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected static $theme_options = array();
 
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';
 
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Theme Options', 'salt' );
 	}
 
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
	}
 
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( self::$key, self::$key );
	}
 
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_theme_page() {
		$this->options_page = add_theme_page( __('Theme Options', 'salt'), __('Theme Options', 'salt'), 'manage_options', self::$key, array(&$this, 'admin_page_display') );
	}
 
	/**
	 * Admin page markup. Mostly handled by CMB
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb_options_page <?php echo self::$key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb_metabox_form( self::option_fields(), self::$key ); ?>
		</div>
		<?php
	}
 
	/**
	 * Defines the theme option metabox and field configuration
	 * @since  0.1.0
	 * @return array
	 */
	public static function option_fields() {
 
		// Only need to initiate the array once per page-load
		if ( ! empty( self::$theme_options ) )
			return self::$theme_options;
			
		$prefix = 'salt_';
		
		self::$theme_options = array(
			'id'         => 'theme_options',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'show_names' => true,
		);
		
		global $_salt_registered_social;
		$accounts = $_salt_registered_social;
		
		self::$theme_options['fields'][] = array(
			'name' => __('Social Page Linkydinks', 'salt'),
			'desc' => sprintf(__('Add links to your social pages. You know Facebook, Instagram and all that. You can also <a href="%s">customise the icons here</a>', 'salt'), get_admin_url( $blog_id, 'customize.php' ) ),
			'id'   => $prefix . 'show_projectsasd',
			'type' => 'title'
		);
		
		if ($accounts) foreach ($accounts as $account => $name) {
			self::$theme_options['fields'][] = array(
			    'name' => $name,
			    'desc' => sprintf(__('Add the URL to your %s account', 'salt'), $account),
			    'id'   => 'social_'.$account,
			    'type' => 'text_medium',
			);
		} 

		return self::$theme_options;
	}
 
	/**
	 * Make public the protected $key variable.
	 * @since  0.1.0
	 * @return string  Option key
	 */
	public static function key() {
		return self::$key;
	}
}
endif;
 
// Get it started
$myprefix_Admin = new Theme_Options();
$myprefix_Admin->hooks();
 
/**
 * Wrapper function around cmb_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function get_theme_option( $key = '' ) {
	return cmb_get_option( Theme_Options::key(), $key );
}
?>