<?php
/**
 * The template for displaying 404 pages (not found)
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
	
			<section <?php do_action('salt_section_class'); ?>>
			
				<?php do_action('salt_section_inside_above'); ?>

				<article class="type-page hentry">
				
					<header class="page-header">
						<h1><?php _e( 'Oops! That page can&rsquo;t be found.', 'salt' ); ?></h1>
					</header>
					
					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'salt' ); ?></p>
						
						<div class="search-wrapper">
							<?php get_search_form(); ?>
						</div>
					</div>
					
				</article>
			
				<?php do_action('salt_section_inside_below'); ?>
	
			</section>
		
			<?php do_action('salt_section_below'); ?>

		</main><!-- /#main -->
			
	<?php do_action('salt_container_inside_below'); ?>

	</div><!-- /#container -->
	
	<?php do_action('salt_container_below'); ?>

<?php get_footer(); ?>