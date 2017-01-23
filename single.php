<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

get_header(); ?>

	<?php do_action('salt_container_above'); ?>

	<div id="container" class="container" role="container">

		<?php do_action('salt_container_inside_above'); ?>
		
		<main id="main" class="row" role="main">
			
			<?php do_action('salt_section_above'); ?>
	
			<section <?php salt_section_class(); ?>>
			
				<?php do_action('salt_section_inside_above'); ?>
	
				<?php
				// Start the loop.
				while ( have_posts() ) : the_post();
		
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'partials/content', get_post_format() );
		
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
		
				// End the loop.
				endwhile;
				?>
			
				<?php do_action('salt_section_inside_below'); ?>
	
			</section>
		
			<?php do_action('salt_section_below'); ?>

		</main><!-- /#main -->
			
		<?php do_action('salt_container_inside_below'); ?>

	</div><!-- /#container -->
	
	<?php do_action('salt_container_below'); ?>

<?php get_footer(); ?>