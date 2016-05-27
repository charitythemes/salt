<?php
/**
 * Post helper functions
 *
 * This file is used to display post elements, from meta to media, to galleries, to in-post pagination, 
 * all post-related functions sit in this file. 
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0.0
 */

if ( ! function_exists( 'salt_author_meta' ) ) :
/**
 * Displays the authors meta information.
 *
 * Add an about the author block, generally used on the single post page.
 *
 * @since Salt 1.0.0
 */
function salt_author_meta() {

 	global $post;
 	
 	// Allows the users to hide the about author text from the Customizer.
 	if ( get_theme_mod('salt_blog_about_author') == '1' ) 
 		return false;
 	?>
	<div class="author-meta vcard">
	
		<h2 class="author-heading"><?php _e('About the author', 'salt'); ?></h2>
		
		<?php if ( get_option( 'show_avatars' ) ) : ?>
		<div class="author-avatar">
			<?php echo get_avatar( $post->post_author, '56' ); ?>
		</div>
		<?php endif; ?>
				
		<div class="author-description">
			<h3 class="author-title"><?php the_author_meta('display_name', $post->post_author); ?></h3>
			<p class="author-bio">
				<?php echo get_the_author_meta('user_description', $post->post_author); ?>
				<a class="author-link" href="<?php echo get_author_posts_url( $post->post_author ); ?>" rel="author">
					<?php echo sprintf(__('View all posts by %s', 'salt'), get_the_author_meta('display_name', $post->post_author)); ?>
				</a>
			</p>
			
		</div>
			
	</div>
	<?php
}
endif;

