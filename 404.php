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

	<div id="container" class="container" role="container">

		<?php do_action('salt_container_inside_above'); ?>
		
		<main id="main" class="row" role="main">
			
			<?php do_action('salt_section_above'); ?>
	
			<section <?php salt_section_class(); ?>>
			
				<?php do_action('salt_section_inside_above'); ?>

				<article class="type-page hentry">
				
				<?php if ( apply_filters( 'salt_show_page_title', true ) ) : ?>

					<header class="page-header">
						<h1><?php _e( 'Oops! That page can&rsquo;t be found.', 'salt' ); ?></h1>
					</header>

				<?php endif; ?>
					
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