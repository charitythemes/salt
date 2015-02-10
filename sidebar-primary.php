<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

do_action('salt_sidebar_above'); ?>

	<div id="primary-sidebar" <?php do_action('salt_primary_sidebar_class'); ?> role="sidebar">
	
		<?php do_action('salt_sidebar_inside_above'); ?>
		    
			<?php dynamic_sidebar('primary-widget-area'); ?>
	    
		<?php do_action('salt_sidebar_inside_below'); ?>
	
	</div>

<?php do_action('salt_sidebar_below'); ?>