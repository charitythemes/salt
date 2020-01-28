<?php
/**
 * The default view for the slider post type
 *
 * This is the template that displays for the slider shortcode by default.
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.5.0
 */

/**
 * Slider query is executed from 'actions.php' on init.
 *
 * @since 1.5.0
 */

// Use a global variable to hold the query results.
global $slider_query;

if ( ! is_home() ) {

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

if ( $slider_query->have_posts() ) :
?>

<div class="slider-wrapper">
	
	<ul class="slides bxslider" style="visibility: hidden;">				
	<?php 
	// Start the loop
	while ( $slider_query->have_posts() ) : $slider_query->the_post();
	
		get_template_part( 'partials/slider/slide' );
	
	// End the loop
	endwhile;
	?>
	</ul>
</div>

<?php wp_reset_postdata(); ?>

<script type="text/javascript">
jQuery(document).ready(function() {
	
	var touchDevice = false;
	if (navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/)) {
		touchDevice = true;
	}
		
	jQuery('.slider-wrapper .bxslider').bxSlider({
		easing			: 'ease-in-out',
		auto			: <?php echo ( get_theme_mod('salt_slider_auto_scroll', '1') == '1' ) ? 'true' : 'false'; ?>,
		mode			: '<?php echo ( $a = get_theme_mod('salt_slider_animation') ) ? $a : 'horizontal'; ?>',
		controls		: <?php echo ( get_theme_mod('salt_slider_direction_nav', '1') == '1' ) ? 'true' : 'false'; ?>,
	    nextText		: '',
	    prevText		: '',
	    preloadImages	: 'all',
		pager			: <?php echo ( get_theme_mod('salt_slider_control_nav', '1') == '1' ) ? 'true' : 'false'; ?>,
		speed			: <?php echo ( $s = get_option('salt_slider_speed') ) ? $s : '500'; ?>,
		adaptiveHeight	: 'true',
		useCSS			: 'false',
  		touchEnabled	: touchDevice,		
		onSliderLoad: function() {
			jQuery(".bxslider").css("visibility", "visible");
		}
	});
});
</script>

<?php
endif; ?>