<div id="header-wrapper">
	
	<?php do_action('salt_header_above'); ?>
				
	<header id="header">

		<?php do_action('salt_header_inside_above'); ?>

		<div class="container">
			
			<?php 
			if ( 'header' == get_theme_mod( 'salt_social_position' ) ) {				
				get_template_part( 'partials/global/parts/social' , 'links' );
			} ?>					
			
			<div class="inner-wrapper">
				<?php get_template_part( 'partials/header/parts/logo' ); ?>
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
						get_template_part( 'partials/global/parts/social' , 'links' );
					} ?>
				</div>
			</div>
		</div>

		<?php do_action('salt_header_inside_below'); ?>

	</header>
	
	<?php do_action('salt_header_below'); ?>

</div>

<?php do_action('salt_header_outside_below'); ?>