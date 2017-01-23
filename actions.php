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

if (!function_exists('salt_front_page_slider')) :
/**
* Add slider onto the front page or index page
*
* Includes the slider using a template part. Override this in a Child theme.
* The slider only show on the index page (blog or page) & when the slider is activated
*
* @since 1.5.0
*/ 
function salt_front_page_slider() {

	if ( is_front_page() && get_theme_mod( 'salt_show_slider' ) ) {
		
		get_template_part( 'slider');
	
	}
}
endif;
add_action( 'salt_container_above', 'salt_front_page_slider' );

if (!function_exists('salt_slider_query')) :
/**
 * Slider Query
 * 
 * This query grabs the posts depending on the options selected in the Customizer.
 *
 * @since 1.5.0
 */
function salt_slider_query( $query ) {
	
	// Only run if we are on the main loop.
	if ( !$query->is_main_query() )
		return;
	
	// Only run the query if the slider is turned on and it's the blog page.
	if ( ! is_home() || ! get_theme_mod( 'salt_show_slider' ) )
		return;
		
	// Use a global variable to hold the query results.
	global $slider_query;
	
	// Limit the number of slides we show by 5, but can be overridden in the Customizer.
	$limit = ( get_option( 'salt_slider_num_of_posts' ) ) ? get_option( 'salt_slider_num_of_posts' ) : 5;

	// Basic arguments
	$args = array( 
		'post_type' 		  => 'post',
		'posts_per_page' 	  => $limit, 
		'paged' 		 	  => 1,
 		'ignore_sticky_posts' => 1
	);

	// If set in Customizer use sticky posts only
	if ( get_theme_mod( 'salt_slider_display_type' ) == 'sticky' ) {
		$sticky = get_option( 'sticky_posts' );
		$args['post__in'] = $sticky;
	
	// If set in Customizer use posts with a certain tag.
	} elseif ( get_theme_mod( 'salt_slider_display_type' ) == 'tag' ) {
		$tags = get_option( 'salt_slider_posts_tag' );
		if ( $tags != '' ) {
			$args['tag'] = $tags;
		}
	}
	
	// Store the query results in a global variable.
	$slider_query = new WP_Query( $args );
}
add_action( 'pre_get_posts', 'salt_slider_query' ); 
endif;

if (!function_exists('salt_exclude_slider_posts')) :
/**
 * Exclude Slider Query
 *
 * If in the customizer the user chooses to exclude slider posts from the main blog. 
 * This action removes those posts from the main post query.
 * 
 * @since 1.5.0
 */
function salt_exclude_slider_posts( $query ) {
	
	// Only run if we are on the main loop.
	if ( ! $query->is_main_query() )
		return;

	// Only run the query if the slider is turned on and it's the blog page.
	if ( ! is_home() || ! get_theme_mod( 'salt_show_slider' ) )
		return;

	// Only proceed if the user is not asking to repeat posts in the main loop.
	if ( get_theme_mod( 'salt_slider_posts_in_loop' ) )
		return;

	// Get our query through a global variable.
	global $slider_query;
		
	// Pluck the ID's out of the main slider query.
	$post_ids = wp_list_pluck( $slider_query->posts, 'ID' ); 
	
	// Exclude posts ID's from the main loop.
	$query->set( 'post__not_in', $post_ids );
}
add_action( 'pre_get_posts', 'salt_exclude_slider_posts' );
endif;

if (!function_exists('salt_hide_page_title')) :
/**
 * Hide Page Title
 *
 * Hide the page title on the front page.
 * 
 * @since 1.6.0
 */
function salt_hide_page_title() {
	
	if ( is_front_page() )
		return false;
		
	return true;
}
add_filter( 'salt_show_page_title', 'salt_hide_page_title', 5 );
endif;

if ( ! function_exists('salt_front_page_blog')) :
/**
 * Show 3 blog posts to the homepage
 *
 * @since 1.6.0
 */
function salt_front_page_blog() {
	
	global $post;
	
	if ( is_front_page() && get_option( 'show_on_front')!='posts' ) {
		?>
		<div class="blog-grid-wrapper">
			<div class="row">
				<?php 
				global $slider_query;
				
				$query_args = array(
					'post_type' 	 => 'post',
					'posts_per_page' => 3,
					'post__not_in'   => get_option('sticky_posts')
				);
				
				// If the admin is not asking to repeat posts in the main loop, pluck them out.
				if ( ! get_theme_mod( 'salt_slider_posts_in_loop' ) )	{				
					$post_ids = wp_list_pluck( $slider_query->posts, 'ID' );
					$query_args['post__not_in'] = $post_ids;
				}
				
				// Query the blog posts.
				$the_query = new WP_Query( $query_args );

				// Default grid arguments
				$args = array(
					'item'			=> 'div',
					'total_posts'	=> sizeof($the_query->posts)
				);
				
				$cols = 3;
				$span = 'col-sm-4';
				
				// The Loop
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						?>
						<div class="<?php echo $span; ?>">
						
						<?php $the_query->the_post();
						
						get_template_part( 'partials/content' ); ?>
					
						</div>
						<?php
					}
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	}	
}
add_action( 'salt_section_inside_below', 'salt_front_page_blog', 15 );
endif;