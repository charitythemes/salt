<?php
/**
 * The secondary sidebar containing the another widget area
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

do_action('salt_sidebar_above'); ?>

	<div id="secondary-sidebar" <?php salt_secondary_sidebar_class(); ?> role="sidebar">
	
		<?php do_action('salt_sidebar_inside_above'); ?>
		    
			<?php dynamic_sidebar('secondary-widget-area'); ?>
	    
		<?php do_action('salt_sidebar_inside_below'); ?>
	
	</div>

<?php do_action('salt_sidebar_below'); ?>