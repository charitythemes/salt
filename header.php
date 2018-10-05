<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything until after the header closes.
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */
?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5shiv.min.js"></script>
	<![endif]-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php 
	if ($favicon = get_option('salt_custom_favicon')) {
		echo '<link rel="shortcut icon" href="' . $favicon .'" type="image/x-icon" />';
	}
	?>
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php do_action('salt_top'); ?>

	<?php 
	if ( 'stick-right' == get_theme_mod( 'salt_social_position' ) || 
		 'stick-left' == get_theme_mod( 'salt_social_position' ) ) {			
		get_template_part( 'partials/global/parts/social' , 'links' );
	} ?>

	<!-- Page Wrapper -->
	<div id="wrapper">
		
		<?php salt_get_header_template_part(); ?>