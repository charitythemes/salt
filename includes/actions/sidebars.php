<?php
/**
 * Actions for adding sidebars to the site
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
 * Register primary and secondary sidebars on the site
 * 
 * @since Salt 1.0
 */
function salt_primary_secondary_sidebars() {

	register_sidebar( array (
		'name' => 'Primary Sidebar',
		'id' => 'primary-widget-area',
		'description' => __( 'The primary sidebar', 'salt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => 'Secondary Sidebar',
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary sidebar', 'salt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'salt_primary_secondary_sidebars' );

/**
 * Register footer sidebars on the site based on how many many footer cols there are
 * 
 * @since Salt 1.0
 */
function salt_footer_sidebars() {
 	
	$total = get_theme_mod('salt_footer_sidebars');	
	
	if ($total && $total != '0') {

		$i=0; while ($i < $total) {
			
			$i++;
			register_sidebar( array (
				'name' => 'Footer '.$i,
				'id' => 'footer-'.$i, 
				'description' => __( 'Footer Widget Area', 'salt' ), 
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
		}
	}		
}

add_action( 'widgets_init', 'salt_footer_sidebars' );

?>