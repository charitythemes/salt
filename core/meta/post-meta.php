<?php
/**
 * Add Post Meta Box UI
 *
 * Adds meta box UI onto the edit / add new post UI. This is used for posts that display in the slider.
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.5.0
 */
	
if ( ! function_exists( 'salt_post_metabox' ) ) :
function salt_post_metabox() {	

	$post_metabox = new Salt_Background_Meta_Box( 'post' );	
	$post_metabox->settings['title'] = __('Post Slider Background', 'salt');
}
endif;
add_action( 'admin_init', 'salt_post_metabox' );