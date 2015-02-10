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

	<footer id="footer" <?php do_action('salt_footer_class'); ?>>

		<?php
		do_action('salt_footer_inside_above');

		do_action('salt_footer_inside');

		do_action('salt_footer_inside_below');
		?>

	</footer>

	<?php
	do_action('salt_footer_below');
	
	do_action('salt_bottom');
	
	wp_footer();
	?>
	
	</div><!-- /#wrapper -->

</body>

</html>