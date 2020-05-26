/**
 * UpSolution Element: Tabs
 *
 * @requires $us.canvas
 */
!function( $ ) {
	"use strict";

	$us.WTabs = function( container, options ) {
		this.init( container, options );
	};

	$us.WTabs.prototype = {

		init: function( container, options ) {
			// Setting options
			var defaults = {
				duration: 300,
				easing: 'cubic-bezier(.78,.13,.15,.86)'
			};
			this.options = $.extend( {}, defaults, options );
			this.isRtl = $( '.l-body' ).hasClass( 'rtl' );

			// Commonly used dom elements
			this.$container = $( container );
			this.$tabsList = this.$container.find( '.w-tabs-list:first' );
			this.$tabs = this.$tabsList.find( '.w-tabs-item' );
			this.$tabsH = this.$tabsList.find( '.w-tabs-item-h' );
			this.$sectionsWrapper = this.$container.find( '.w-tabs-sections:first' );
			this.$sectionsHelper = this.$sectionsWrapper.children();
			this.$sections = this.$sectionsHelper.find( '.w-tabs-section' );
			this.$headers = this.$sections.children( '.w-tabs-section-header' );
			this.$contents = this.$sections.children( '.w-tabs-section-content' );
			this.$line_charts = this.$container.find( ".vc_line-chart" );
			this.$round_charts = this.$container.find( ".vc_round-chart" );

			// Class variables
			this.width = 0;
			this.tabWidths = [];
			this.isTogglable = ( this.$container.usMod( 'type' ) == 'togglable' );
			// Basic layout
			this.basicLayout = this.$container.hasClass( 'accordion' ) ? 'accordion' : ( this.$container.usMod( 'layout' ) || 'default' );
			// Current active layout (may be switched to 'accordion')
			this.curLayout = this.basicLayout;
			this.responsive = $us.canvas.options.responsive;
			// Array of active tabs indexes
			this.active = [];
			this.activeOnInit = [];
			this.definedActive = [];
			this.count = this.$tabs.length;
			// Container width at which we should switch to accordion layout
			this.minWidth = 0;

			if ( this.count == 0 ) {
				return;
			}

			// Preparing arrays of jQuery objects for easier manipulating in future
			this.tabs = $.map( this.$tabs.toArray(), $ );
			this.sections = $.map( this.$sections.toArray(), $ );
			this.headers = $.map( this.$headers.toArray(), $ );
			this.contents = $.map( this.$contents.toArray(), $ );

			$.each( this.tabs, function( index ) {

				if ( this.sections[ index ].hasClass( 'content-empty' ) ) {
					this.tabs[ index ].hide();
					this.sections[ index ].hide();
				}

				if ( this.tabs[ index ].hasClass( 'active' ) ) {
					this.active.push( index );
					this.activeOnInit.push( index );
				}
				if ( this.tabs[ index ].hasClass( 'defined-active' ) ) {
					this.definedActive.push( index );
				}
				this.tabs[ index ].add( this.headers[ index ] ).on( 'click', function( e ) {
					e.preventDefault();
					// Toggling accordion sections
					if ( this.curLayout == 'accordion' && this.isTogglable ) {
						// Cannot toggle the only active item
						this.toggleSection( index );
					}
					// Setting tabs active item
					else {
						if ( index != this.active[ 0 ] ) {
							this.headerClicked = true;
							this.openSection( index );
						} else if ( this.curLayout == 'accordion' ) {
							this.contents[ index ].css( 'display', 'block' ).slideUp( this.options.duration, this._events.contentChanged );
							this.tabs[ index ].removeClass( 'active' );
							this.sections[ index ].removeClass( 'active' );
							this.active[ 0 ] = undefined;
						}
					}
				}.bind( this ) );
			}.bind( this ) );

			this.$tabsH.on( 'click', function( e ) {
				e.preventDefault();
			} );

			// Boundable events
			this._events = {
				resize: this.resize.bind( this ),
				hashchange: this.hashchange.bind( this ),
				contentChanged: function() {
					$us.$canvas.trigger( 'contentChange' );
					// TODO: check if we can do this without hardcoding line charts init here;
					this.$line_charts.length && jQuery.fn.vcLineChart && this.$line_charts.vcLineChart( { reload: !1 } );
					this.$round_charts.length && jQuery.fn.vcRoundChart && this.$round_charts.vcRoundChart( { reload: !1 } );
				}.bind( this )
			};

			// Starting everything
			this.switchLayout( this.curLayout );


			$us.$window.on( 'resize', this._events.resize );

			$us.$window.on( 'hashchange', this._events.hashchange );

			$us.$document.on( 'ready', this._events.resize );

			$us.$document.on( 'ready', function() {
				setTimeout( this._events.resize, 50 );

				setTimeout( function() {
					// Open tab on page load by hash
					if ( window.location.hash ) {
						var hash = window.location.hash.substr( 1 ),
							$linkedSection = this.$container.find( '.w-tabs-section[id="' + hash + '"]' );
						if ( $linkedSection.length && ( !$linkedSection.hasClass( 'active' ) ) ) {
							var $header = $linkedSection.find( '.w-tabs-section-header' );
							$header.click();
						}
					}
				}.bind( this ), 150 );
			}.bind( this ) );

			// Support for external links to tabs
			$.each( this.tabs, function( index ) {
				if ( this.headers[ index ].attr( 'href' ) != undefined ) {
					var tabHref = this.headers[ index ].attr( 'href' ),
						tabHeader = this.headers[ index ];
					$( 'a[href="' + tabHref + '"]' ).on( 'click', function( e ) {
						e.preventDefault();
						if ( $( this ).hasClass( 'w-tabs-section-header', 'w-tabs-item-h' ) ) {
							return;
						}
						tabHeader.click();
					} );
				}
			}.bind( this ) );

			this.$container.addClass( 'initialized' );
		},

		hashchange: function() {
			if ( window.location.hash ) {
				var hash = window.location.hash.substr( 1 ),
					$linkedSection = this.$container.find( '.w-tabs-section[id="' + hash + '"]' );
				if ( $linkedSection.length && ( !$linkedSection.hasClass( 'active' ) ) ) {
					var $header = $linkedSection.find( '.w-tabs-section-header' );
					$header.click();
				}
			}
		},

		switchLayout: function( to ) {
			this.cleanUpLayout( this.curLayout );
			this.prepareLayout( to );
			this.curLayout = to;
		},

		/**
		 * Clean up layout's special inline styles and/or dom elements
		 * @param from
		 */
		cleanUpLayout: function( from ) {
			if ( from == 'default' || from == 'timeline' || from == 'modern' || from == 'trendy' ) {
				this.$sectionsWrapper.clearPreviousTransitions().resetInlineCSS( 'width', 'height' );
				this.$sectionsHelper.clearPreviousTransitions().resetInlineCSS( 'position', 'width', 'left' );
				this.$sections.resetInlineCSS( 'width', 'display' );
				this.$container.removeClass( 'autoresize' );
			} else if ( from == 'accordion' ) {
				this.$container.removeClass( 'accordion' );
				this.$sections.resetInlineCSS( 'display' );
				this.$contents.resetInlineCSS( 'height', 'padding-top', 'padding-bottom', 'display', 'opacity' );
			} else if ( from == 'ver' ) {
				this.$contents.resetInlineCSS( 'height', 'padding-top', 'padding-bottom', 'display', 'opacity' );
			}
		},

		/**
		 * Apply layout's special inline styles and/or dom elements
		 * @param to
		 */
		prepareLayout: function( to ) {
			if ( to != 'accordion' && this.active[ 0 ] == undefined ) {
				this.active[ 0 ] = this.activeOnInit[ 0 ];
				if ( this.active[ 0 ] != undefined ) {
					this.tabs[ this.active[ 0 ] ].addClass( 'active' );
					this.sections[ this.active[ 0 ] ].addClass( 'active' );
				}
			}

			if ( to == 'default' || to == 'timeline' || to == 'modern' || to == 'trendy' ) {
				this.$container.addClass( 'autoresize' );
				this.$sectionsHelper.css( 'position', 'absolute' );
			} else if ( to == 'accordion' ) {
				this.$container.addClass( 'accordion' );
				this.$contents.hide();
				if ( this.curLayout != 'accordion' && this.active[ 0 ] != undefined && this.active[ 0 ] != this.definedActive[ 0 ] ) {
					this.tabs[ this.active[ 0 ] ].removeClass( 'active' );
					this.sections[ this.active[ 0 ] ].removeClass( 'active' );
					this.active[ 0 ] = this.definedActive[ 0 ];

				}
				for ( var i = 0; i < this.active.length; i ++ ) {
					if ( this.contents[ this.active[ i ] ] !== undefined ) {
						this.tabs[ this.active[ i ] ].addClass( 'active' );
						this.sections[ this.active[ i ] ].addClass( 'active' );
						this.contents[ this.active[ i ] ].show();
					}
				}

			} else if ( to == 'ver' ) {
				this.$contents.hide();
				this.contents[ this.active[ 0 ] ].show();
			}


		},

		/**
		 * Measure needed sizes and store them to this.tabWidths variable
		 *
		 * TODO Count minWidth here as well
		 */
		measure: function() {
			if ( this.basicLayout == 'ver' ) {
				// Measuring minimum tabs width
				this.$tabsList.css( 'width', 0 );
				var minTabWidth = this.$tabsList.outerWidth( true );
				this.$tabsList.css( 'width', '' );
				// Measuring the mininum content width
				this.$container.addClass( 'measure' );
				var minContentWidth = this.$sectionsWrapper.outerWidth( true );
				this.$container.removeClass( 'measure' );
				// Measuring minimum tabs width for percent-based sizes
				var navWidth = this.$container.usMod( 'navwidth' );
				if ( navWidth != 'auto' ) {
					// Percent-based measure
					minTabWidth = Math.max( minTabWidth, minContentWidth * parseInt( navWidth ) / ( 100 - parseInt( navWidth ) ) );
				}
				this.minWidth = Math.max( 480, minContentWidth + minTabWidth + 1 );
			} else {
				this.tabWidths = [];
				// We hide active line temporarily to count tab sizes properly
				this.$container.addClass( 'measure' );
				for ( var index = 0; index < this.tabs.length; index ++ ) {
					this.tabWidths.push( this.tabs[ index ].outerWidth( true ) );
				}
				this.$container.removeClass( 'measure' );
				if ( this.basicLayout == 'default' || this.basicLayout == 'timeline' || this.basicLayout == 'modern' || this.basicLayout == 'trendy' ) {
					// Array sum
					this.minWidth = this.tabWidths.reduce( function( pv, cv ) {
						return pv + cv;
					}, 0 );
				}
			}
		},

		/**
		 * Open tab section
		 *
		 * @param index int
		 */
		openSection: function( index ) {
			if ( this.sections[ index ] === undefined ) {
				return;
			}
			if ( this.curLayout == 'default' || this.curLayout == 'timeline' || this.curLayout == 'modern' || this.curLayout == 'trendy' ) {
				this.$container.addClass( 'autoresize' );
				this.$sections.removeClass( 'active' ).css( 'display', 'none' );
				this.sections[ index ].stop( true, true ).fadeIn( this.options.duration, function() {
					$( this ).addClass( 'active' );
				} );
			} else if ( this.curLayout == 'accordion' ) {
				if ( this.contents[ this.active[ 0 ] ] !== undefined ) {
					this.contents[ this.active[ 0 ] ].css( 'display', 'block' ).slideUp( this.options.duration );
				}
				this.contents[ index ].css( 'display', 'none' ).slideDown( this.options.duration, this._events.contentChanged );
				// Scrolling to the opened section at small window dimensions
				if ( $us.canvas.winWidth < 768 && this.headerClicked == true ) {
					var newTop = this.headers[ 0 ].offset().top;
					for ( var i = 0; i < index; i ++ ) {
						newTop += this.headers[ i ].outerHeight();
					}
					$us.scroll.scrollTo( newTop, true );
					this.headerClicked = false;
				}
				this.$sections.removeClass( 'active' );
				this.sections[ index ].addClass( 'active' );
			} else if ( this.curLayout == 'ver' ) {
				if ( this.contents[ this.active[ 0 ] ] !== undefined ) {
					this.contents[ this.active[ 0 ] ].css( 'display', 'none' );
				}
				this.contents[ index ].css( 'display', 'none' ).stop( true, true ).fadeIn( this.options.duration, this._events.contentChanged );
				this.$sections.removeClass( 'active' );
				this.sections[ index ].addClass( 'active' );
			}

			this._events.contentChanged();
			this.$tabs.removeClass( 'active' );
			this.tabs[ index ].addClass( 'active' );
			this.active[ 0 ] = index;
		},

		/**
		 * Toggle some togglable accordion section
		 *
		 * @param index
		 */
		toggleSection: function( index ) {
			// (!) Can only be used within accordion state
			var indexPos = $.inArray( index, this.active );
			if ( indexPos != - 1 ) {
				this.contents[ index ].css( 'display', 'block' ).slideUp( this.options.duration, this._events.contentChanged );
				this.tabs[ index ].removeClass( 'active' );
				this.sections[ index ].removeClass( 'active' );
				this.active.splice( indexPos, 1 );
			} else {
				this.contents[ index ].css( 'display', 'none' ).slideDown( this.options.duration, this._events.contentChanged );
				this.tabs[ index ].addClass( 'active' );
				this.sections[ index ].addClass( 'active' );
				this.active.push( index );
			}
		},

		/**
		 * Resize-driven logics
		 */
		resize: function() {
			this.width = this.$container.width();
			this.$tabsList.removeClass( 'hidden' );

			// Basic layout may be overriden
			if ( this.responsive ) {
				// if (this.basicLayout == 'ver' && this.curLayout != 'ver') this.switchLayout('ver');
				if ( this.curLayout != 'accordion' ) {
					this.measure();
				}
				var nextLayout = ( this.width < this.minWidth ) ? 'accordion' : this.basicLayout;
				if ( nextLayout !== this.curLayout ) {
					this.switchLayout( nextLayout );
				}
			}

			// Fixing tabs display
			if ( this.curLayout == 'default' || this.curLayout == 'timeline' || this.curLayout == 'modern' || this.curLayout == 'trendy' ) {
				this.$container.addClass( 'autoresize' );
				this.$sectionsWrapper.css( 'width', this.width );
				this.$sectionsHelper.css( 'width', this.count * this.width );
				this.$sections.css( 'width', this.width );
				if ( this.contents[ this.active[ 0 ] ] !== undefined ) {
					this.$sectionsHelper.css( 'left', - this.width * ( this.isRtl ? ( this.count - this.active[ 0 ] - 1 ) : this.active[ 0 ] ) );
					var height = this.sections[ this.active[ 0 ] ].height();
					this.$sectionsWrapper.css( 'height', height );
				}
			}
			this._events.contentChanged();
		}

	};

	$.fn.wTabs = function( options ) {
		return this.each( function() {
			$( this ).data( 'wTabs', new $us.WTabs( this, options ) );
		} );
	};
}( jQuery );

/* RevSlider support for our tabs */
jQuery( function( $ ) {
	$( '.w-tabs .rev_slider' ).each( function() {
		var $slider = $( this );
		$slider.bind( "revolution.slide.onloaded", function( e ) {
			$us.$canvas.on( 'contentChange', function() {
				$slider.revredraw();
			} );
		} );
	} );
} );
