<?php 
if ( class_exists( 'Salt_Post_Types' ) ) :
/**
 * Register a Post Type Sections
 *
 * A sections post type is used to content on to pages.
 * 
 * @package		WordPress
 * @subpack		Salt
 * @since		1.5.2
 */
 
/**
 * Create the post type using Salt_Post_Types wrapper class.
 *
 * Uses the same arguments as WordPress register_post_type function, add to array to override defaults.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 */
$section = new Salt_Post_Types( 'section', array( 
	'singular'			  => 'Section',
	'plural'			  => 'Sections',
	'slug'				  => 'sections',
	'menu_icon' 		  => 'dashicons-exerpt-view',
	'supports'			  => array( 'thumbnail', 'title', 'page-attributes' ),
	'exclude_from_search' => true,
	'public'			  => false
	));

/**
 * Register Link Metabox.
 *
 * Salt_Post_Types has a few default metabox types, including 'background', 'link', 'text'.
 */
$section->register_metabox( 'background' );
$section->register_metabox( 'link' );
$section->register_metabox( 'text' );
$section->register_metabox( 'fontawesome' );

/**
 * Register Taxonomy
 *
 * Give this post type a taxonomy, uses the same arguments as WordPress function register_taxonomy.
 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
 */
$section->register_taxonomy( 'section_type', array(
	'plural'			=> __('Section Groups', 'salt'),
	'singular'			=> __('Section Group', 'salt'),
	'slug'				=> 'group',
    'show_admin_column' => true,
	'query_var' 		=> false,
	'rewrite' 			=> false

));

/**
 * Register Template Grid
 *
 * Register templates that are used to display a post type, these are selected through the shortcode 'display' tag.
 */
$section->register_template( 'grid', array(
	'label'      => __('Grid', 'salt'),
	'screenshot' => get_template_directory_uri() . '/core/assets/img/view-grid-screenshot.png',
	'template'	 => get_stylesheet_directory() . '/partials/view-grid.php'
));

/**
 * Register Template List
 *
 * Register templates that are used to display a post type, these are selected through the shortcode 'display' tag.
 */
$section->register_template( 'list', array(
	'label'      => __('List', 'salt'),
	'screenshot' => get_template_directory_uri() . '/core/assets/img/view-list-screenshot.png',
	'template'	 => get_stylesheet_directory() . '/partials/view-list.php'
));

/**
 * Register Shortcode Options
 *
 * Register a selection of options that can be used when creating insert the post type into a page.
 * These options are passed through to the templates and used to manuipulate how the posts display.
 */
$section->register_shortcode( 'salt_section', array(
	'title'  => __('Insert Sections', 'salt'),
	'fields' => array(
		array(
			'name'	=> __('Sections', 'salt'),
			'desc'	=> __('Number of sections to show (leave blank for all)', 'salt'),
			'std'	=> 5,
			'id'	=> 'num',
			'type'	=> 'text',
			'views' => array('grid', 'list')
		),
		array(
			'name'	  => __('Columns', 'salt'),
			'desc'	  => __('Select the number of columns you would like to display', 'salt'),
			'std'	  => 4,
			'id'	  => 'cols',
			'type'	  => 'select',
			'views'	  => array('grid'),
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'6' => '6'
			)
		),
		array(
			'name'	  => __('Sections Type', 'salt'),
			'desc'	  => __('Only display one type of section by selecting it from the list', 'salt'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'section_type',
				array(
					'hide_empty' => false
				)
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Order By', 'salt'),
			'desc'	  => __('Select the order you would like the list to display', 'salt'),
			'std'	  => 'menu_order',
			'id'	  => 'orderby',
			'type'	  => 'select',
			'views'	  => array('grid', 'list'),
			'options' => array(
				'title' 	 => 'Alphabetically',
				'menu_order' => 'By Page Order',
				'date'		 => 'By Date',
				'rand'		 => 'Randomly'
			)
		),
		array(
			'name'	  => __('Order', 'salt'),
			'desc'	  => __('Select if you like the list to be ascending or descending.', 'salt'),
			'std'	  => 'DESC',
			'id'	  => 'order',
			'type'	  => 'select',
			'views'	  => array('grid', 'list'),
			'options' => array(
				'ASC'  => 'Ascending',
				'DESC' => 'Descending',
			)
		)			
	)
));

if ( ! function_exists( 'salt_section_get_posts' )) :
/**
 * Filter the Post Type by taxonomy
 *
 * If the shortcode option gid is set, then filter the post types by the taxonomy.
 */
function salt_section_get_posts( $args, $atts ) {
	if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
		$args['taxonomy'] = 'section_type';
		$args['section_type'] = $atts['gid'];
	}	
	return $args;
}
add_filter( 'salt_section_get_posts', 'salt_section_get_posts', 10, 2 );
endif;

if ( ! function_exists('salt_add_section_tax_filters')) :
/**
 * Backend filter to by taxonomy
 *
 * The filter dropdown allows sorting taxonomy on the Sections list table.
 * @link https://developer.wordpress.org/reference/hooks/restrict_manage_posts/
 */
function salt_add_section_tax_filters() {
	global $typenow;
  
	if( $typenow == 'section' ) {
		
		$terms = get_terms( 'section_type' );

		if ( count( $terms ) > 0 ) {

			echo '<select name="term" class="postform">';
			echo '<option value="">'.__('Show All', 'salt').'</option>';

			foreach ($terms as $term)
				echo '<option value='. $term->slug, isset( $_GET['term'] ) && $_GET['term'] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
			
			echo '</select>';
			
			echo '<input type="hidden" name="taxonomy" value="section_type" />';
		}
	}
}
add_action( 'restrict_manage_posts', 'salt_add_section_tax_filters' );
endif;

endif;