<?php get_template_part( 'partials/header' , 'logo' ); ?>
<div class="nav-wrapper navbar-collapse pull-right">
	<nav role="navigation" id="primary-menu">
		<?php wp_nav_menu (  array (  'container' => 'div', 'items_wrap' => '<ul class="%2$s">%3$s</ul>', 'menu_class' => 'menu', 'theme_location' => 'primary-menu' )); ?>
	</nav>
</div>
<div class="pull-right navbar-toggle-wrapper visible-xs-block">
	<button type="button" class="navbar-toggle">
    	<i class="fa fa-bars fa-lg"></i>
	</button>
</div>