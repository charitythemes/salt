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

} )( wp.customize );