if ( ! function_exists( 'salt_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @param string|array $size (Optional) Image size to use. Accepts any valid image size, 
 * 									       or an array of width and height values in pixels (in that order).
 * @param array $attr Holds the set arguments for the post thumbnail. See @link for more details. Optional.
 * 
 * @return string HTML string that can be used to display the image.
 * @link https://developer.wordpress.org/reference/functions/the_post_thumbnail/
 *
 * @since Salt 1.0.0
 */
function salt_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail( $size, $attr ); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( $size, $attr ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'salt_pagination' ) ) :
/**
 * Custom loop pagination function.
 *
 * salt_pagination() is used for paginating the various archive pages created by WordPress. This is not
 * to be used on single.php or other single view pages.
 *
 * @param array $args (Optional) ...
 *
 *     @type arg 		$base Reference the url, which will be used to create the paginated links.
 *     @type string 	$format Used for replacing the page number.
 *     @type numercial	$total Total amount of pages and is an integer.
 *     @type numercial	$current The current page number. 
 *     @type boolean	$prev_next Include prev and next links in the list by setting the ‘prev_next’ argument to true.
 *     @type string		$prev_text Change the text for the prev link.
 *     @type string		$next_text Change the text for the next link.
 *     @type boolean	$show_all Show all of the pages instead of a short list of the pages near the current page, by default is false
 *     @type numerical	$end_size How many numbers on either the start and the end list edges, by default is 1.
 *     @type numerical	$mid_size How many numbers to either side of current page, but not including current page.
 *     @type string		$add_args It is possible to add query vars to the link by using the ‘add_args’
 *     @type string		$before Text added before the page number – within the anchor tag.
 *     @type string		$after Text added after the page number – within the anchor tag.
 *     @type boolean	$jump_to Include a Jump To option on the pagination.
 *     @type boolean	$echo If set to true the pagination will be displayed or false will return as a string.
 *     
 * @param array	$query Pass the query that paginatio is being created for, by default uses $wp_query.
 *
 * @return (array|string|void) String of page links or array of page links.
 * @link https://developer.wordpress.org/reference/functions/paginate_links/
 *
 * @since Salt 1.0.0
 */
function salt_pagination( $args = array(), $query = '' ) {
	global $wp_rewrite, $wp_query;
	
	if ( $query ) {
		$wp_query = $query;
	}

	/* If there's not more than one page, return nothing. */
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}

	/* Get the current page. */
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
	/* Get the max number of pages. */
	$max_num_pages = intval( $wp_query->max_num_pages );

	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base' 			=> add_query_arg( 'paged', '%#%' ),
		'format' 		=> '',
		'total' 		=> $max_num_pages,
		'current' 		=> $current,
		'prev_next' 	=> true,
		'prev_text' 	=> __( '&laquo; Previous', 'salt' ),
		'next_text' 	=> __( 'Next &raquo;', 'salt' ), 	
		'show_all' 		=> false,
		'end_size' 		=> 1,
		'mid_size' 		=> 1,
		'add_args'		=> '',
		'type' 			=> 'plain',
		'before' 		=> '<div class="pagination salt-pagination">', // Begin salt_pagination() arguments.
		'after' 		=> '</div>',
		'jumpto' 		=> false,
		'echo' 			=> true,
	);

	/* Add the $base argument to the array if the user is using permalinks. */
	if( $wp_rewrite->using_permalinks() ) {
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	}
	
	/* If we're on a search results page, we need to change this up a bit. */
	if ( is_search() ) {
		/* If we're in BuddyPress, use the default "unpretty" URL structure. */
		if ( class_exists( 'BP_Core_User' ) ) {
			$search_query = get_query_var( 's' );
			$paged = get_query_var( 'paged' );
			
			$base = user_trailingslashit( esc_url( home_url('/') ) ) . '?s=' . $search_query . '&paged=%#%';
			
			$defaults['base'] = $base;
		} else {
			$search_permastruct = $wp_rewrite->get_search_permastruct();
			if ( !empty( $search_permastruct ) ) {
				$defaults['base'] = user_trailingslashit( trailingslashit( get_search_link() ) . 'page/%#%' );
			}
		}
	}

	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );

	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'salt_pagination_args', $args );

	/* Don't allow the user to set this to an array. */
	if ( 'array' == $args['type'] ) {
		$args['type'] = 'plain';
	}
	
	/* Make sure raw querystrings are displayed at the end of the URL, if using pretty permalinks. */
	$pattern = '/\?(.*?)\//i';
	
	preg_match( $pattern, $args['base'], $raw_querystring );
	
	if( $wp_rewrite->using_permalinks() && $raw_querystring ) {
		$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
	}

	@$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
	@$args['base'] .= substr( $raw_querystring[0], 0, -1 );
	
	/* Get the paginated links. */
	$page_links = paginate_links( $args );

	/* Remove 'page/1' from the entire output since it's not needed. */
	$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );

	if( $args['jumpto'] ) {
		$page_links .= ' <form class="pagination-jump" method="get" action="">';
		$page_links .= '<label>' . __('Jump to', 'salt');
		$page_links .= ' <input type="text" size="2" id="page-number" value="" />';
		$page_links .= '</label>';
		$page_links .= '<input type="hidden" id="pagination-base" value="' . $args['base'] . '" />';
		$page_links .= '<input type="submit" id="pagination-submit" value="' . __('Go', 'salt') . '" />';
		$page_links .= '</form>';
		
		ob_start();
		?>
		
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function(){
			jQuery('form.pagination-jump').submit(function(){
				var number = parseInt( jQuery('#page-number').val(), 10);
				var base = jQuery('#pagination-base').val();
				var action = base.replace( /%#%/g, number );
				
				jQuery(this).attr('action', action);
			});
		});
		//]]>
		</script>
		
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		
		$page_links .= $js;
	}

	/* Wrap the paginated links with the $before and $after elements. */
	$page_links = $args['before'] . $page_links . $args['after'];

	/* Allow devs to completely overwrite the output. */
	$page_links = apply_filters( 'salt_pagination', $page_links );

	do_action( 'salt_pagination_end' );
	
	/* Return the paginated links for use in themes. */
	if ( $args['echo'] ) {
		echo $page_links;
	} else {
		return $page_links;
	}
} // End salt_pagination()
endif;

