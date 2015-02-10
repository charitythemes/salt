jQuery(document).ready(function ($) {
	
	/**
	 * Radio images select
	 *
	 * @since 1.0
	 */
	$('.salt-radio-img-img').click(function() {
		$(this).parent().parent().parent().find('.salt-radio-img-img').removeClass('salt-radio-img-selected');
		$(this).addClass('salt-radio-img-selected');
	});
});