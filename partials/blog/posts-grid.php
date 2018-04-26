<div class="blog-grid-wrapper">
	<div class="row">
		<?php 
		global $slider_query, $post;
		
		$query_args = array(
			'post_type' 	 	  => 'post',
			'posts_per_page' 	  => 3,
			'ignore_sticky_posts' => 1
		);

		// If the admin is not asking to repeat posts in the main loop, pluck them out.
		if ( isset( $slider_query ) && ! get_theme_mod( 'salt_slider_posts_in_loop' ) )	{				
			$post_ids = wp_list_pluck( $slider_query->posts, 'ID' );
			$query_args['post__not_in'] = $post_ids;
		}

		// If set in Customizer use posts with a certain tag.
		if ( get_theme_mod( 'salt_filter_homepage_posts' ) == '1' ) {
			$tags = get_option( 'salt_tag_homepage_posts' );
			if ( $tags != '' ) {
				$query_args['tag'] = $tags;
			}
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
				
				get_template_part( 'partials/content/content' ); ?>
			
				</div>
				<?php
			}
		}
		wp_reset_postdata();
		?>
	</div>
</div>
