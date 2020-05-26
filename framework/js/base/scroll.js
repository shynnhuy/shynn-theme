/**
 * $us.scroll
 *
 * ScrollSpy, Smooth scroll links and hash-based scrolling all-in-one
 *
 * @requires $us.canvas
 */
! function( $ ) {
	"use strict";

	function USScroll( options ) {

		// Setting options
		var defaults = {
			/**
			 * @param {String|jQuery} Selector or object of hash scroll anchors that should be attached on init
			 */
			attachOnInit: '.menu-item a[href*="#"], .menu-item[href*="#"], a.w-btn[href*="#"], .w-text a[href*="#"], ' + '.vc_icon_element a[href*="#"], .vc_custom_heading a[href*="#"], a.w-grid-item-anchor[href*="#"], .w-toplink, ' + '.w-image a[href*="#"], .w-iconbox a[href*="#"], .w-comments-title a[href*="#"], a.smooth-scroll[href*="#"]',
			/**
			 * @param {String} Classname that will be toggled on relevant buttons
			 */
			buttonActiveClass: 'active',
			/**
			 * @param {String} Classname that will be toggled on relevant menu items
			 */
			menuItemActiveClass: 'current-menu-item',
			/**
			 * @param {String} Classname that will be toggled on relevant menu ancestors
			 */
			menuItemAncestorActiveClass: 'current-menu-ancestor',
			/**
			 * @param {Number} Duration of scroll animation
			 */
			animationDuration: $us.canvasOptions.scrollDuration,
			/**
			 * @param {String} Easing for scroll animation
			 */
			animationEasing: 'easeInOutExpo'
		};
		this.options = $.extend( {}, defaults, options || {} );

		// Hash blocks with targets and activity indicators
		this.blocks = {};

		// Is scrolling to some specific block at the moment?
		this.isScrolling = false;

		// Waypoints that will be called at certain scroll position
		this.waypoints = [];

		// Sticky rows
		this.stickyRows = [];//$('.l-section.type_sticky');

		// Boundable events
		this._events = {
			cancel: this.cancel.bind( this ), scroll: this.scroll.bind( this ), resize: this.resize.bind( this )
		};

		this._canvasTopOffset = 0;
		$us.$window.on( 'resize load', this._events.resize );
		setTimeout( this._events.resize, 75 );

		$us.$window.on( 'scroll', this._events.scroll );
		setTimeout( this._events.scroll, 75 );

		if ( this.options.attachOnInit ) {
			this.attach( this.options.attachOnInit );
		}

		$( '.l-section.type_sticky' ).each( function( key, row ) {
			var $row = $( row ), $rowGap = $row.next( '.l-section-gap' ), stickyRow = {
				$row: $row, $rowGap: $rowGap
			};
			this._countStickyRow( stickyRow );
			this.stickyRows.push( stickyRow );
		}.bind( this ) );

		// Recount scroll positions on any content changes
		$us.$canvas.on( 'contentChange', this._countAllPositions.bind( this ) );

		// Handling initial document hash
		if ( document.location.hash && document.location.hash.indexOf( '#!' ) == - 1 ) {
			var hash = document.location.hash, scrollPlace = ( this.blocks[ hash ] !== undefined ) ? hash : undefined;
			if ( scrollPlace === undefined ) {
				try {
					var $target = $( hash );
					if ( $target.length != 0 ) {
						scrollPlace = $target;
					}
				}
				catch ( error ) {
					//Do not have to do anything here since scrollPlace is already undefined
				}

			}
			if ( scrollPlace !== undefined ) {
				// While page loads, its content changes, and we'll keep the proper scroll on each sufficient content
				// change until the page finishes loading or user scrolls the page manually
				var keepScrollPositionTimer = setInterval( function() {
					this.scrollTo( scrollPlace );
				}.bind( this ), 100 );
				var clearHashEvents = function() {
					// Content size still may change via other script right after page load
					setTimeout( function() {
						clearInterval( keepScrollPositionTimer );
						$us.canvas.resize();
						this._countAllPositions();
						this.scrollTo( scrollPlace );
					}.bind( this ), 100 );
					$us.$window.off( 'load touchstart mousewheel DOMMouseScroll touchstart', clearHashEvents );
				}.bind( this );
				$us.$window.on( 'load touchstart mousewheel DOMMouseScroll touchstart', clearHashEvents );
			}
		}
	}

	USScroll.prototype = {

		/**
		 * Count hash's target position and store it properly
		 *
		 * @param {String} hash
		 * @private
		 */
		_countPosition: function( hash ) {
			var targetTop = this.blocks[ hash ].target.offset().top;
			if ( this.blocks[ hash ].target.is( '.l-section.sticky' ) ) {
				this.blocks[ hash ].target.removeClass( 'sticky' );
				targetTop = this.blocks[ hash ].target.offset().top;
				this.blocks[ hash ].target.addClass( 'sticky' );
			}
			this.blocks[ hash ].top = Math.ceil( targetTop - this._canvasTopOffset );
			if ( $us.header.headerTop === undefined || ( $us.header.headerTop > 0 && targetTop > $us.header.headerTop ) ) {
				this.blocks[ hash ].top = this.blocks[ hash ].top - $us.header.scrolledOccupiedHeight;
			}
			if ( this.stickyRows[ 0 ] !== undefined && window.innerWidth > this.stickyRows[ 0 ].disableWidth && targetTop > this.stickyRows[ 0 ].originalTop ) {
				this.blocks[ hash ].top = this.blocks[ hash ].top - this.stickyRows[ 0 ].height;
			}
			this.blocks[ hash ].bottom = this.blocks[ hash ].top + this.blocks[ hash ].target.outerHeight( false );
		},

		/**
		 * Count all targets' positions for proper scrolling
		 *
		 * @private
		 */
		_countAllPositions: function() {
			// Take into account #wpadminbar (and others possible) offset
			this._canvasTopOffset = $us.$canvas.offset().top;
			// Counting stickyRows
			for ( var i = 0; i < this.stickyRows.length; i ++ ) {
				this._countStickyRow( this.stickyRows[ i ] );
			}
			// Counting blocks
			for ( var hash in this.blocks ) {
				if ( ! this.blocks.hasOwnProperty( hash ) ) {
					continue;
				}
				this._countPosition( hash );
			}
			// Counting waypoints
			for ( var i = 0; i < this.waypoints.length; i ++ ) {
				this._countWaypoint( this.waypoints[ i ] );
			}
		},

		/**
		 * Indicate scroll position by hash
		 *
		 * @param {String} activeHash
		 * @private
		 */
		_indicatePosition: function( activeHash ) {
			var activeMenuAncestors = [];
			for ( var hash in this.blocks ) {
				if ( ! this.blocks.hasOwnProperty( hash ) ) {
					continue;
				}
				if ( this.blocks[ hash ].buttons !== undefined ) {
					this.blocks[ hash ].buttons.toggleClass( this.options.buttonActiveClass, hash === activeHash );
				}
				if ( this.blocks[ hash ].menuItems !== undefined ) {
					this.blocks[ hash ].menuItems.toggleClass( this.options.menuItemActiveClass, hash === activeHash );
				}
				if ( this.blocks[ hash ].menuAncestors !== undefined ) {
					this.blocks[ hash ].menuAncestors.removeClass( this.options.menuItemAncestorActiveClass );
				}
			}
			if ( this.blocks[ activeHash ] !== undefined && this.blocks[ activeHash ].menuAncestors !== undefined ) {
				this.blocks[ activeHash ].menuAncestors.addClass( this.options.menuItemAncestorActiveClass );
			}
		},

		/**
		 * Attach anchors so their targets will be listened for possible scrolls
		 *
		 * @param {String|jQuery} anchors Selector or list of anchors to attach
		 */
		attach: function( anchors ) {
			// Location pattern to check absolute URLs for current location
			var locationPattern = new RegExp( '^' + location.pathname.replace( /[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&" ) + '#' );

			var $anchors = $( anchors );
			if ( $anchors.length == 0 ) {
				return;
			}
			$anchors.each( function( index, anchor ) {
				var $anchor = $( anchor ), href = $anchor.attr( 'href' ), hash = $anchor.prop( 'hash' );
				// Ignoring ajax links
				if ( hash.indexOf( '#!' ) != - 1 ) {
					return;
				}
				// Checking if the hash is connected with the current page
				if ( ! ( // Link type: #something
					href.charAt( 0 ) == '#' || // Link type: /#something
					( href.charAt( 0 ) == '/' && locationPattern.test( href ) ) || // Link type:
																				   // http://example.com/some/path/#something
					href.indexOf( location.host + location.pathname + '#' ) > - 1 ) ) {
					return;
				}
				// Do we have an actual target, for which we'll need to count geometry?
				if ( hash != '' && hash != '#' ) {
					// Attach target
					if ( this.blocks[ hash ] === undefined ) {
						var $target = $( hash ), $type = '';

						// Don't attach anchors that actually have no target
						if ( $target.length == 0 ) {
							return;
						}
						// If it's the only row in a section, than use section instead
						if ( $target.hasClass( 'g-cols' ) && $target.parent().children().length == 1 ) {
							$target = $target.closest( '.l-section' );
						}
						// If it's tabs or tour item, then use tabs container
						if ( $target.hasClass( 'w-tabs-section' ) ) {
							var $newTarget = $target.closest( '.w-tabs' );
							if ( ! $newTarget.hasClass( 'accordion' ) ) {
								$target = $newTarget;
								$type = 'tab';
							}
						}
						this.blocks[ hash ] = {
							target: $target, type: $type
						};
						this._countPosition( hash );
					}
					// Attach activity indicator
					if ( $anchor.parent().length > 0 && $anchor.parent().hasClass( 'menu-item' ) ) {
						var $menuIndicator = $anchor.closest( '.menu-item' );
						this.blocks[ hash ].menuItems = ( this.blocks[ hash ].menuItems || $() ).add( $menuIndicator );
						var $menuAncestors = $menuIndicator.parents( '.menu-item-has-children' );
						if ( $menuAncestors.length > 0 ) {
							this.blocks[ hash ].menuAncestors = ( this.blocks[ hash ].menuAncestors || $() ).add( $menuAncestors );
						}
					} else {
						this.blocks[ hash ].buttons = ( this.blocks[ hash ].buttons || $() ).add( $anchor );
					}
				}
				$anchor.on( 'click', function( event ) {
					event.preventDefault();
					this.scrollTo( hash, true );
					//If it's tabs
					if ( typeof this.blocks[ hash ] !== 'undefined' ) {
						if ( this.blocks[ hash ].type == 'tab' ) {
							var $linkedSection = this.blocks[ hash ].target.find( '.w-tabs-section[id="' + hash.substr( 1 ) + '"]' );
							if ( $linkedSection.length && ( ! $linkedSection.hasClass( 'active' ) ) ) {
								var $header = $linkedSection.find( '.w-tabs-section-header' );
								$header.click();
							}
						}
					}
				}.bind( this ) );
			}.bind( this ) );
		},

		/**
		 * Scroll page to a certain position or hash
		 *
		 * @param {Number|String|jQuery} place
		 * @param {Boolean} animate
		 */
		scrollTo: function( place, animate ) {
			var placeType, newY;
			// Scroll to top
			if ( place == '' || place == '#' ) {
				newY = 0;
				placeType = 'top';
			}
			// Scroll by hash
			else if ( this.blocks[ place ] !== undefined ) {
				newY = this.blocks[ place ].top;
				placeType = 'hash';
			} else if ( place instanceof $ ) {
				if ( place.hasClass( 'w-tabs-section' ) ) {
					var newPlace = place.closest( '.w-tabs' );
					if ( ! newPlace.hasClass( 'accordion' ) ) {
						place = newPlace;
					}
				}
				newY = Math.floor( place.offset().top - this._canvasTopOffset );
				if ( $us.header.headerTop === undefined || ( $us.header.headerTop > 0 && place.offset().top > $us.header.headerTop ) ) {
					newY = newY - $us.header.scrolledOccupiedHeight;
				}
				placeType = 'element';
			} else {
				newY = Math.floor( place - this._canvasTopOffset );
				if ( $us.header.headerTop === undefined || ( $us.header.headerTop > 0 && place > $us.header.headerTop ) ) {
					newY = newY - $us.header.scrolledOccupiedHeight;
				}
			}
			var indicateActive = function() {
				if ( placeType == 'hash' ) {
					this._indicatePosition( place );
				} else {
					this.scroll();
				}
			}.bind( this );
			if ( animate ) {
				this.isScrolling = true;

				// Fix for iPads since scrollTop returns 0 all the time
				if ( navigator.userAgent.match( /iPad/i ) != null && $( '.us_iframe' ).length && placeType == 'hash' ) {
					$( place )[ 0 ].scrollIntoView( { behavior: "smooth", block: "start" } );
				}

				$us.$htmlBody.stop( true, false ).animate( {
					scrollTop: newY + 'px'
				}, {
					duration: this.options.animationDuration, easing: this.options.animationEasing, always: function() {
						$us.$window.off( 'keydown mousewheel DOMMouseScroll touchstart', this._events.cancel );
						this.isScrolling = false;
						indicateActive();
					}.bind( this )
				} );
				// Allow user to stop scrolling manually
				$us.$window.on( 'keydown mousewheel DOMMouseScroll touchstart', this._events.cancel );
			} else {
				$us.$htmlBody.stop( true, false ).scrollTop( newY );
				indicateActive();
			}
		},

		/**
		 * Cancel scroll
		 */
		cancel: function() {
			$us.$htmlBody.stop( true, false );
		},

		/**
		 * Add new waypoint
		 *
		 * @param {jQuery} $elm object with the element
		 * @param {mixed} offset Offset from bottom of screen in pixels ('100') or percents ('20%')
		 * @param {Function} fn The function that will be called
		 */
		addWaypoint: function( $elm, offset, fn ) {
			$elm = ( $elm instanceof $ ) ? $elm : $( $elm );
			if ( $elm.length == 0 ) {
				return;
			}
			if ( typeof offset != 'string' || offset.indexOf( '%' ) == - 1 ) {
				// Not percent: using pixels
				offset = parseInt( offset );
			}
			var waypoint = {
				$elm: $elm, offset: offset, fn: fn
			};
			this._countWaypoint( waypoint );
			this.waypoints.push( waypoint );
		},

		/**
		 *
		 * @param {Object} waypoint
		 * @private
		 */
		_countWaypoint: function( waypoint ) {
			var elmTop = waypoint.$elm.offset().top, winHeight = $us.$window.height();
			if ( typeof waypoint.offset == 'number' ) {
				// Offset is defined in pixels
				waypoint.scrollPos = elmTop - winHeight + waypoint.offset;
			} else {
				// Offset is defined in percents
				waypoint.scrollPos = elmTop - winHeight + winHeight * parseInt( waypoint.offset ) / 100;
			}
		},

		/**
		 *
		 * @param {Object} stickyRow
		 * @private
		 */
		_countStickyRow: function( stickyRow ) {
			var isSticky = false;
			if ( stickyRow.$row.hasClass( 'sticky' ) ) {
				isSticky = true;
				stickyRow.$row.removeClass( 'sticky' );
			}
			stickyRow.disableWidth = ( stickyRow.$row.data( 'sticky-disable-width' ) !== undefined ) ? stickyRow.$row.data( 'sticky-disable-width' ) : 900;
			stickyRow.originalTop = stickyRow.$row.offset().top;
			stickyRow.top = stickyRow.$row.offset().top - this._canvasTopOffset;
			if ( $us.header.headerTop === undefined || ( $us.header.headerTop > 0 && stickyRow.top > $us.header.headerTop ) ) {
				stickyRow.top = stickyRow.top - $us.header.scrolledOccupiedHeight;
			}
			stickyRow.height = stickyRow.$row.outerHeight();
			if ( stickyRow.$row.is( '.l-main .l-section:first-child' ) ) {
				stickyRow.height = stickyRow.height - parseInt( stickyRow.$row.css( 'padding-top' ) );
			}
			if ( isSticky ) {
				stickyRow.$row.addClass( 'sticky' );
			}
		},

		/**
		 * Scroll handler
		 */
		scroll: function() {
			var scrollTop = parseInt( $us.$window.scrollTop() );
			if ( ! this.isScrolling ) {
				var activeHash;
				for ( var hash in this.blocks ) {
					if ( ! this.blocks.hasOwnProperty( hash ) ) {
						continue;
					}
					if ( scrollTop >= ( this.blocks[ hash ].top - 1 ) && scrollTop < ( this.blocks[ hash ].bottom - 1 ) ) {
						activeHash = hash;
						break;
					}
				}
				this._indicatePosition( activeHash );
			}
			// Handling sticky rows
			for ( var i = 0; i < this.stickyRows.length; i ++ ) {
				if ( this.stickyRows[ i ].top < scrollTop && window.innerWidth > this.stickyRows[ i ].disableWidth ) {
					this.stickyRows[ i ].$row.addClass( 'sticky' );
					this.stickyRows[ i ].$rowGap.css( 'height', this.stickyRows[ i ].height );
				} else {
					this.stickyRows[ i ].$row.removeClass( 'sticky' );
					this.stickyRows[ i ].$rowGap.css( 'height', null );
				}
			}
			// Handling waypoints
			for ( var i = 0; i < this.waypoints.length; i ++ ) {
				if ( this.waypoints[ i ].scrollPos < scrollTop ) {
					this.waypoints[ i ].fn( this.waypoints[ i ].$elm );
					this.waypoints.splice( i, 1 );
					i --;
				}
			}
		},

		/**
		 * Resize handler
		 */
		resize: function() {
			// Delaying the resize event to prevent glitches
			setTimeout( function() {
				this._countAllPositions();
				this.scroll();
			}.bind( this ), 150 );
			this._countAllPositions();
			this.scroll();
		}
	};

	$( function() {
		$us.scroll = new USScroll( $us.scrollOptions || {} );
	} );

}( jQuery );