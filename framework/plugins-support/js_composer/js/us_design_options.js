! function( $ ) {
	var USDOSetValue = function( $container ) {
		var $value = $container.find( '.us-design-options-value' ),
			value = '',
			$inputs = $container.find( '.us-design-options-input' ),
			withPosition = false;
		$inputs.each( function( index, input ) {
			var $input = $( input ),
				name = $input.attr( 'name' ).replace( '_', '-' ),
				trimmedValue = $input.val().trim();
			// If parameter is empty, do not add it to the value
			if ( trimmedValue == '' ) {
				return;
			}
			if ( name.substr( 0, 8 ) == 'position' ) {
				name = name.replace( 'position-', '' );
				withPosition = true;
			}
			// If a plain number is entered, add 'px' to it's end
			if ( trimmedValue.match( /^\d+$/ ) && trimmedValue != 0 ) {
				trimmedValue += 'px';
			}
			value += ' ' + name + ': ' + trimmedValue + ';';
		}.bind( this ) );

		if ( withPosition ) {
			value = 'position: absolute;' + value;
		}

		$value.val( value.trim() );
	};

	$( '#usdo_pos_abs' ).change( function() {
		var $this = $( this ),
			$posWrapper = $( '.usof-design-position' ),
			$container = $this.closest( '.usof-design' );
		$posWrapper.toggleClass( 'pos_off', ! $this.is( ':checked' ) );
		if ( ! $this.is( ':checked' ) ) {
			// Clearing all values
			$container.find( '.us-design-options-input.for-position' ).val( '' );
		}
		USDOSetValue( $container );
	} );

	$( '.us-design-options-input' ).off( 'blur' ).live( 'blur', function( e ) {
		var $target = $( e.target ),
			trimmedValue = $target.val().trim(),
			$container = $target.closest( '.usof-design' );
		if ( trimmedValue != '' ) {
			$target.val( trimmedValue );
			USDOSetValue( $container );
		}
	} );
}( window.jQuery );