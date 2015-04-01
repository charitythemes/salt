<?php
/**
 * Actions for loading different elements into the website header
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
 * Add a favicon in the browser.
 *
 * Using the Theme Customizer, uses can add their own 16x16px favicon 
 * This is added in the sites header via wp_head
 *
 * @since Salt 1.0
 */
function salt_favicon() {

	$favicon = get_option('salt_custom_favicon');

	if ($favicon) {
		echo '<link rel="shortcut icon" href="' . $favicon .'" type="image/x-icon" />';
	}
}
add_action('wp_head', 'salt_favicon');

/**
 * Insert a header wrapper div tag above the website header
 *
 * @since 1.0
 */
function salt_header_wrapper_insert() {
	echo '<div id="header-wrapper">';
}
add_action('salt_header_above','salt_header_wrapper_insert'); 

/**
 * Insert a container class inside the website header 
 *
 * @since 1.0
 */
function salt_header_inside_above_container() {
	echo '<div class="container">';
}
add_action('salt_header_inside_above','salt_header_inside_above_container'); 

/**
 * Load the logo into the header of the website
 *
 * @since 1.0
 */
function salt_header_logo() {
	?>
	<div class="logo pull-left">
		<a href="<?php echo home_url(); ?>" title="<?php bloginfo('description') ?>">
			<?php if ( $site_logo = get_option('salt_custom_logo') ) { ?>
					<img src="<?php echo $site_logo; ?>" alt="<?php bloginfo('name') ?>" />
			<?php } else { ?>
					<h1><?php bloginfo('name') ?></h1>
					<p class="tagline"><?php echo get_bloginfo ( 'description' ); ?></p>
			<?php } ?>
		</a>
	</div>
	<?php 
}
add_action('salt_header_inside','salt_header_logo');

/**
 * Loads the site navigation into the header
 *
 * @since 1.0
 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
function salt_header_navigation() {
	
	$site_layout = get_theme_mod('salt_layout_type');

	if ($site_layout != 'boxed')
		$class='pull-right';
	?>
	
	<div class="nav-wrapper navbar-collapse <?php echo $class; ?>">
		<?php 
		if ($site_layout == 'boxed') 
			echo '<div class="container">'; ?>		

		<nav role="navigation" id="primary-menu">
			<?php wp_nav_menu (  array (  'container' => 'div', 'items_wrap' => '<ul class="%2$s">%3$s</ul>', 'menu_class' => 'menu', 'theme_location' => 'primary-menu' )); ?>
		</nav>

		<?php 
		if ($site_layout == 'boxed')
			echo '</div>'; ?>
	</div>
	<?php	
}

$site_layout = get_theme_mod('salt_layout_type');

if ($site_layout == 'boxed') {
	add_action('salt_header_below','salt_header_navigation');
} else {
	add_action('salt_header_inside_below','salt_header_navigation');
}

/**
 * Load the mobile navbar toggle button in the header
 *
 * @since 1.0
 */
function salt_header_mobile_menu() {
	?>
	<div class="pull-right navbar-toggle-wrapper visible-xs-block">
		<button type="button" class="navbar-toggle">
        	<i class="fa fa-bars fa-lg"></i>
		</button>
	</div>
	<?php
}
add_action('salt_header_inside','salt_header_mobile_menu'); 

/**
 * Close of the wrapper divs in the header
 *
 * @since 1.0
 */
function salt_header_close_div() {
	echo '</div>';
}
add_action('salt_header_below','salt_header_close_div'); 
add_action('salt_header_inside_below','salt_header_close_div'); 

?>