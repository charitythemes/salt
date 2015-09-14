<?php
/**
 * General Theme Actions
 *
 * Actions are called with the function do_action().
 * 
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference
 * 
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.3.0
 */

if (!function_exists('salt_page_featured_image')) :
/**
 * Adds a featured image on pages.
 *
 * @since 1.3.0
 */
function salt_page_featured_image() {
	global $post;
	
	// If this post has a thumbnail and we are looking at a page.
	if ( has_post_thumbnail() && is_page() ) {
		
		// Get the thumbnail data.
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID ); 
		$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		$post_thumbnail_data = get_post( $post_thumbnail_id );
		?>
		<div class="banner-cover-wrapper cover-image" <?php echo 'style="background-image: url('.$post_thumbnail[0].'); height: '.$post_thumbnail[2].'px;"'; ?>>
			
			<?php 
			// Additional action for adding content over the featured image.
			do_action( 'salt_featured_image_content' ); ?>
			
			<?php 
			// If this image has a title or an excerpt we can display that over the image.	
			if ( $post_thumbnail_data->post_title || $post_thumbnail_data->post_excerpt ) { ?>
			<div class="darken">
				<div class="copy-container">
				<?php 
				// Show the title if it exists.
				if ( $post_thumbnail_data->post_title ) {
					echo '<h1>'.$post_thumbnail_data->post_title.'</h1>';
				}	
				// Show the excerpt if it exists.
				if ( $post_thumbnail_data->post_excerpt ) {
					echo '<p class="excerpt">'.$post_thumbnail_data->post_excerpt.'</p>';
				}	
				?>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}
add_action( 'salt_container_above', 'salt_page_featured_image' ); 
endif;