if (!function_exists('salt_post_meta')) :
/**
 * Creates the Post Meta Data - Date, Posted by, Category, Tags, Edit
 *
 * @param array $args
 * 
 * 		@type boolean	$show_post_date Display the post date, default is true.
 * 		@type boolean	$show_post_author Display the post author, default is true.
 * 		@type boolean	$show_post_category Display post category, default is true.
 * 		@type boolean	$show_post_tags  Display post tags, default is true.
 * 		@type string	$before Text added before the output.
 * 		@type string	$after Text added after the output.
 * 		@type boolean	$show_edit_link Display a edit link for the administrator, default is true.
 *      @type boolean	$echo If set to true the post meta will be displayed or false will return as a string.
 * 
 * @return string of the post meta as HTML
 *
 * @since Salt 1.0.0
 *
 */
function salt_post_meta($args=array()) {
	$defaults = array (
		'show_post_date' 	 => TRUE,
		'show_post_author' 	 => TRUE,
		'show_post_category' => TRUE,
		'show_post_tags'	 => TRUE,
 		'before' 			 => '<p class="post-meta">',
 		'after' 			 => "</p> \n",
 		'show_edit_link' 	 => TRUE,
 		'echo' 				 => TRUE
	);
	
	// Parse incomming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );
	
	// OPTIONAL: Declare each item in $args as its own variable i.e. $type, $before.
	extract( $args, EXTR_SKIP );
	
	$output = $before;
	
	if($show_post_date) {
		$output .= '<span class="post-date">';
		$output .= get_the_time( get_option( 'date_format' ) );
		$output .= '</span> ';
	}
	
	if($show_post_author) {
		$output .= '<span class="post-author">';
		
		$link = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			esc_attr( sprintf( __( 'Posts by %s', 'salt' ), get_the_author() ) ),
			get_the_author()
		);
		$output .= apply_filters( 'the_author_posts_link', $link );
		
		$output .= '</span> ';
	}
	
	if($show_post_category) {
		$categories = get_the_category();
		$separator = ', ';
		$categories_link = '';
		if($categories){
			$output .= '<span class="post-category">';

			foreach($categories as $category) {
				$categories_link .= '<a href="' . get_category_link( $category->term_id ) . '" title="'
								. esc_attr( sprintf( __( 'View all posts in %s', 'salt' ), $category->name ) ) . '">'
								. $category->cat_name . '</a>' . $separator;
			}
			
			$output .= trim($categories_link, $separator);
			$output .= '</span> ';
		}
	}

	if($show_post_tags) {
		$tags = get_the_tags();
		$separator = ', ';
		$tags_link = '';
		if($tags){
			$output .= '<span class="post-tags">';
			
			foreach($tags as $tag) {
				$tags_link .= '<a href="' . get_tag_link( $tag->term_id ) . '" title="'
								. esc_attr( sprintf( __( "View all posts in %s", 'salt' ), $tag->name ) ) . '">'
								. $tag->name . '</a>' . $separator;
			}
			
			$output .= trim($tags_link, $separator);
			$output .= '</span> ';
		}
	}
	
	if($show_edit_link && current_user_can('edit_post', get_the_ID())) {
		$output .= '<span class="edit-link">';
		$output .= '<a href="' . get_edit_post_link( get_the_ID(), false) . '" title="' . __('Edit', 'salt') . '">';
		$output .= __('Edit', 'salt');
		$output .= '</a>';
		$output .= '</span>';
	}

	$output .= $after;

	$output = apply_filters('salt_post_meta', $output);
	
	if($echo) {
		echo $output;
	} else {
		return $output;
	}
}
endif;

if( !function_exists( 'salt_social_icons') ) :
/*
 * Create a unorganized list of social media icons
 * 
 * The organized link includes classes based on the options passed through the args array. These correspond to the CSS
 * included in this theme. Just add new CSS to your child theme to create styles, then pass them through args array.
 * 
 * @param array $args
 * 
 * 		@type boolean	$type Displays black, white or color icons, default is black,
 * 		@type boolean	$shape Displays square, circular or no background shaped icons, default is circle.
 * 		@type boolean	$size  Displays small, medium or large icons, default is small.
 *      @type boolean	$echo If set to true the icons will be displayed or false will return as a string.
 * 
 * @return string of the post meta as HTML
 *
 * @since Salt 1.1.0
 */
