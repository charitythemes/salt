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

	wp_enqueue_style( 'bootstrap' 		, get_template_directory_uri() . '/core/inc/bs/bootstrap.min.css', false, '3.2.0');
	wp_enqueue_style( 'fontawesome'		, get_template_directory_uri() . '/core/inc/fontawesome/css/font-awesome.min.css', false, '4.2.0');
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
	wp_enqueue_script( 'global', get_template_directory_uri() . '/js/global.min.js', array('jquery'), '1.0.0', true );

	if ( is_singular() ) 
		wp_enqueue_script( 'comment-reply' );		
}

add_action( 'wp_enqueue_scripts', 'salt_register_scripts' );

/**
 * These are the main classes used throughout theme on key elements.
 */
salt_register_classes( array(
	//Add extra classes to the main ID's - leave blank for none
	'wrapper' 						=> '',
	'header' 						=> '',
	'container' 					=> 'container',
	'main' 							=> 'row',
	'footer' 						=> 'container',
	'footer-widgets'       			=> '',
	'article' 						=> '',
	//Add the classes for the main column, depending on the layout options
	'main-one-col' 					=> 'col-md-12',
	'main-two-col-left' 			=> 'col-md-8',
	'main-two-col-right'    		=> 'col-md-8 col-md-push-4',
	'main-three-col-middle' 		=> 'col-md-6 col-md-push-3',
	//Add the classes for the primary sidebar, depending on the layout options
	'primary-two-col-left'  		=> 'widget-area col-md-4',
	'primary-two-col-right'     	=> 'widget-area col-md-4 col-md-pull-8',
	'primary-three-col-middle'  	=> 'widget-area col-md-3 col-md-pull-6',
	//Add the classes for the secondary sidebar, depending on the layout options
	'secondary-three-col-middle'	=> 'widget-area col-md-3'
) );

/**
 * These are the various options for the social media icons.
 */
salt_register_social_connect( array(
	'twitter'		=> __('Follow us on Twitter', 'salt'),
	'facebook'		=> __('Connect on Facebook', 'salt'),
	'google-plus'	=> __('Watch on Google+', 'salt'),
	'linkedin'		=> __('Connect on Linkedin', 'salt'),
	'pinterest'		=> __('Follow us on Pinterest', 'salt'),
	'flickr'		=> __('Follow us on Flickr', 'salt'),
	'rss'			=> __('RSS Feed', 'salt'),
	'vimeo'			=> __('Follow us on Vimeo', 'salt'),
	'bebo'			=> __('Follow us on BEBO', 'salt'),
	'github'		=> __('Fork us on Github', 'salt'),
	'picasa'		=> __('See photos on Picasa', 'salt'),
	'skype'			=> __('Skype us', 'salt'),
	'youtube'		=> __('Watch us on YouTube', 'salt'),
	'dribbble'		=> __('Connect on Dribbble', 'salt'),
	'zerply'		=> __('Connect on Zerply', 'salt'),
	'wikipedia'		=> __('Learn more', 'salt'),
	'stumbleupon'	=> __('Stumbleupon us', 'salt'),
	'grooveshark'	=> __('Follow us on Grooveshark', 'salt'),
	'digg'			=> __('Digg us', 'salt'),
	'behance'		=> __('Check us out on Behance', 'salt'),
	'technoratie'	=> __('Follow us on Technoratie', 'salt'),
	'blogger'		=> __('Follow us on Blogger', 'salt'),
	'tumblr'		=> __('Follow us on Tumblr', 'salt'),
	'dropbox'		=> __('Check our our Dropbox', 'salt'),
	'weibo'			=> __('Follow us on Weibo', 'salt'),
	'wechat'		=> __('Follow us on Wechat', 'salt'),
) );