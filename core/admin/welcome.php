<?php
/**
 * Welcome Screen
 *
 * Creates a simple welcome screen when the theme is activated or updated.
 * Welcome screen explains the latest updates, and will be changed for each version release.
 * 
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.5.0
 */
 
/**
* Welcome Screen Activate
*
* Set a transient t.
*
* @since 1.5.0
*/ 
function salt_welcome_screen_activate() {

	// Bail if no activation redirect
	if ( get_transient( '_salt_welcome_screen_activation_redirect' ) ) {
		return;
	}
	
	// Delete the redirect transient
	set_transient( '_salt_welcome_screen_activation_redirect', true, 30 );
	
	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}
	
	// Redirect to welcome page
	wp_safe_redirect( add_query_arg( array( 'page' => 'salt-welcome-screen' ), admin_url( '' ) ) );

}
add_action( 'after_switch_theme', 'salt_welcome_screen_activate' );

if ( !function_exists('salt_welcome_screen_pages')) :
/**
* Add Welcome Screen
*
* Adds the welcome screen to the dashboard menu section.
*
* @since 1.5.0
*/
function salt_welcome_screen_pages() {
	add_dashboard_page(
		'Welcome To Salt Theme',
		'Welcome To Salt Theme',
		'read',
		'salt-welcome-screen',
		'salt_welcome_screen_content'
	);
}
add_action('admin_menu', 'salt_welcome_screen_pages');
endif;

if ( !function_exists('salt_welcome_screen_content')) :
/**
* Welcome Screen Content
*
* The text and pictures to introduce the latest Salt Theme version.
*
* @since 1.5.0
*/
function salt_welcome_screen_content() {
?>
	<div class="wrap about-wrap">
		<h1><?php printf( __( 'Salt Theme v%s' ), SALT_VERSION ); ?><span class="ct_logo"><a href="https://www.charitythemes.org" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/core/assets/img/logo_ct_2x_pink.png" alt="" style="margin-top: 10px;
    margin-left: 10px;" /></a></span></h1>

		<div class="about-text"><?php _e( 'Thank you for using Salt Theme!' ); ?></div>

		<h2 class="nav-tab-wrapper">
			<a href="?page=welcome-screen-about" class="nav-tab nav-tab-active"><?php _e( 'What&#8217;s New' ); ?></a>
		</h2>

		<div class="headline-feature feature-section one-col" style="text-align: center;">
			<h2><?php _e( 'New Feature: Blog Posts Slider' ); ?></h2>
			<div class="media-container">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/screenshot.png" />
			</div>
		</div>

		<hr />

		<div class="feature-section two-col">
			<div class="col">
				<div class="media-container">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/core/assets/img/post-slider-background-options.png" alt="Post%20Slider%20Background%20Options" width="777" height="402" />
				</div>
			</div>
			<div class="col">
				<h3><?php _e( 'Background Images' ); ?></h3>
				<p><?php _e( 'When you add or edit a blog post, you will see a new control panel (shown on left). This is used to add either a background image or color to this posts slide. Giving you full control to make each slide look unique!' ); ?></p>
			</div>
		</div>

		<hr />

		<div class="feature-section two-col">
			<div class="col">
				<div class="media-container" style="text-align: center;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/core/assets/img/slider-customizer.png" alt="slider-customizer" width="283" height="322" />
				</div>
			</div>
			<div class="col">
				<h3><?php _e( 'Customize your slider' ); ?></h3>
				<p><?php _e( 'From the WordPress customizer, you can change a few of the slider settings, such as the animation speed, whether to show the controls or not and the direction your images slider.' ); ?></p>
			</div>
		</div>

		<hr />

		<div class="feature-section two-col">
			<div class="col">
				<div class="media-container" style="text-align: center;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/core/assets/img/layout-customizer.png" alt="" />
				</div>
			</div>
			<div class="col">
				<h3><?php _e( '2 different layouts' ); ?></h3>
				<p><?php _e( 'Swapping between the Wide and Boxed layouts for your blog, also changes the arrangement of the slider.' ); ?></p>
			</div>
		</div>

		<hr />

		<div class="changelog">
			<h3><?php _e( 'We Need Your Help!' ); ?></h3>

			<div class="feature-section under-the-hood three-col">
				<div class="col">
					<h4><?php _e( 'Suggest a feature!' ); ?></h4>
					<p><?php echo sprintf( __( 'Do you have an idea for a great feature on our next version of Salt Theme? Or perhaps there is something you don\'t like and want us to improve, <a href="%s" target="_blank">please let us know</a>, we\'d love to hear from you!', 'salt'), 'https://www.facebook.com/charitythemes.org/'); ?></p>
				</div>
				<div class="col">
					<h4><?php _e( 'Like Salt Theme? Give us 5 stars!' ); ?></h4>
					<p><?php echo sprintf(__( 'We have over a 1000 active users of Salt Theme, which is pretty great! But no one has rated us yet, if you can spare a moment, please rate us on <a href="%s" target="_blank">wordpress.org</a>.' ), 'https://wordpress.org/themes/salt/'); ?></p>
				</div>
				<div class="col">
					<h4><?php _e( 'Like us on Facebook' ); ?></h4>
					<p><?php echo sprintf(__( 'Get our latest updates by <a href="%s" target="_blank">following us on Facebook!</a>' ), 'https://www.facebook.com/charitythemes.org/'); ?></p>
				</div>
			</div>

			<div class="return-to-dashboard">
				<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
					<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
						<?php is_multisite() ? _e( 'Return to Updates' ) : _e( 'Return to Dashboard &rarr; Updates' ); ?>
					</a> |
				<?php endif; ?>
				<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? _e( 'Go to Dashboard &rarr; Home' ) : _e( 'Go to Dashboard' ); ?></a>
			</div>

		</div>
	</div>
  <?php
}

endif;

if ( !function_exists('salt_welcome_screen_remove_menus')) :
/**
* Remove From Menus
*
* Remove the menu item from the dashboard menu.
*
* @since 1.5.0
*/
function salt_welcome_screen_remove_menus() {
	remove_submenu_page( 'index.php', 'salt-welcome-screen' );
}
add_action( 'admin_head', 'salt_welcome_screen_remove_menus' );
endif;
?>