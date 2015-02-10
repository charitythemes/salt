<?php
/**
 * Actions for arranging the layouts and class names for the whole site
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
 * Outputs all the classes that are added to the site body tag
 * 
 * @since Salt 1.0
 *
 * @return array of classes
 */
function salt_layout_body_class( $classes ) {
	global $post, $layout;
	
	if (is_front_page() && is_home()) { 
		
		$layout = 'two-col-left';
	
	} elseif (is_page()) {
		
		$layout = (get_post_meta($post->ID, '_post_layout', true)) ? get_post_meta($post->ID, '_post_layout', true) : 'two-col-left';

	} elseif (is_single()) {
		
		$layout = 'two-col-left';
		
	}
	
	$classes[] = apply_filters('salt_layout', $layout);

	return $classes;
}
add_filter('body_class','salt_layout_body_class');

/**
 * Adds the left sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.0
 */
function salt_add_left_sidebar() {
	global $post, $_salt_registered_classes, $layout;
	
	if ($layout == 'two-col-right') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('primary');
	}
}
add_action('salt_section_below', 'salt_add_left_sidebar', 10);

/**
 * Adds the right sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.0
 */
function salt_add_right_sidebar() {
	global $post, $_salt_registered_classes, $layout;

	if ($layout == 'two-col-left') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('secondary');
	}
}
add_action('salt_section_below', 'salt_add_right_sidebar', 20);

/**
 * Outputs all the classes that are added to the wrapper tag
 * 
 * @since Salt 1.0
 */
function salt_wrapper_class_output( $classes ) {
	global $_salt_registered_classes;
	
	if($_salt_registered_classes['wrapper']) {
		$classes[] = $_salt_registered_classes['wrapper'];
	}
	
	$classes[] = (get_theme_mod('salt_layout_type')) ? get_theme_mod('salt_layout_type') : 'wide';
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_wrapper_class', 'salt_wrapper_class_output');

/**
 * Outputs all the classes that are added to the header tag
 * 
 * @since Salt 1.0
 */
function salt_header_class_output( $classes ) {
	global $_salt_registered_classes;
	
	if($_salt_registered_classes['header']) {
		$classes[] = $_salt_registered_classes['header'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_header_class', 'salt_header_class_output');

/**
 * Outputs all the classes that are added to the #container div tag
 * 
 * @since Salt 1.0
 */
function salt_container_class_output( $classes ) {
	global $_salt_registered_classes;
	
	if($_salt_registered_classes['container']) {
		$classes[] = $_salt_registered_classes['container'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_container_class', 'salt_container_class_output');

/**
 * Outputs all the classes that are added to the #main div tag
 * 
 * @since Salt 1.0
 */	
function salt_main_class_output( $classes ) {
	global $_salt_registered_classes;
	
	if($_salt_registered_classes['main']) {
		$classes[] = $_salt_registered_classes['main'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_main_class', 'salt_main_class_output');

/**
 * Outputs all the classes that are added to the sites section tag
 * 
 * @since Salt 1.0
 */
function salt_section_class_output( $classes ) {
	global $_salt_registered_classes, $layout;
	
	if('one-col' == $layout && $_salt_registered_classes['main-one-col']) {
		$classes[] = $_salt_registered_classes['main-one-col'];
	} elseif('two-col-right' == $layout && $_salt_registered_classes['main-two-col-right']) {
		$classes[] = $_salt_registered_classes['main-two-col-right'];
	} elseif('two-col-left' == $layout && $_salt_registered_classes['main-two-col-left']) {
		$classes[] = $_salt_registered_classes['main-two-col-left'];
	} elseif('three-col-middle' == $layout && $_salt_registered_classes['main-three-col-middle']) {
		$classes[] = $_salt_registered_classes['main-three-col-middle'];
	} else {
		$classes[] = $_salt_registered_classes['main-one-col'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_section_class', 'salt_section_class_output');

/**
 * Outputs all the classes that are added to the article tag
 * 
 * @since Salt 1.0
 */
function salt_article_class_output( $classes ) {
	global $_salt_registered_classes;
	
	if($_salt_registered_classes['article']) {
		$classes[] = $_salt_registered_classes['article'];
	}

	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_article_class', 'salt_article_class_output');

/**
 * Outputs all the classes that are added to the #primary sidebar tag
 * 
 * @since Salt 1.0
 */
function salt_primary_sidebar_class_output( $classes ) {
	global $_salt_registered_classes, $layout;
	
	if('two-col-right' == $layout && $_salt_registered_classes['primary-two-col-right']) {
		$classes[] = $_salt_registered_classes['primary-two-col-right'];
	} elseif('two-col-left' == $layout && $_salt_registered_classes['primary-two-col-left']) {
		$classes[] = $_salt_registered_classes['primary-two-col-left'];
	} elseif('three-col-middle' == $layout && $_salt_registered_classes['primary-three-col-middle']) {
		$classes[] = $_salt_registered_classes['primary-three-col-middle'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_primary_sidebar_class', 'salt_primary_sidebar_class_output');

/**
 * Outputs all the classes that are added to the #secondary sidebar tag
 * 
 * @since Salt 1.0
 */
function salt_secondary_sidebar_class_output( $classes ) {
	global $_salt_registered_classes, $layout;
	
	if('three-col-middle' == $layout && $_salt_registered_classes['secondary-three-col-middle']) {
		$classes[] = $_salt_registered_classes['secondary-three-col-middle'];
	}
	
	if( $classes ) {
		$output .= 'class="' . implode(' ', $classes) . '"';
		echo $output;
	}
}
add_action('salt_secondary_sidebar_class', 'salt_secondary_sidebar_class_output');

/**
 * Outputs all the classes that are added to the #footer div tag
 * 
 * @since Salt 1.0
 */
if (!function_exists('salt_footer_class_output')) {
	function salt_footer_class_output( $classes ) {
		global $_salt_registered_classes;
		
		if($_salt_registered_classes['footer']) {
			$classes[] = $_salt_registered_classes['footer'];
		}
		
		if( $classes ) {
			$output .= 'class="' . implode(' ', $classes) . '"';
			echo $output;
		}
	}
}
add_action('salt_footer_class', 'salt_footer_class_output');

/**
 * Outputs all the classes that are added to the footer widgets div tag
 * 
 * @since Salt 1.0
 */
if (!function_exists('salt_footer_widgets_class_output')) {
	function salt_footer_widgets_class_output( $classes ) {
		global $_salt_registered_classes;
		
		if($_salt_registered_classes['footer-widgets']) {
			$classes[] = $_salt_registered_classes['footer-widgets'];
		}
		
		if( $classes ) {
			$output .= 'class="' . implode(' ', $classes) . '"';
			echo $output;
		}
	}
}
add_action('salt_footer_widgets_class', 'salt_footer_widgets_class_output');