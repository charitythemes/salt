<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */
 
get_header(); ?>

	<?php do_action('salt_container_above'); ?>

	<div id="container" <?php do_action('salt_container_class'); ?> role="container">

		<?php do_action('salt_container_inside_above'); ?>
		
		<main id="main" <?php do_action('salt_main_class'); ?> role="main">
			
			<?php do_action('salt_section_above'); ?>
	
			<?php if ( have_posts() ) : ?>
	
			<section <?php do_action('salt_section_class'); ?>>
			
				<?php do_action('salt_section_inside_above'); ?>
	
				<?php
				// Start the loop.
				while ( have_posts() ) : the_post();
	
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'partials/content', get_post_format() );
	
				// End the loop.
				endwhile;
				?>
				
				<?php salt_pagination(); ?>
				
				<?php do_action('salt_section_inside_below'); ?>
	
			</section>
			
			<?php
			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'partials/content', 'none' );
	
			endif;
			?>
		
			<?php do_action('salt_section_below'); ?>

		</main><!-- /#main -->
			
		<?php do_action('salt_container_inside_below'); ?>

	</div><!-- /#container -->
	
	<?php do_action('salt_container_below'); ?>

<?php get_footer(); ?>