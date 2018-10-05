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
	global $post, $layout, $template;
	
	if ( is_page_template( 'template-right-sidebar.php' ) ) {
		
		$layout = 'two-col-left';
		
	} elseif ( is_page_template( 'template-left-sidebar.php' ) ) {
		
		$layout = 'two-col-right';
		
	} elseif ( is_page_template( 'template-full-width.php' ) ) {
		
		$layout = 'one-col';
		
	} elseif ( is_page_template( 'template-both-sidebar.php' ) ) {
		
		$layout = 'three-col-middle';
		
	} elseif ( get_theme_mod('salt_page_layout') && is_page() ) {
		
		$layout = get_theme_mod('salt_page_layout');
		
	} elseif ( get_theme_mod('salt_blog_layout') && ( is_home() || is_archive() || is_search() ) ) { 
				
		$layout = get_theme_mod('salt_blog_layout');
		
	} elseif ( get_theme_mod('salt_post_layout') && is_single() && get_post_type() == 'post' ) {	

		$layout = get_theme_mod('salt_post_layout');
			
	} else {
		
		$layout = 'two-col-left';
				
	}
	
	if ( get_theme_mod('salt_layout_type') )
		$classes[] = get_theme_mod('salt_layout_type');
	else
		$classes[] = 'wide';
		
	/**
	 * Filter the layout for specific posts or pages.
	 *
	 * @since Salt 1.6.0
	 *
	 * @param string $layout Classes added.
	 */
	$layout = apply_filters( 'salt_layout', $layout );
	
	$classes[] = $layout;
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
 * Display the classes for the post div.
 *
 * @since 1.6.0
 *
 * @param string|array $class   One or more classes to add to the class list.
 */
function salt_section_class(  $class = '' ) {

	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', salt_get_section_class( $class ) ) . '"';
}

/**
 * Outputs all the classes that are added to the sites section tag
 * 
 * @since Salt 1.6.0
 */
function salt_get_section_class( $class = ''  ) {
	global $layout;
	
	$classes = array();
	
	if ( 'one-col' == $layout )
		$classes[] = 'col-sm-12';

	if ( 'two-col-right' == $layout ) {
		$classes[] = 'col-sm-8';
		$classes[] = 'col-sm-push-4';
	}

	if ( 'two-col-left' == $layout )
		$classes[] = 'col-sm-8';

	if ( 'three-col-middle' == $layout ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-sm-push-3';
	} 
		
	if ( ! isset( $layout ) )
		$classes[] = 'col-sm-12';
	
	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'salt_section_class', $classes, $class );
	
	return array_unique( $classes );
}

/**
 * Display the classes for the post div.
 *
 * @since 1.6.0
 *
 * @param string|array $class   One or more classes to add to the class list.
 */
function salt_primary_sidebar_class(  $class = '' ) {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', salt_get_primary_sidebar_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the post div as an array.
 *
 * The class names are many. If the post is a sticky, then the 'sticky'
 *
 * @since 1.6.0
 *
 * @param string|array $class   One or more classes to add to the class list.
 * @return array Array of classes.
 */
function salt_get_primary_sidebar_class( $class = '' ) {
 	global $layout;

	$classes = array();
	
	$classes[] = 'widget-area';
	
	if ( 'two-col-right' == $layout ) {
		$classes[] = 'col-sm-4';	
		$classes[] = 'col-sm-pull-8';
	}
	
	if ( 'two-col-left' == $layout )
		$classes[] = 'col-sm-4';	
	
	if ( 'three-col-middle' == $layout ) {
		$classes[] = 'col-sm-3';
		$classes[] = 'col-sm-pull-6';
	}

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'salt_primary_sidebar_class', $classes, $class );
	
	return array_unique( $classes );
}

/**
 * Display the classes for the post div.
 *
 * @since 1.6.0
 *
 * @param string|array $class   One or more classes to add to the class list.
 */
function salt_secondary_sidebar_class(  $class = '' ) {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', salt_get_secondary_sidebar_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the post div as an array.
 *
 * The class names are many. If the post is a sticky, then the 'sticky'
 *
 * @since 1.6.0
 *
 * @param string|array $class   One or more classes to add to the class list.
 * @return array Array of classes.
 */
function salt_get_secondary_sidebar_class( $class = '' ) {
	global $layout;
	
	$classes = array();
	
	$classes[] = 'widget-area';
	
	if ( 'three-col-middle' == $layout )
		$classes[] = 'col-sm-3';
	
	$classes = array_map( 'esc_attr', $classes );
	
	$classes = apply_filters( 'salt_secondary_sidebar_class', $classes, $class );
	
	return array_unique( $classes );
}