<?php 
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

			<?php if ( have_posts() ) : ?>
	
			<section <?php salt_section_class(); ?>>
			
				<?php do_action('salt_section_inside_above'); ?>

				<?php
				if ( apply_filters( 'salt_show_page_title', true ) ) :
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				endif;
				?>	
	
				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
	
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'partials/content/content', get_post_format() );
	
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