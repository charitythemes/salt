<?php
/**
 * Woocommerce Actions
 *
 * @package Salt
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.6.7
 */

if ( class_exists( 'woocommerce' ) ) :
	
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
   add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
   add_action( 'woocommerce_shop_loop_item_title', 'salt_template_loop_product_title', 10 );
   
/**
 * Remove Header On Woocommerce Store Page
 */
function salt_remove_woo_shop_header() {
	if ( is_shop() || is_front_page() )
		return false;
	else
		return true;
}
add_filter( 'salt_show_page_title', 'salt_remove_woo_shop_header' );

/**
 * Add padding on store 
 */
function salt_shop_loop_before_title() {
	get_template_part('partials/woocommerce/product/before', 'title');
}
add_action( 'woocommerce_shop_loop_item_title', 'salt_shop_loop_before_title', 5 );

/**
 * Add padding on store 
 */
function salt_shop_loop_category() {
	get_template_part('partials/woocommerce/product/category', 'title');
}
add_action( 'woocommerce_shop_loop_item_title', 'salt_shop_loop_category', 15 );


/**
 * Add padding on store 
 */
function salt_shop_loop_after_title() {
	get_template_part('partials/woocommerce/product/before', 'after');
}
add_action( 'woocommerce_after_shop_loop_item', 'salt_shop_loop_after_title', 20 );

/**
 * Remove the add to cart text
 */
function salt_cart_button_text() {
	return false;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'salt_cart_button_text' );

/**
 * Remove sidebar from cart, checkout, account and single product pages 
 */
function salt_woocommerce_layout_override( $layout ) {
	
	if ( is_cart() || is_checkout() || is_account_page() || is_product() )
		return 'one-col';
	
	return $layout;
}
add_filter( 'salt_layout', 'salt_woocommerce_layout_override' );
 
/**
 * Show the product title in the product loop. 
 */
function salt_template_loop_product_title() {
	echo '<h2 class="woocommerce-loop-product__title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h2>';
}

/**
 * Change store main section width
 */
function salt_section_class_extend( $classes ) {
	global $layout;

	if ( 'two-col-right' == $layout && ( is_shop()||is_product_category() ) ) {
		$classes = array();
		$classes[] = 'col-sm-9';
		$classes[] = 'col-sm-push-3';
	}

	return $classes;
}
add_filter( 'salt_section_class', 'salt_section_class_extend' );

/**
 * Change store sidebar width
 */
function salt_primary_sidebar_class_extend( $classes ) {
	global $layout;

	if ( 'two-col-right' == $layout && ( is_shop()||is_product_category() ) ) {
		$classes = array();
		$classes[] = 'widget-area';
		$classes[] = 'col-sm-3';	
		$classes[] = 'col-sm-pull-9';
	}

	return $classes;
}
add_filter( 'salt_primary_sidebar_class', 'salt_primary_sidebar_class_extend' );

/**
 * Add body class if store notice is showing
 * 
 * @link https://developer.wordpress.org/reference/functions/body_class/
 */
function salt_store_notice_body_class( $classes ){
	if ( ! is_store_notice_showing() || ( isset( $_COOKIE[ 'store_notice' ] ) && $_COOKIE[ 'store_notice' ] == 'hidden' ) ) {
		return $classes;
	}
	$classes[] = 'woocommerce-store-notice-enabled';
	return $classes;
}
add_filter( 'body_class', 'salt_store_notice_body_class', 10, 1 );

if ( ! function_exists('salt_add_search_nav_item')) :
/**
 * Add search icon menu item
 *
 * @since 1.6.7
 */
function salt_add_search_nav_item( $items, $args ) {
	if ( $args->theme_location == 'primary-menu' ) {
		
		if ( get_option('salt_disable_search') == true )
			return $items;

		ob_start();
		get_template_part( 'partials/global/parts/menu', 'search' );
		$items .= ob_get_contents();
		ob_end_clean();
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'salt_add_search_nav_item', 10, 2 );
endif;

if ( ! function_exists('salt_add_cart_nav_item')) :
/**
 * Add a shopping cart menu item
 *
 * @since 1.6.7
 */
function salt_add_cart_nav_item( $items, $args ) {
	if ( $args->theme_location == 'primary-menu' ) {

		if ( get_option('salt_disable_cart') == true )
			return $items;

		ob_start();
		get_template_part( 'partials/global/parts/menu', 'cart' );
		$items .= ob_get_contents();
		ob_end_clean();
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'salt_add_cart_nav_item', 20, 2 );
endif;

if ( ! function_exists('salt_header_search_bar')) :
/**
 * Add the search bar under the header
 *
 * @since 1.6.7
 */
function salt_header_search_bar() {	
	if ( get_option('salt_disable_search') == true )
		return false;

	get_template_part( 'partials/global/parts/menu-search', 'bar' );
}
add_action( 'salt_header_outside_below', 'salt_header_search_bar' );
endif;

/**
 * Add customizer settings to the Woocommerce tab ti disable the search and shopping cart options
 */
function salt_customize_woocommerce( $wp_customize ) {
   
    $wp_customize->add_section( 
    	'salt_theme_settings', 
    	array(
			'title' 		=> __('Theme Settings', 'salt'),
			'priority' 		=> 5,
			'panel' 		=> 'woocommerce'
		)
	);
	
	$wp_customize->add_setting(
		'salt_disable_search', 
		array(
	    	'default'       	=> '',
	        'capability'    	=> 'edit_theme_options',
	        'type'          	=> 'option',
	        'sanitize_callback' => 'salt_sanitize_checkbox'
		)
	);
	
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_disable_search', array(
        'label'    		=> __('Disable the search option in the header', 'salt'),
        'section'  		=> 'salt_theme_settings',
        'settings' 		=> 'salt_disable_search',
        'type'          => 'checkbox'
    )));

	$wp_customize->add_setting('salt_disable_cart', array(
    	'default'       	=> '',
        'capability'    	=> 'edit_theme_options',
        'type'          	=> 'option',
        'sanitize_callback' => 'salt_sanitize_checkbox'
    ));
	
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'salt_disable_cart', array(
        'label'    		=> __('Disable the shopping cart link in the header', 'salt'),
        'section'  		=> 'salt_theme_settings',
        'settings' 		=> 'salt_disable_cart',
        'type'          => 'checkbox'
    )));	
}
add_action( 'customize_register' , 'salt_customize_woocommerce' );
 
endif; // end class_exists( 'woocommerce' )