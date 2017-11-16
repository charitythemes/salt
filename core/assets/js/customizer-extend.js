/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 */

( function( api ) {

	// An array of the different color scheme keys available.
	var colorSchemeKeys = [
		'light',
		'darkest',
		'dark',
		'medium',
		'light',
		'lightest',		
	];
	
	// Extend the 'Radio' controls.
	api.controlConstructor.radio = api.Control.extend( {
		ready: function() {
			// Check if the salt color scheme setting is being updated.
			if ( 'salt_color_scheme' === this.id ) {
				
				// When the salt color scheme settings is changed.
				this.setting.bind( 'change', function( value ) {

					// Cycle through the color tones presets.
					colorSchemeKeys.forEach( function( key ) {

						// Update each color tone.
						api( 'salt_color_scheme_' + key ).set( colorScheme[value].colors[ key ] );
						api.control( 'salt_color_scheme_' + key ).container.find( '.color-picker-hex' )
							.data( 'data-default-color', colorScheme[value].colors[ key ] )
							.wpColorPicker( 'defaultColor', colorScheme[value].colors[ key ] );
						
					});
				} );
			}
		}
	} );
	
	/**
	 * Extend the 'Select' controls.
	 *
	 * @since Salt 1.5.0
	 */	
	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			
			// Check if this input is the display type select box.
			if ( 'salt_slider_display_type' === this.id ) {

				// If the option tag is not currrently being used, lets hide the tag extra input field
				if ( this.setting._value != 'tag' ) {
					jQuery( '#customize-control-salt_slider_posts_tag' ).hide();			
				}
				
				// When the display type select box is changed.
				this.setting.bind( 'change', function( value ) {

					// if the user selects 'tag'
					if ( value == 'tag' ) {
						// Show the input box for a certain tag
						jQuery( '#customize-control-salt_slider_posts_tag' ).slideDown();
					} else if ( value != 'tag' ) {
						jQuery( '#customize-control-salt_slider_posts_tag' ).slideUp();
					}
				} );
			}
		}
	} );


	/**
	 * Extend the 'Checkbox' controls.
	 *
	 * @since Salt 1.6.4
	 */	
	api.controlConstructor.checkbox = api.Control.extend( {
		ready: function() {
			
			// Check if this input is the display type select box.
			if ( 'salt_filter_homepage_posts' === this.id ) {
				
				// If the option tag is not currrently being used, lets hide the tag extra input field
				if ( this.setting._value != 1 ) {
					jQuery( '#customize-control-salt_tag_homepage_posts' ).hide();			
				}
				
				// When the display type select box is changed.
				this.setting.bind( 'change', function( value ) {
					// if the user selects 'tag'
					if ( value == '1' ) {
						// Show the input box for a certain tag
						jQuery( '#customize-control-salt_tag_homepage_posts' ).slideDown();
					} else if ( value != '1' ) {
						jQuery( '#customize-control-salt_tag_homepage_posts' ).slideUp();
					}
				} );
			}
		}
	} );

} )( wp.customize );