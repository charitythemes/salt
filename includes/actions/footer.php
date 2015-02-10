<?php
/**
 * Actions for loading different elements into the website footer
 *
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
 * Loads the credits of the theme into the footer
 *
 * @since 1.0
 */
function salt_footer_credits() { 
	?>
	<p class="pull-left copyright">
		<span class="copy">&copy;</span> <?php echo date('Y'); ?> <?php echo get_theme_mod('salt_footer_text'); ?>
	</p>

	<?php
    if (get_option('salt_footer_credit')) { ?>
	<p class="pull-right credits">
		<?php echo sprintf(__('Powered by %1s WordPress %2s', 'salt'), '<a href="http://www.wordpress.org" target="_blank">','</a>'); ?> <span class="seperator">|</span> 
		<?php echo sprintf(__('Designed by %1s charity: themes %2s', 'salt'), '<a class="ct_logo" href="http://www.charitythemes.org" target="_blank">','</a>'); ?>
	</p>
	<?php } ?>
<?php 
}
add_action( 'salt_footer_inside', 'salt_footer_credits'); 

/**
 * Load the widgetized areas into the footer
 *
 * @since 1.0
 */
function salt_footer_sidebars_display() {
	
	$cols = false;
	$cols = get_theme_mod('salt_footer_sidebars');

	if (!$cols) return;
	
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
	
	echo '<div id="footer-widgets">';
	
	echo '<div class="container">';

	$i = 0;
	
	echo '<div class="row">';

	while ( $i < $cols ) {
			$i++;

			echo '<div class="'.$span.'">';
			
			$footer_siderbar = 'footer-' . $i;

			dynamic_sidebar( $footer_siderbar );
			
			echo '</div>';
	}
	echo '</div>';
	
	echo '</div>';
	
	echo '</div>';
}

add_action( 'salt_footer_above', 'salt_footer_sidebars_display' );


/**
 * Insert a footer wrapper div tag above the website footer
 *
 * @since 1.0
 */
function salt_footer_wrapper_insert() {
	echo '<div id="footer-wrapper">';
}
add_action('salt_footer_above','salt_footer_wrapper_insert'); 

/**
 * Close of the wrapper divs in the footer
 *
 * @since 1.0
 */
function salt_footer_close_div() {
	echo '</div>';
}
add_action('salt_footer_below','salt_header_close_div'); 