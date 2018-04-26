<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
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
		
					// Include the page content template.
					get_template_part( 'partials/content/content', 'page' );
		
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