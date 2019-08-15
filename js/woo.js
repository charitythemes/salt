(function($) {
	$(document).ready(function() {
		/* Increase product quantity by 1 */
		$('.plus').on('click',function(e){
			var val = parseInt($(this).prev('input').val());
			$(this).prev('input').val( val+1 );
			if ( $('button[name=update_cart]').length > 0 ) {
				$('button[name=update_cart]').prop('disabled', '');
			}
		});

		/* Increase product quantity by 1 */		
		$('.minus').on('click',function(e){
			var val = parseInt($(this).next('input').val());
			if(val !== 1){
			    $(this).next('input').val( val-1 );
			}
			if ( $('button[name=update_cart]').length > 0 ) {
				$('button[name=update_cart]').prop('disabled', '');
			}
		});
		
		// Remove the header gap if the user clicks the dismiss button
		$( '.woocommerce-store-notice__dismiss-link' ).click( function() {
			$( 'body' ).removeClass('woocommerce-store-notice-enabled');
		});

		/* Bind: Search - Header link */
		$('#site-search-btn').bind('click', function(e) {
			e.preventDefault();
			$(this).toggleClass('active');
			$('body').toggleClass('header-search-open');
			$(this).searchPanelToggle();
			if ( $('.navbar-toggle').hasClass('active') ) {
				$('.navbar-collapse').toggle();
				$('.navbar-toggle').toggleClass('active');
			}
		});
	
		/* Bind: Search - Panel "close" button */
		$('#search-close').bind('click', function(e) {
			e.preventDefault();
			$('#site-search-btn').trigger('click');
		});
	
		/**
		 *	Shop search: Toggle panel
		 */
		$.fn.searchPanelToggle = function () {
			var self = this,
				$searchInput = $('#site-search input.search-field');
			
			$('#site-search').slideToggle(200, function() {
				$('#site-search').toggleClass('fade-in');	
				if ($('#site-search').hasClass('fade-in')) {
					// "Focus" search input
					$searchInput.focus();
				} else {
					// Empty input value
					$searchInput.val('');
				}
			});
		}

		/**
		 *	Search: Validate input string
		 */
		$.fn.searchValidateInput = function(s) {
			// Make sure the search string has at least one character (not just whitespace) and minimum allowed characters are entered
			if ((/\S/.test(s)) && s.length > (1)) {
				return true;
			} else {
				return false;
			}
		}
	
		/* Bind: Search input "input" event */
		var validSearch;
		$('#site-search input.search-field').on('input', function() {
			validSearch = $(this).searchValidateInput($(this).val());
			
			if (validSearch) {
				$('#search-notice').addClass('show');
			} else {
				$('#search-notice').removeClass('show');
			}
		}).trigger('input');
	});
})(jQuery);