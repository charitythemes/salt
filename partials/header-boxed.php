<?php get_template_part( 'partials/header' , 'logo' ); ?>
<div class="navbar-toggle-wrapper visible-xs-block">
	<button type="button" class="navbar-toggle">
    	<i class="fa fa-bars fa-lg"></i>
	</button>
</div>
<?php if( 'menu-right' == get_theme_mod( 'salt_social_position' ) ) { ?>	
<div class="social-links visible-xs-block">
	<?php 
	if ( $t=get_theme_mod('salt_social_type') )
		$args['type'] = $t;

	if ( $s=get_theme_mod('salt_social_shape') )
		$args['shape'] = $s;

	if ( $z=get_theme_mod('salt_social_size') )
		$args['size'] = $z;
	
	salt_social_icons( $args ); ?>
</div>
<?php } ?>