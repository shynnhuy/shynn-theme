/**
 * UpSolution Element: l-preloader
 */
! function( $ ) {
	"use strict";

	if ( $( '.l-preloader' ).length ) {
		$( 'document' ).ready( function() {
			setTimeout( function() {
				$( '.l-preloader' ).addClass( 'done' );
			}, 500 );
			setTimeout( function() {
				$( '.l-preloader' ).addClass( 'hidden' );
			}, 1000 ); // 500 ms after 'done' class is added
		} );
	}
}( jQuery );