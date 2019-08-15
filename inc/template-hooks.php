<?php
/**
 * Grill Content Wrappers.
 *
 * @see grill_output_content_wrapper()
 * @see grill_output_content_wrapper_end()
 */
add_action( 'grill_before_main_content', 'salt_output_content_wrapper', 20 );
add_action( 'grill_after_main_content',  'salt_output_content_wrapper_end', 5 );
add_action( 'grill_after_main_content', 'salt_add_left_sidebar_extend', 8 );
add_action( 'grill_after_main_content',  'salt_add_right_sidebar_extend', 8 );

/**
 * Woocommerce container, row and content wrappers
 *
 * Used on shop, category, tag pages (not single product)
 * 
 * @since Salt 1.6.7
 */
add_action( 'woocommerce_before_main_content', 'salt_output_container_wrapper', 5 );
add_action( 'woocommerce_after_main_content',  'salt_output_container_wrapper_end', 25 );

add_action( 'woocommerce_before_main_content', 'salt_output_row_wrapper', 10 );
add_action( 'woocommerce_after_main_content',  'salt_output_row_wrapper_end', 20 );

add_action( 'woocommerce_before_main_content', 'salt_output_content_wrapper', 15 );
add_action( 'woocommerce_after_main_content',  'salt_output_content_wrapper_end', 10 );

add_action( 'woocommerce_after_main_content', 'salt_add_left_sidebar_extend', 15 );
add_action( 'woocommerce_after_main_content',  'salt_add_right_sidebar_extend', 15 );

/**
 * Add the tabs, display, related product full width below product details
 *
 * @since Salt 1.6.7
 */
add_action( 'woocommerce_sidebar', 'salt_output_single_product_bottom', 10 );

function salt_output_single_product_bottom() {
	if ( is_product() )
		get_template_part( 'woocommerce/content-single-product', 'bottom' );
}

/**
 * Remove Woocommerce sidebar
 * 
 * @since Salt 1.6.7
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Wrappers
 * 
 * @since Salt 1.6.5
 */
function salt_output_container_wrapper() {
	?><div id="container" class="container" role="container"><?php
}

function salt_output_container_wrapper_end() {
	?></div><?php
}

function salt_output_row_wrapper() {
	?><main id="main" class="row" role="main"><?php
}

function salt_output_row_wrapper_end() {
	?></main><?php
}

function salt_output_content_wrapper() {
	?><section <?php salt_section_class(); ?>><?php
}

function salt_output_content_wrapper_end() {
	?></section><?php
}

/**
 * Adds the left sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.6.7
 */
function salt_add_left_sidebar_extend() {
	global $layout;
	
	if (($layout == 'two-col-right'||$layout == 'three-col-middle') && (is_shop()||is_product_category()||is_product_tag()||is_product())) {
		get_sidebar('store');
	} elseif ($layout == 'two-col-right') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('primary');
	}
}

/**
 * Adds the right sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.6.7
 */
function salt_add_right_sidebar_extend() {
	global $layout;
	
	if ($layout == 'two-col-left' && (is_shop()||is_product_category()||is_product_tag()||is_product())) {
		get_sidebar('store');
	} elseif ($layout == 'two-col-left') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('secondary');
	}
}