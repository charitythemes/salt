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

	<?php if ( apply_filters( 'salt_show_page_title', true ) ) : ?>

	<header class="page-header">
		<?php the_title( '<h1>', '</h1>' ); ?>		
	</header>

	<?php endif; ?>
	
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
	
	<?php if ( ! is_front_page() ) : ?>
	<footer class="page-footer">
		<?php edit_post_link( __( 'Edit Page', 'salt' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
	<?php endif; ?>
	
	<?php do_action('salt_article_inside_below'); ?>

</article>

<?php do_action('salt_article_below'); ?>