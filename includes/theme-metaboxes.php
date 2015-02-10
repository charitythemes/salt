<?php
/**
 * Defines default metaboxes
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 */

if(!function_exists('additional_metaboxes')) :
/**
 * Add additional metaboxes in wp-admin
 *
 * @since Salt 1.0
 */
function additional_metaboxes() {

	// The parent theme image folder url
	$image_folder_url = get_template_directory_uri() . '/core/assets/images/';
	
	$default_metaboxes = array(
		array(
			'id' => 'page_metaboxes',
			'title' => __('Page Options', 'salt'),
			'pages' => array('page'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array (
					'id' => '_post_layout',
					'name' => __( 'Layout', 'salt' ),
					'type' => 'images',
					'desc' => __( 'Select a specific layout for this post/page. Overrides default site layout.', 'salt' ),
					'options' => array (
						array('name' => '', 'value' => $image_folder_url . 'layout-off.png'),
						array('name' => 'one-col', 'value' => $image_folder_url . '1c.png'),
						array('name' => 'two-col-left', 'value' => $image_folder_url . '2cl.png'),
						array('name' => 'two-col-right', 'value' => $image_folder_url . '2cr.png'),
						array('name' => 'three-col-middle', 'value' => $image_folder_url . '3cm.png'),
					)
				)
			)
		),
	);
	
	$metaboxes = apply_filters('salt_additional_metaboxes', $default_metaboxes);

	if($metaboxes) {
		foreach($metaboxes as $metabox) {
			new cmb_Meta_Box( $metabox );
		}
	}
}
endif;

add_action('init', 'additional_metaboxes');