function salt_social_icons( $args=array() ) {
	global $_salt_registered_social;

	$defaults = array(
		'type'			=> 'black',		// BLACK: use the black icons
		'shape'			=> 'circle',		// CIRCLE: use the cicular icons
		'size'			=> 'small', 	// SMALL: use small icons
		'echo'          => true,		// TRUE: output the list
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$output = '<ul class="ico-social '.$type.' '.$shape.' '.$size.'">';
	
	foreach ($_salt_registered_social as $social => $title) {

		$link = get_theme_mod('salt_social_'.$social);
		
		if ($link)
			$output .= '<li><a target="_blank" href="'.$link.'" title="'.$title.'" class="'.$social.'"><i class="fa fa-'.$social.'"></i></a></li>';
	
	}
	
	$output .= '</ul>';
	
	if($echo) {
		echo $output;
	} else {
		return $output;
	}
}
endif;

if (!function_exists('salt_post_background_image')) :
/**
 * Salt Post Background Image
 *
 * Outputs the background image saved as meta with each post. Will be ouput inside a html tag as CSS.
 * 
 * @param array $args Holds the set arguments for the post background. Optional.
 *
 *     @type string  		$before Markup to prepend the.
 *     @type string  		$after  Markup to append to the title.
 * 	   @type string 		$color  Pass a background color, format #999999
 * 	   @type string 		$repeat  Should the image repeat. Default no-repeat
 * 	   @type string 		$stretch  Should the image be stretched to cover the entire background.
 * 	   @type string 		$position  What should the position of the background. Top, middle or bottom.
 *     @type bool   		$echo   Whether to echo or return the title. Default true for echo.
 * 
 * @return string CSS that can be used on a HTML tag to display the background.
 * @since Salt 1.5.0 
 */
function salt_post_background_image( $args='' ) {
	
	global $post;

	$m = get_post_custom( $post->ID );

	$defaults = array(
		'before'   => 'style="',
		'after'    => '"',
		'color'	   => ( isset( $m['_background_color'][0] ) ) ? $m['_background_color'][0] : '',
		'repeat'   => ( isset( $m['_background_repeat'][0] ) ) ? $m['_background_repeat'][0] : 'no-repeat',
		'stretch'  => ( isset( $m['_background_stretch'][0] ) ) ? $m['_background_stretch'][0] : '',
		'position' => ( isset( $m['_background_position'][0] ) ) ? $m['_background_position'][0] : '',
		'echo'	   => true
	);
	
	$r = wp_parse_args( $args, $defaults );	

	if ( isset( $m['_background_image'][0] ) || isset( $r['color'] ) ) {
		
		if ( isset( $m['_background_image'][0] ) ) 
			$image = wp_get_attachment_image_src( $m['_background_image'][0], 'large' );
		
		$css = $r['before'];
		
		if ( isset( $image[0] ) ) {
			$css .= 'background-image:url(' . $image[0] . ');';
		}
		
		if ( isset( $r['color'] ) && $r['color']!='' ) {
			$css .= 'background-color:' . $r['color'] . ';';
		}
		
		if ( isset( $r['repeat'] ) && $r['repeat']!='' ) {
			$css .= 'background-repeat:' . $r['repeat'] . ';';
		}
		
		if ( isset( $r['stretch'] ) && $r['stretch'] == 'on' ) {
			$css .= 'background-size:cover;';
		}
		
		if ( isset( $r['position'] ) && $r['position']!='' ) {
			$css .= 'background-position:' . $r['position'] . ';';
		}
				
		$css .= $r['after'];
	}
	
	if ( $r['echo'] ) {
		echo $css;
	} else {
		return $css;
	}
}
endif;