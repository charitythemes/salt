<?php
/**
 * Template parts functions
 *
 * @package Salt
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.6.5
 */

if (!function_exists('salt_get_header_template_part')) :
/**
 * Switch between different headers based on the choice in the customizer.
 *
 * @link https://codex.wordpress.org/Function_Reference/add_theme_support
 * @since 1.6.5
 */
function salt_get_header_template_part() {
	
	$template = get_theme_mod('salt_layout_type');
	
	switch ($template) {
		
		case 'wide':
			get_template_part('partials/header/wide');
			break;
		
		case 'boxed':
			get_template_part('partials/header/boxed');
			break;
		
		default:
			get_template_part('partials/header/wide');
	}
}
endif;