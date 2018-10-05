<?php
/**
 * Single Slide
 *
 * The template for displaying a single slide within the slider.
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.5.0
 */

$height = ( $h = get_post_meta( get_the_ID(), '_background_height', true ) ) ? $h : '450';

?>
<li id="bx-slide-<?php echo get_the_ID(); ?>" class="slide" <?php salt_post_background_image(); ?>>

	<div class="<?php echo ( get_post_meta( get_the_ID(), '_background_darken', true ) =='on' ) ? 'darken' : '';?> slide-content" style="height:<?php echo $height; ?>px;">

		<div class="slide-container <?php echo ( $pos=get_post_meta( get_the_ID(), '_text_position', true ) ) ? $pos : '';?>">		

			<div class="copy-container <?php echo ( $align=get_post_meta( get_the_ID(), '_text_align', true ) ) ? $align : '';?> <?php echo ( $size=get_post_meta( get_the_ID(), '_text_size', true ) ) ? $size : '';?>">

				<?php get_template_part( 'partials/slider/parts/title' ); ?>

				<?php get_template_part( 'partials/slider/parts/excerpt' ); ?>

				<?php get_template_part( 'partials/slider/parts/link' ); ?>
				
				<?php edit_post_link( __( 'Edit Slide', 'salt' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		</div>

	</div>

</li>