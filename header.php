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
	<title><?php wp_title(); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5shiv.min.js"></script>
	<![endif]-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php do_action('salt_top'); ?>

	<div id="wrapper" <?php do_action('salt_wrapper_class'); ?>>

		<?php do_action('salt_header_above'); ?>
		
		<header id="header" <?php do_action('salt_header_class'); ?>>
	
			<?php do_action('salt_header_inside_above'); ?>
	
			<?php do_action('salt_header_inside'); ?>
	
			<?php do_action('salt_header_inside_below'); ?>
	
		</header>

		<?php do_action('salt_header_below'); ?>
		
		<?php do_action('salt_header_outside_below'); ?>