<?php
defined( 'ABSPATH' ) || exit;

?>
<div class="salt-fullwidth-wrapper">
	<div class="container">
		<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
		
			<?php
				/**
				 * Hook: woocommerce_after_single_product_summary.
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_upsell_display - 15
				 * @hooked woocommerce_output_related_products - 20
				 */
				do_action( 'woocommerce_after_single_product_summary' );
			?>
		
		</div>
	</div>
</div>