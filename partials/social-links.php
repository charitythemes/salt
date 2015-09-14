<div class="social-links <?php echo get_theme_mod('salt_social_position'); ?>">
	<?php 
	if ( $t=get_theme_mod('salt_social_type') )
		$args['type'] = $t;

	if ( $s=get_theme_mod('salt_social_shape') )
		$args['shape'] = $s;

	if ( $z=get_theme_mod('salt_social_size') )
		$args['size'] = $z;
	
	salt_social_icons( $args ); ?>
</div>