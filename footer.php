<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the body
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

 	do_action('salt_footer_above'); ?>

	<?php
	$cols = false;
	$cols = get_theme_mod('salt_footer_sidebars');

	if ($cols != 0) {
	
	if ($cols==6)
		$span = 'col-sm-2';
	elseif ($cols==4)
		$span = 'col-sm-3';
	elseif ($cols==3)
		$span = 'col-sm-4';
	elseif ($cols==2)
		$span = 'col-sm-6';
	elseif ($cols==1)
		$span = 'col-sm-12';
		
	$i = 0;
	?>
	<div id="footer-widgets">
		<div class="container">
			<div class="row">
			<?php
			while ( $i < $cols ) {
				$i++;
				?>
				<div class="<?php echo $span; ?>">	
					<?php
					$footer_siderbar = 'footer-' . $i;
					dynamic_sidebar( $footer_siderbar ); ?>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>	
	<?php } ?>

	<div id="footer-wrapper">		
		<footer id="footer" class="container">
	
			<?php do_action('salt_footer_inside_above'); ?>

			<p class="copyright"><span class="copy">&copy;</span> <?php echo date('Y'); ?> <?php echo get_theme_mod('salt_footer_text'); ?></p>
	
		    <?php if (get_option('salt_footer_credit')!='1') { ?>
		    
			<p class="credits"><?php echo sprintf( esc_html__('Designed by %1s charity: themes %2s', 'salt'), '<a class="ct_logo" href="https://charitythemes.org" title="WordPress Themes for Nonprofits" target="_blank">','</a>'); ?></p>
			
			<?php } ?>

			<?php
			if ( 'footer' == get_theme_mod( 'salt_social_position' ) ) {			
				get_template_part( 'partials/global/parts/social' , 'links' );
			} ?>			
	
			<?php do_action('salt_footer_inside_below'); ?>
	
		</footer>
	</div>

	<?php do_action('salt_footer_below'); ?>
	
	<?php do_action('salt_bottom'); ?>
	
	</div><!-- /#wrapper -->

<?php wp_footer(); ?>

</body>

</html>