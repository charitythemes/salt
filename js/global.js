(function($) {
	$(document).ready(function() {
		$('.navbar-toggle').on('click', function() {
			$('.navbar-collapse').toggle();
			$('.navbar-toggle').toggleClass('active');
		});
	});
})(jQuery);