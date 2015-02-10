<?php
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

if( !function_exists( 'salt_image_resize') ) :
/*
 * Resize images dynamically using wp built in functions
 *
 * @since Salt 1.0
 * @return array
 */
function salt_image_resize( $args=array() ) {
	global $wp_version;
	
	$defaults = array(
		'attachment_id' => 0,		// use an attachment id number
		'image_url'     => '',		// or use an image url 
		'width'         => 0,		// the image dimension
		'height'        => 0,		// the image dimension
		'alt'           => '',		// alt text for IMG tag
		'class'         => '',		// set special class for IMG tag
		'echo'          => true,	// TRUE: output the IMG tag
		'return_format' => 'array',	// this option could be array or html
		'crop'          => false,	// TRUE: needn't keep original image ratio
		'hard_crop'     => false,	// TRUE: crop directly from original image, without resize it firstly
		'retina'        => false,	// TRUE: display image for retina screen
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	$target_img_width  = &$width;
	$target_img_height = &$height;

	// Prepare the image url, image file path, image source width, image source height
	if($attachment_id) {
		$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		
		// if no image is available
		if(!$image_src) {
			return false;
		}
		
		$source_img_url    = $image_src[0];
		$source_img_width  = $image_src[1];
		$source_img_height = $image_src[2];
		$source_img_path   = get_attached_file( $attachment_id );
	} elseif( $image_url ) {
		$file_path = parse_url( $image_url );
		$source_img_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
		
		// if file doesn't exist
		if(!@file_exists($source_img_path)) {
			return false;
		}
		
		$orig_size = @getimagesize( $source_img_path );
		
		$source_img_url    = $image_url;
		$source_img_width  = $orig_size[0];
		$source_img_height = $orig_size[1];
	} else {
		return false;
	}
	
	
	$wp_img_editor = wp_get_image_editor( $source_img_path );
	$source_img_info = pathinfo( $source_img_path );
	
	if( $crop ) {					// do not keep the original image ratio
		if(!$target_img_width || !$target_img_height) {
			return false;
		}
		
		if($hard_crop) {
			if($source_img_width < $target_img_width) {
				$src_x = 0;
				$target_img_width = $source_img_width;
			} else {
				$src_x = (($source_img_width - $target_img_width) / 2);
			}
			
			if($source_img_height < $target_img_height) {
				$src_y = 0;
				$target_img_height = $source_img_height;
			} else {
				$src_y = (($source_img_width - $target_img_width) / 2);
			}
		}
		
		$target_img_name = $source_img_info['dirname'] . '/' . $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'];
		
		if(file_exists($target_img_name)) {
			$target_img_url = str_replace( basename( $source_img_url ), basename( $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'] ), $source_img_url );
		} else {
			if($hard_crop) {
				$wp_img_editor->crop( $src_x, $src_y, $target_img_width, $target_img_height );
			} else {
				$source_img_ratio = $source_img_width / $source_img_height;
				$target_img_ratio = $target_img_width / $target_img_height;
			
				if($source_img_ratio > $target_img_ratio) {
					$src_x = ( ( ($target_img_height * $source_img_ratio) - $target_img_width ) / 2 );
					$wp_img_editor->resize( null, $target_img_height, false );
					$wp_img_editor->crop( $src_x, 0, $target_img_width, $target_img_height );
				} elseif($source_img_ratio < $target_img_ratio) {
					$src_y = ( ( ($target_img_width * $source_img_height / $source_img_width) - $target_img_height ) / 2 );
					$wp_img_editor->resize( $target_img_width, null, false );
					$wp_img_editor->crop( 0, $src_y, $target_img_width, $target_img_height );
				} else {
					$wp_img_editor->resize( $target_img_width, null, false );
				}
			}

			$target_file = $wp_img_editor->save( $target_img_name );
			$target_img_url = str_replace( basename( $source_img_url ), basename( $target_file['file'] ), $source_img_url );
		}
	} else {						// keep the original image ratio
		if($target_img_width) {
			// calculate the target image height
			$target_img_height = (int)ceil($source_img_height * $target_img_width / $source_img_width);

			if( $target_img_width >= $source_img_width || $target_img_height >= $source_img_height) {
				$target_img_width  = $source_img_width;
				$target_img_height = $source_img_height;
				$target_img_name = $source_img_info['dirname'] . '/' . $source_img_info['filename'] . '.' . $source_img_info['extension'];
				$target_img_url = $source_img_url;
			} else {
				$target_img_name = $source_img_info['dirname'] . '/' . $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'];
			
				if(file_exists($target_img_name)) {
					$target_img_url = str_replace( basename( $source_img_url ), basename( $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'] ), $source_img_url );
				} else {
					$wp_img_editor->resize( $target_img_width, $target_img_height, true );
					$target_file = $wp_img_editor->save( $target_img_name );
					
					$target_img_url = str_replace( basename( $source_img_url ), basename( $target_file['file'] ), $source_img_url );
				}
			}
		} elseif($target_img_height) {
			// calculate the target image width
			$target_img_width = (int)ceil($source_img_width * $target_img_height / $source_img_height);

			if( $target_img_width >= $source_img_width || $target_img_height >= $source_img_height) {
				$target_img_width  = $source_img_width;
				$target_img_height = $source_img_height;
				$target_img_name = $source_img_info['dirname'] . '/' . $source_img_info['filename'] . '.' . $source_img_info['extension'];
				$target_img_url = $source_img_url;
			} else {
				$target_img_name = $source_img_info['dirname'] . '/' . $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'];
			
				if(file_exists($target_img_name)) {
					$target_img_url = str_replace( basename( $source_img_url ), basename( $source_img_info['filename'] . '-' . $target_img_width . 'x' . $target_img_height . '.' . $source_img_info['extension'] ), $source_img_url );
				} else {
					$wp_img_editor->resize( $target_img_width, $target_img_height, true );
					$target_file = $wp_img_editor->save( $target_img_name );
					
					$target_img_url = str_replace( basename( $source_img_url ), basename( $target_file['file'] ), $source_img_url );
				}
			}
		} else {
			// if nor target image width either target image height is set, the original image will be output or returned.
			$target_img_url    = $source_img_url;
			$target_img_width  = $source_img_width;
			$target_img_height = $source_img_height;
		}
	}
	
	// Every thing is prepared, start to show the image or return image informations.
	// if $retina set to true, show the image in quarter size.
	if($retina) {
		$target_img_width  = $target_img_width / 2;
		$target_img_height = $target_img_height / 2;
	}
	
	$output = '<img src="' . $target_img_url . '" width="' . $target_img_width . '" height="' . $target_img_height . '"';
	
	if($alt) {
		$output .= ' alt="' . $alt . '"';
	}
	
	if($class) {
		$output .= ' class="' . $class . '"';
	}
	
	$output .= ' />';
	
	if($echo) {
		echo $output;
	} else {
		if('array' == $return_format) {
			return array(
				'url'    => $target_img_url,
				'width'  => $target_img_width,
				'height' => $target_img_height
			);
		} else {
			// return the IMG tag
			return $output;
		}
	}
}
endif;

if( !function_exists( 'salt_social_icons') ) :
/*
 * Create a unorganized list of social media icons
 *
 * @param int $attach_id
 * @since Salt 1.0
 * @return array
 */
function salt_social_icons( $args=array() ) {
	global $_salt_registered_social;
	
	$defaults = array(
		'attachment_id' => 0,
		'type'			=> 'black',		// BLACK: use the black icons
		'shape'			=> 'circle',	// CIRCLE: use the cicular icons
		'background'	=> false, 		// FALSE: no background color
		'echo'          => true,		// TRUE: output the list
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	$output = '<ul class="ico-social '.$type.' '.$shape.' small">';
	
	foreach ($_salt_registered_social as $social => $title) {

		$link = get_theme_option('social_'.$social);
		
		if ($link)
			$output .= '<li><a target="_blank" href="'.$link.'" title="'.$title.'" class="ico-'.$social.'"></a></li>';
	
	}
	
	$output .= '</ul>';
	
	if($echo) {
		echo $output;
	} else {
		return $output;
	}
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

if ( !function_exists('salt_breadcrumb') ) :
/**
 * Creates the breadcrumb trail.
 *
 * salt_breadcrumb() is used for creating a breadcrumb trail on pages and posts
 * still to do is a way connect the single post with a parent page.
 *
 * @since Salt 1.0
 */
function salt_breadcrumb( $args = array() ) {
	global $post;
	
	/* Set up some default arguments for the breadcrumb function. */
	$defaults = array (
		'separator' 		=> '&raquo;',
		'display_on_home'   => false,
		'display_home_link' => true,
		'home_class'		=> 'home',
		'before' 			=> '<div class="breadcrumb">',
		'after' 			=> '</div>',
		'echo' 		 		=> true,
	);
	
	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );
	
	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'salt_breadcrumb_args', $args );
	
	extract( $args, EXTR_SKIP );

	if (!$display_on_home && is_front_page()) return;
	
	$output = '';
	
	$post_id = $post->ID;
	
	$bc_separator = ' <span class="separator"> ' . $separator . ' </span> ';
	
	if(0 === strpos('page', get_option('show_on_front'))) {
		//Get the homepage ID (page_on_front in settings)
		$page_on_front = (int)get_option('page_on_front'); 
		$page_for_posts  = (int)get_option('page_for_posts');
	} else {
		$page_on_front = 0;
		$page_for_posts  = 0;
	}
	
	if($page_on_front != $post_id && $display_home_link) {
		if ($page_on_front) {
			$permalink = get_permalink($page_on_front);
			
			$output .= '<a class="'.$home_class.'" href="' . $permalink . '" title="' . get_the_title($page_on_front) . '">' . get_the_title($page_on_front) . '</a>';
			$output .= $bc_separator;

		} elseif (!is_home() || (is_home() && !$page_on_front && $page_for_posts) && $display_home_link) {
			$output .= '<a href="' . get_bloginfo('siteurl') . '" title="' . get_bloginfo('siteurl') . '">';

			if ($page_on_front != 0) {
				$output .= get_the_title($page_on_front);
			} else {
				$output .= __('Home', 'salt');
			}
			
			$output .= '</a>';
			
			$output .= $bc_separator;
		}
	}
	
	if(is_home() && $page_for_posts && !$is_page){
		$output .= get_the_title($page_for_posts);
	} elseif ( $is_page && $page_for_posts!=$post_id ) {
		$ancestors = get_post_ancestors($post_id);
		
		if( is_array( $ancestors ) ) {
			$ancestors_r = array_reverse($ancestors);
			
			foreach ( $ancestors_r as $anc ) {
				if ( $page_on_front == $anc ) {
					continue;
				}
				
				$output .= '<a href="' . get_permalink($anc) . '">' . get_the_title($anc) . '</a>';
				$output .= $bc_separator;
			}
		}
		
		$output .= '<a href="' . get_permalink($post_id) . '">' .get_the_title($post_id) . '</a>';
		$output .= $bc_separator;
		
		$output .= '<span class="current-crumb">' . get_the_title($post->ID) . '</span>';
	} elseif ( is_page() && $page_on_front!=$post_id ) {
		the_post();
		
		if(is_array($post->ancestors)) {
			$ancestors = array_reverse($post->ancestors);
			
			foreach($ancestors as $anc) {
				if($page_on_front==$anc) {
					continue;
				}
				
				$output .= '<a href="' . get_permalink($anc) . '">' . get_the_title($anc) . '</a>';
				$output .= $bc_separator;
			}
		}
		
		if ( isset( $_GET['yr'] )) {
			$output .= '<a href="' . get_permalink() . '">' . get_the_title() .'</a>';
			$output .= $bc_separator;
			
			$output .= '<span class="current-crumb">' . $_GET['yr'] . '</span>';
		} else {
			$output .= '<span class="current-crumb">' . get_the_title() . '</span>';
		}
		
		rewind_posts();
	} elseif ( $page_for_posts && $is_page ) {
		$ancestors = get_post_ancestors($post_id);
		
		if( is_array( $ancestors ) ) {
			$ancestors_r = array_reverse($ancestors);
			
			foreach ( $ancestors_r as $anc ) {
				$output .= '<a href="' . get_permalink($anc) . '">' . get_the_title($anc) . '</a>';
				$output .= $bc_separator;
			}
		}
		
		$output .= '<span class="current-crumb">' . get_the_title($post_id) . '</span>';

	} elseif ( is_single() ) {

		$single_parent_id = get_option("salt_".$post->post_type."_parent_page");
		
		if ( $single_parent_id ) {
			$output .= '<a href="' . get_permalink($single_parent_id) . '">' . get_the_title($single_parent_id) . '</a>';
			$output .= $bc_separator;
		}
		
		$output .= '<span class="current-crumb">' . get_the_title($post->ID) .'</span>';
	
	} elseif ( is_404() ) {
		$output .= '<span class="current-crumb">' . __('Not Found', 'salt') . '</span>';
	} elseif ( is_search() ) {
		$output .= '<span class="current-crumb">' . __('Search Results', 'salt') . '</span>';
	} elseif ( is_tax() ) {
		$term = get_queried_object();
		$tax = get_taxonomy( $term->taxonomy );
		$output .= '<span class="current-crumb">' . single_term_title( $tax->labels->name, false ) . '</span>';
	}
	
	/* Wrap the paginated links with the $before and $after elements. */
	$output = $args['before'] . $output . $args['after'];

	$output = apply_filters('salt_breadcrumb', $output);

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}
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
								. esc_attr( sprintf( __( "View all posts in %s" ), $tag->name ) ) . '">'
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

if (!function_exists( 'salt_register_classes')) :
/**
 * Merge given classes to global variable registered class list
 *
 * Register class that can be used accross different hooks throughout the site
 * Use the function from the includes/theme-setup.php files to add new ones
 *
 * @since Salt 1.0
 */
function salt_register_classes( $classes = array() ) {
	// Do nothing if argument is invailid
	if( !$classes ) {
		return;
	}
	global $_salt_registered_classes;

	$_salt_registered_classes = array_merge( (array) $_salt_registered_classes, $classes );
}
endif;

if (!function_exists( 'salt_register_social_connect')) :
/**
 * Merge given social options to global variable registered class list
 *
 * Registering new social icons allows the end user to add their social pages
 * Use the function from the includes/theme-setup.php files to add new ones
 *
 * @since Salt 1.0
 */	
function salt_register_social_connect( $social = array() ) {
	global $_salt_registered_social;

	$_salt_registered_social = array_merge( (array) $_salt_registered_social, $social );
}
endif;