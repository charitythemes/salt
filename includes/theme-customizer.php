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
add_action( 'customize_register' , 'salt_register_new_controls' );


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

/**
* This hooks into 'customize_register' (available as of WP 3.4) and allows
* you to add new sections and controls to the Theme Customize screen.
* 
* @see add_action('customize_register',$func)
* @param \WP_Customize_Manager $wp_customize
* @since Salt 1.0
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
	
	$wp_customize->add_control( new WP_Customize_ImageSelect_Control( $wp_customize, 'salt_blog_layout', array(
        'label'    => __( 'Blog Layout', 'salt' ),
        'section'  => 'general',
        'settings' => 'salt_blog_layout',
        'choices'  => array (
			'one-col' 		   => $image_folder_url . '1c.png',
			'two-col-left'     => $image_folder_url . '2cl.png',
			'two-col-right'    => $image_folder_url . '2cr.png',
			'three-col-middle' => $image_folder_url . '3cm.png'
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
}
add_action( 'customize_register' , 'salt_customize_register' );