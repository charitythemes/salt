<?php
/**
 * The default template for displaying content
 *
 * Used for both single/index/archive/search.
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

do_action('salt_article_above'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php do_action('salt_article_inside_above'); ?>

	<?php salt_post_thumbnail(); ?>
	
	<header class="entry-header">
		
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
	</header>
	
	<div class="entry-content">
	
		<?php 
		if ( is_single() ) :	
			the_content();
		else :
			the_excerpt(); ?>
		<?php endif; ?>
		
		<?php
		if ( is_single() )
			salt_author_meta(); ?>

	</div>
	
	<footer class="entry-footer">

		<?php
		$args = array(
			'show_post_date' => true,
			'show_post_author' => true,
			'show_post_category' => true,
	 		'show_edit_link' => true,
	 		'echo' => true
		);
	
		salt_post_meta( $args ); ?>
	
	</footer>

	<?php do_action('salt_article_inside_below'); ?>
	
	<div class="clearfix"></div>

</article>

<?php do_action('salt_article_below'); ?>