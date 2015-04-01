<?php
/**
 * This is where add the basic settings for this theme.
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */
 
/**
 * Adding theme support for a few useful things.
 *
 * @link https://codex.wordpress.org/Function_Reference/add_theme_support
 * @since 1.0
 */
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

/*
 * Let WordPress manage the document title.
 * By adding theme support, we declare that this theme does not use a
 * hard-coded <title> tag in the document head, and expect WordPress to
 * provide it for us.
 */
add_theme_support( 'title-tag' );

/*
 * Switch default core markup for search form, comment form, and comments
 * to output valid HTML5.
 */
add_theme_support( 'html5', array(
	'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
) );

/*
 * Enable support for Post Formats. Probably coming in v1.1
 *
 * @link https://codex.wordpress.org/Post_Formats
 */
/*
add_theme_support( 'post-formats', array(
	'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
) );
*/

/**
 * This theme uses wp_nav_menu() for the main menu.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
register_nav_menus( array(
	'primary-menu' => __('Primary Menu', 'salt'),
) );

/**
 * If it is not set already, we should set the content width.
 *
 * @link http://codex.wordpress.org/Content_Width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 960;
}

/**
 * What's a WordPress theme without stylesheets.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function salt_register_styles() {

	wp_enqueue_style( 'bootstrap' 		, get_template_directory_uri() . '/css/bootstrap.min.css', false, '3.2.0');
	wp_enqueue_style( 'fontawesome'		, get_template_directory_uri() . '/css/font-awesome.min.css', false, '4.2.0');
	wp_enqueue_style( 'social' 			, get_template_directory_uri() . '/css/social.css', 'false', '1.0');
	wp_enqueue_style( 'main'	 		, get_template_directory_uri() . '/css/main.css', 'false', '1.0');
}

add_action( 'wp_enqueue_scripts', 'salt_register_styles' );

/**
 * Most sites these days have some jQuery including this theme.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */
function salt_register_scripts() {
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/respond.min.js', array('jquery'), '1.4.2', true );
	wp_enqueue_script( 'global', get_template_directory_uri() . '/js/global.js', array('jquery'), '1.0.0', true );

	if ( is_singular() ) 
		wp_enqueue_script( 'comment-reply' );		
}

add_action( 'wp_enqueue_scripts', 'salt_register_scripts' );

/**
 * These are the main classes used throughout theme on key elements.
 */
global $_salt_registered_classes;
$_salt_registered_classes = array(
	//Add extra classes to the main ID's - leave blank for none
	'wrapper' 						=> '',
	'header' 						=> '',
	'container' 					=> 'container',
	'main' 							=> 'row',
	'footer' 						=> 'container',
	'footer-widgets'       			=> '',
	'article' 						=> '',
	//Add the classes for the main column, depending on the layout options
	'main-one-col' 					=> 'col-sm-12',
	'main-two-col-left' 			=> 'col-sm-8',
	'main-two-col-right'    		=> 'col-sm-8 col-sm-push-4',
	'main-three-col-middle' 		=> 'col-sm-6 col-sm-push-3',
	//Add the classes for the primary sidebar, depending on the layout options
	'primary-two-col-left'  		=> 'widget-area col-sm-4',
	'primary-two-col-right'     	=> 'widget-area col-sm-4 col-sm-pull-8',
	'primary-three-col-middle'  	=> 'widget-area col-sm-3 col-sm-pull-6',
	//Add the classes for the secondary sidebar, depending on the layout options
	'secondary-three-col-middle'	=> 'widget-area col-sm-3'
);