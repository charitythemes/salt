<?php
/**
 * Salt Customize Class
 *
 * Give opportunity to make special sidebar of current page.
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 */
 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates the Theme Customizer Options
 *
 * this class should be extended in a new class,
 * and certain method must be overrided to apply your custom types
 *
 */
if( !class_exists('Salt_Theme_Customize') ) {
	class Salt_Theme_Customize {
		
		/**
		* This hooks into 'customize_register' (available as of WP 3.4) and allows
		* you to add new sections and controls to the Theme Customize screen.
		* 
		* @see add_action('customize_register',$func)
		* @param \WP_Customize_Manager $wp_customize
		* @link https://codex.wordpress.org/Theme_Customization_API
		* @since 1.0
		*/		
		public static function register ( $wp_customize ) {
		}
		
		/**
		* This will output the custom WordPress settings to the live theme's WP head.
		* 
		* Used by hook: 'wp_head'
		* 
		* @see add_action('wp_head',$func)
		* @since 1.0
		*/
		public static function header_output() {
		}
		
		/**
		* This outputs the javascript needed to automate the live settings preview.
		* Also keep in mind that this function isn't necessary unless your settings 
		* are using 'transport'=>'postMessage' instead of the default 'transport'
		* => 'refresh'
		* 
		* Used by hook: 'customize_preview_init'
		* 
		* @see add_action('customize_preview_init',$func)
		* @since 1.0
		*/
		public static function live_preview() {
			wp_enqueue_script( 
			   'salt-customizer', 
			   get_template_directory_uri() . '/core/assets/js/theme-customizer.js',
			   array(  'jquery', 'customize-preview' ), // Define dependencies
			   '', 	// Define a version (optional) 
			   true // Specify whether to put in footer (leave this true)
			);
		}

		/**
		 * This will generate a line of CSS for use in header output. If the setting
		 * ($mod_name) has no defined value, the CSS will not be output.
		 * 
		 * @uses get_theme_mod()
		 * @param string $selector CSS selector
		 * @param string $style The name of the CSS *property* to modify
		 * @param string $mod_name The name of the 'theme_mod' option to fetch
		 * @param string $prefix Optional. Anything that needs to be output before the CSS property
		 * @param string $postfix Optional. Anything that needs to be output after the CSS property
		 * @param bool $echo Optional. Whether to print directly to the page (default: true).
		 * @return string Returns a single line of CSS with selectors and a property.
		 * @since 1.0
		 */
		public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
		  $return = '';
		  $mod = get_theme_mod($mod_name);
		  if ( ! empty( $mod ) ) {
		     $return = sprintf('%s { %s:%s; }',
		        $selector,
		        $style,
		        $prefix.$mod.$postfix
		     );
		     if ( $echo ) {
		        echo $return;
		     }
		  }
		  return $return;
		}		
	}
}

/**
 * Register New Controls to use in the Customizer
 *
 */
function salt_register_new_controls() {
	
	/**
	 * Adds an 'Image Select' option into the Customizer
	 *
	 */
	class WP_Customize_ImageSelect_Control extends WP_Customize_Control {
		public $type = 'imageselect';
		
		public function render_content() {
			
			if ( empty( $this->choices ) )
				return;
	
			$name = '_customize-imageselect-' . $this->id;
	
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			
			<?php
			foreach ( $this->choices as $value => $src ) :
				?>
				<span>
					<label>
						<input type="radio" id="<?php echo $value; ?>" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> class="selectimages-radio" />
						<?php $select = ($this->value() == $value) ? 'selected' : ''; ?>
						<img alt="<?php echo $value; ?>" src="<?php echo $src; ?>" alt="" class="selectimages <?php echo $select; ?>" onClick="" />
					</label>
				</span>
				<?php 
			endforeach;
			?>
			</label>
			<?php
		}
	}
}

function salt_customize_controls_print_styles() {
	?>
	<style>
		input[type=radio].selectimages-radio {
			display: none; }
			
		.selectimages {
			border: 3px solid #fff;
			margin: 0 2px 10px 0;
			cursor: pointer;
			float: left; }
			
		.selectimages.selected {
			border: 3px solid #2da3cc }
		
		.selectimages:hover {
			opacity: .8; }
	</style>
	<?php
}

add_action( 'customize_controls_print_styles', 'salt_customize_controls_print_styles');

function salt_customize_controls_print_scripts() {
	?>
	<script>
		jQuery( document ).ready(function() {
			jQuery('.selectimages').click(function() {
				jQuery(this).parent().parent().parent().find('.selected').removeClass('selected');
				jQuery(this).addClass('selected');
			});
		});
	</script>
	<?php
}

add_action( 'customize_controls_print_footer_scripts', 'salt_customize_controls_print_scripts');

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , 'salt_register_new_controls' );
add_action( 'customize_register' , array( 'Salt_Theme_Customize' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Salt_Theme_Customize' , 'header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'Salt_Theme_Customize' , 'live_preview' ) );