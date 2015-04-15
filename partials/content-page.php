<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

do_action('salt_article_above'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php do_action('salt_article_inside_above'); ?>
	
	<?php 
	if (!is_front_page()) {
	?>
	<header class="page-header">
		<?php the_title( '<h1>', '</h1>' ); ?>		
	</header>
	<?php
	} ?>
	
	<div class="page-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'salt' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'salt' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>		
	</div>
		
	<footer class="page-footer">
		<?php edit_post_link( __( 'Edit', 'salt' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
	</footer>
	
	<?php do_action('salt_article_inside_below'); ?>

</article>

<?php do_action('salt_article_below'); ?>