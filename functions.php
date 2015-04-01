<?php
/**
 * Salt functions and definitions
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

locate_template('includes/theme-setup.php', true);
locate_template('includes/theme-customizer.php', true);

/**
 * Include additional actions & Filters
 * 
 * @link http://codex.wordpress.org/Function_Reference/locate_template
 * @since Salt 1.0
 */
locate_template('includes/actions/header.php', true);
locate_template('includes/actions/footer.php', true);
locate_template('includes/actions/layout.php', true);
locate_template('includes/actions/sidebars.php', true);

/**
 * Several useful functions for salt and any child themes 
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 */

if ( ! function_exists( 'salt_author_meta' ) ) :
/**
 * Displays the authors meta information.
 *
 * Add an about the author block, generally used on the single post page.
 *
 * @since Salt 1.0
 */
function salt_author_meta() {

 	global $post;
	?>
	<div class="author-meta vcard">
	
		<h2 class="author-heading"><?php _e('About the author', 'salt'); ?></h2>
			
		<div class="author-avatar">
			<?php echo get_avatar( $post->post_author, '56' ); ?>
		</div>
				
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
 * @since Salt 1.0
 */
function salt_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) );
		?>
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
 * @since Salt 1.0
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
		'add_fragment'	=> '',
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
			
			$base = user_trailingslashit( home_url() ) . '?s=' . $search_query . '&paged=%#%';
			
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
 * @since Salt 1.0
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
//			$output .= '<span class="small">';
//			$output .= __('Posted on', 'salt');
//			$output .= '</span> ';
		$output .= get_the_time( get_option( 'date_format' ) );
		$output .= '</span> ';
	}
	
	if($show_post_author) {
		$output .= '<span class="post-author">';
//			$output .= '<span class="small">';
//			$output .= __('by', 'salt');
//			$output .= '</span> ';
		
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
//				$output .= '<span class="small">';
//				$output .= __('in', 'salt');
//				$output .= '</span> ';
			
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
//				$output .= '<span class="small">';
//				$output .= __('in', 'salt');
//				$output .= '</span> ';
			
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