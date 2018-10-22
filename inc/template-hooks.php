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

function salt_output_content_wrapper() {
?><section <?php salt_section_class(); ?>><?php
}
function salt_output_content_wrapper_end() {
?></section><?php
}

/**
 * Adds the left sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.7.0
 */
function salt_add_left_sidebar_extend() {
	global $layout;
	if ($layout == 'two-col-right') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('primary');
	}
}

/**
 * Adds the right sidebar to the site for 2 and 3 column layouts
 * 
 * @since Salt 1.7.0
 */
function salt_add_right_sidebar_extend() {
	global $layout;
	if ($layout == 'two-col-left') {
		get_sidebar('primary');
	} elseif ($layout == 'three-col-middle') {
		get_sidebar('secondary');
	}
}