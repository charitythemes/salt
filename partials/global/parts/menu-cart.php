<li class="menu-cart"><a href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="fa fa-shopping-basket"></i><?php  

if ( WC()->cart->cart_contents_count > 0 ) {
	?><span class="salt-minicart-count"><?php echo esc_html( WC()->cart->cart_contents_count ); ?></span><?php
} ?></a></li>