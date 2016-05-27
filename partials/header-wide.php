<?php get_template_part( 'partials/header' , 'logo' ); ?>
<div class="nav-wrapper">
	<div class="navbar-toggle-wrapper">
		<button type="button" class="navbar-toggle visible-xs-block">
			<span></span>
		</button>		
		<nav role="navigation" id="primary-menu" class="navbar-collapse">
			<?php wp_nav_menu (  array (  'container' => 'div', 'items_wrap' => '<ul class="%2$s">%3$s</ul>', 'menu_class' => 'menu', 'theme_location' => 'primary-menu' )); ?>
		</nav>
	</div>
	
	<?php if ( 'menu-right' == get_theme_mod( 'salt_social_position' ) ) {				
		get_template_part( 'partials/social' , 'links' );
	} ?>
</div>