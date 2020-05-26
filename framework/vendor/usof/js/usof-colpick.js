/*
 * usof_colpick Color Picker
 * Copyright 2013 Jose Vargas. Licensed under GPL license.
 * Based on Stefan Petre's Color Picker www.eyecon.ro, dual licensed under the MIT and GPL licenses
 */

( function( $ ) {
	var usof_colpick = function() {
		var
			tpl = '<div class="usof_colpick">' +
				'<div class="usof_colpick_color">' +
				'<div class="usof_colpick_color_overlay1"><div class="usof_colpick_color_overlay2">' +
				'<div class="usof_colpick_selector_outer"><div class="usof_colpick_selector_inner"></div></div>' +
				'</div></div>' +
				'</div>' +
				'<div class="usof_colpick_hue"><div class="usof_colpick_hue_arrs"></div></div>' +
				'<div class="usof_colpick_alpha"><div class="usof_colpick_alpha_arrs"></div></div>' +
				'<div class="usof_colpick_gradient"></div>' +
				'<div class="usof_colpick_hex_field"><div class="usof_colpick_field_letter">#</div><input type="text" maxlength="6" size="6" /></div>' +
				// Rgb
				'<div class="usof_colpick_rgb_r usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">R</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// rGb
				'<div class="usof_colpick_rgb_g usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">G</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// rgB
				'<div class="usof_colpick_rgb_b usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">B</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// Hsb
				'<div class="usof_colpick_hsb_h usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">H</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// hSb
				'<div class="usof_colpick_hsb_s usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">S</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// hsB
				'<div class="usof_colpick_hsb_b usof_colpick_field">' +
				'<div class="usof_colpick_field_letter">B</div><input type="text" maxlength="3" size="3" />' +
				'</div>' +
				// Alpha
				'<div class="usof_colpick_field"><input type="text" maxlength="3" size="3" /></div>' +
				'</div>',
			defaults = {
				showEvent: 'focus',
				onShow: function() {
				},
				onBeforeShow: function() {
				},
				onHide: function() {
				},
				onChange: function() {
				},
				onSubmit: function() {
				},
				colorScheme: 'light',
				livePreview: true,
				flat: false,
				layout: 'full',
				height: 156
			},
			transparentImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAMFJREFUeNrsVlsOhCAM7Jg9j97/GHChMTxcC9UPTNatif0AIxhmOlMqSMpIhBiG9k/y43gP+P8Bn/FPkIbiPZQhTRSafXDKoMDL4DNqWn7fNccMlAYF66ZE/8avBvu0qUG1sPvKLQOFlo0GigfvcVGI8cQbNr8plnlxehflPELlMbMhYDzu7zaluj1onM7GL0/sB+ic7pGBxcXu+QkuqhhrJasartXQ9iqPGtQKOO20lKscbtbAIAXk8J/HEYNVgAEAHShEyUuW684AAAAASUVORK5CYII=",
			//Fill the inputs of the plugin
			fillRGBAFields = function( hsba, cal ) {
				var rgb = hsbaToRgba( hsba );
				$( cal ).data( 'usof_colpick' ).fields
					.eq( 1 ).val( rgb.r ).end()
					.eq( 2 ).val( rgb.g ).end()
					.eq( 3 ).val( rgb.b ).end()
					.eq( 7 ).val( rgb.a ).end();
			},
			fillHSBAFields = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).fields
					.eq( 4 ).val( Math.round( hsba.h ) ).end()
					.eq( 5 ).val( Math.round( hsba.s ) ).end()
					.eq( 6 ).val( Math.round( hsba.b ) ).end()
					.eq( 7 ).val( hsba.a ).end();
			},
			fillHexFields = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).fields.eq( 0 ).val( hsbaToHex( hsba ) );
			},
			//Set the round selector position
			setSelector = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).selector.css( 'backgroundColor', hsbaToHex( {
					h: hsba.h,
					s: 100,
					b: 100
				} ) );
				$( cal ).data( 'usof_colpick' ).selectorIndic.css( {
					left: parseInt( $( cal ).data( 'usof_colpick' ).height * hsba.s / 100, 10 ),
					top: parseInt( $( cal ).data( 'usof_colpick' ).height * ( 100 - hsba.b ) / 100, 10 )
				} );
			},
			//Set the hue selector position
			setHue = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).hue.css( 'top', parseInt( $( cal ).data( 'usof_colpick' ).height - $( cal ).data( 'usof_colpick' ).height * hsba.h / 360, 10 ) );
			},
			// Set the alpha selector position
			setAlpha = function( hsba, cal ) {
				var usof_colpick = $( cal ).data( 'usof_colpick' ),
					rgba = hsbaToRgba( hsba );
				if ( hsba.a === undefined ) {
					hsba.a = 1.;
				}
				usof_colpick.alpha.css( 'top', parseInt( $( cal ).data( 'usof_colpick' ).height * ( 1. - hsba.a ) ) );
				var alphaStyle = 'background: linear-gradient(to bottom, rgb(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ') 0%, ';
				alphaStyle += 'rgba(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ', 0) 100%) repeat scroll 0% 0%, transparent url("';
				alphaStyle += transparentImage + '") repeat scroll 0% 0%/16px;';
				usof_colpick.alphaContainer.attr( 'style', alphaStyle )
			},
			//Set current and new colors
			setCurrentColor = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).currentColor.css( 'backgroundColor', hsbaToHex( hsba ) );
			},
			setNewColor = function( hsba, cal ) {
				$( cal ).data( 'usof_colpick' ).newColor.css( 'backgroundColor', hsbaToHex( hsba ) );
			},
			//Called when the new color is changed
			change = function( ev ) {
				var cal = $( this ).parent().parent(), col;
				if ( this.parentNode.className.indexOf( '_hex' ) > 0 ) {
					cal.data( 'usof_colpick' ).color = col = hexToHsba( fixHex( this.value ) );
					fillRGBAFields( col, cal.get( 0 ) );
					fillHSBAFields( col, cal.get( 0 ) );
				} else if ( this.parentNode.className.indexOf( '_hsb' ) > 0 ) {
					col = fixHSBA( {
						h: parseInt( cal.data( 'usof_colpick' ).fields.eq( 4 ).val(), 10 ),
						s: parseInt( cal.data( 'usof_colpick' ).fields.eq( 5 ).val(), 10 ),
						b: parseInt( cal.data( 'usof_colpick' ).fields.eq( 6 ).val(), 10 ),
						a: parseFloat( cal.data( 'usof_colpick' ).fields.eq( 7 ).val() )
					} );
					cal.data( 'usof_colpick' ).color = col;
					fillRGBAFields( col, cal.get( 0 ) );
					fillHexFields( col, cal.get( 0 ) );
				} else {
					cal.data( 'usof_colpick' ).color = col = rgbaToHsba( fixRGBA( {
						r: parseInt( cal.data( 'usof_colpick' ).fields.eq( 1 ).val(), 10 ),
						g: parseInt( cal.data( 'usof_colpick' ).fields.eq( 2 ).val(), 10 ),
						b: parseInt( cal.data( 'usof_colpick' ).fields.eq( 3 ).val(), 10 ),
						a: parseFloat( cal.data( 'usof_colpick' ).fields.eq( 7 ).val() )
					} ) );
					fillHexFields( col, cal.get( 0 ) );
					fillHSBAFields( col, cal.get( 0 ) );
				}
				setSelector( col, cal.get( 0 ) );
				setHue( col, cal.get( 0 ) );
				setAlpha( col, cal.get( 0 ) );
				setNewColor( col, cal.get( 0 ) );
				cal.data( 'usof_colpick' ).onChange.apply( cal.parent(), [
					col,
					hsbaToHex( col ),
					hsbaToRgba( col ),
					cal.data( 'usof_colpick' ).el,
					0
				] );
			},
			//Change style on blur and on focus of inputs
			blur = function( ev ) {
				$( this ).parent().removeClass( 'usof_colpick_focus' );
			},
			focus = function() {
				$( this ).parent().parent().data( 'usof_colpick' ).fields.parent().removeClass( 'usof_colpick_focus' );
				$( this ).parent().addClass( 'usof_colpick_focus' );
			},
			//Increment/decrement arrows functions
			moveIncrement = function( ev ) {
				ev.data.field.val( Math.max( 0, Math.min( ev.data.max, parseInt( ev.data.val - ev.pageY + ev.data.y, 10 ) ) ) );
				if ( ev.data.preview ) {
					change.apply( ev.data.field.get( 0 ), [ true ] );
				}
				return false;
			},
			upIncrement = function( ev ) {
				change.apply( ev.data.field.get( 0 ), [ true ] );
				ev.data.el.removeClass( 'usof_colpick_slider' ).find( 'input' ).focus();
				$( document ).off( 'mouseup', upIncrement );
				$( document ).off( 'mousemove', moveIncrement );
				return false;
			},
			//Hue slider functions
			downHue = function( ev ) {
				ev.preventDefault ? ev.preventDefault() : ev.returnValue = false;
				var current = {
					cal: $( this ).parent(),
					y: $( this ).offset().top
				};
				$( document ).on( 'mouseup touchend', current, upHue );
				$( document ).on( 'mousemove touchmove', current, moveHue );

				var pageY = ( ( ev.type == 'touchstart' ) ? ev.originalEvent.changedTouches[ 0 ].pageY : ev.pageY );
				change.apply(
					current.cal.data( 'usof_colpick' )
						.fields.eq( 4 ).val( parseInt( 360 * ( current.cal.data( 'usof_colpick' ).height - ( pageY - current.y ) ) / current.cal.data( 'usof_colpick' ).height, 10 ) )
						.get( 0 ),
					[ current.cal.data( 'usof_colpick' ).livePreview ]
				);
				return false;
			},
			moveHue = function( ev ) {
				var pageY = ( ( ev.type == 'touchmove' ) ? ev.originalEvent.changedTouches[ 0 ].pageY : ev.pageY );
				change.apply(
					ev.data.cal.data( 'usof_colpick' )
						.fields.eq( 4 ).val( parseInt( 360 * ( ev.data.cal.data( 'usof_colpick' ).height - Math.max( 0, Math.min( ev.data.cal.data( 'usof_colpick' ).height, ( pageY - ev.data.y ) ) ) ) / ev.data.cal.data( 'usof_colpick' ).height, 10 ) )
						.get( 0 ),
					[ ev.data.preview ]
				);
				return false;
			},
			upHue = function( ev ) {
				fillRGBAFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				fillHexFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				$( document ).off( 'mouseup touchend', upHue );
				$( document ).off( 'mousemove touchmove', moveHue );
				return false;
			},
			//Alpha slider function
			downAlpha = function( ev ) {
				ev.preventDefault ? ev.preventDefault() : ev.returnValue = false;
				var current = {
					cal: $( this ).parent(),
					y: $( this ).offset().top
				};
				$( document ).on( 'mouseup touchend', current, upAlpha );
				$( document ).on( 'mousemove touchmove', current, moveAlpha );

				var pageY = ( ( ev.type == 'touchstart' ) ? ev.originalEvent.changedTouches[ 0 ].pageY : ev.pageY );
				change.apply(
					current.cal.data( 'usof_colpick' )
						.fields.eq( 7 ).val( ( current.cal.data( 'usof_colpick' ).height - ( pageY - current.y ) ) / current.cal.data( 'usof_colpick' ).height )
						.get( 0 ),
					[ current.cal.data( 'usof_colpick' ).livePreview ]
				);
				return false;
			},
			moveAlpha = function( ev ) {
				var pageY = ( ( ev.type == 'touchmove' ) ? ev.originalEvent.changedTouches[ 0 ].pageY : ev.pageY );
				change.apply(
					ev.data.cal.data( 'usof_colpick' )
						.fields.eq( 7 ).val( ( ev.data.cal.data( 'usof_colpick' ).height - ( pageY - ev.data.y ) ) / ev.data.cal.data( 'usof_colpick' ).height )
						.get( 0 ),
					[ ev.data.preview ]
				);
				return false;
			},
			upAlpha = function( ev ) {
				fillRGBAFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				fillHexFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				$( document ).off( 'mouseup touchend', upAlpha );
				$( document ).off( 'mousemove touchmove', moveAlpha );
				return false;
			},
			//Color selector functions
			downSelector = function( ev ) {
				ev.preventDefault ? ev.preventDefault() : ev.returnValue = false;
				var current = {
					cal: $( this ).parent(),
					pos: $( this ).offset()
				};
				current.preview = current.cal.data( 'usof_colpick' ).livePreview;

				$( document ).on( 'mouseup touchend', current, upSelector );
				$( document ).on( 'mousemove touchmove', current, moveSelector );

				var pageX, pageY;
				if ( ev.type == 'touchstart' ) {
					pageX = ev.originalEvent.changedTouches[ 0 ].pageX;
					pageY = ev.originalEvent.changedTouches[ 0 ].pageY;
				} else {
					pageX = ev.pageX;
					pageY = ev.pageY;
				}

				change.apply(
					current.cal.data( 'usof_colpick' ).fields
						.eq( 6 ).val( parseInt( 100 * ( current.cal.data( 'usof_colpick' ).height - ( pageY - current.pos.top ) ) / current.cal.data( 'usof_colpick' ).height, 10 ) ).end()
						.eq( 5 ).val( parseInt( 100 * ( pageX - current.pos.left ) / current.cal.data( 'usof_colpick' ).height, 10 ) )
						.get( 0 ),
					[ current.preview ]
				);
				return false;
			},
			moveSelector = function( ev ) {
				var pageX, pageY;
				if ( ev.type == 'touchmove' ) {
					pageX = ev.originalEvent.changedTouches[ 0 ].pageX;
					pageY = ev.originalEvent.changedTouches[ 0 ].pageY;
				} else {
					pageX = ev.pageX;
					pageY = ev.pageY;
				}

				change.apply(
					ev.data.cal.data( 'usof_colpick' ).fields
						.eq( 6 ).val( parseInt( 100 * ( ev.data.cal.data( 'usof_colpick' ).height - Math.max( 0, Math.min( ev.data.cal.data( 'usof_colpick' ).height, ( pageY - ev.data.pos.top ) ) ) ) / ev.data.cal.data( 'usof_colpick' ).height, 10 ) ).end()
						.eq( 5 ).val( parseInt( 100 * ( Math.max( 0, Math.min( ev.data.cal.data( 'usof_colpick' ).height, ( pageX - ev.data.pos.left ) ) ) ) / ev.data.cal.data( 'usof_colpick' ).height, 10 ) )
						.get( 0 ),
					[ ev.data.preview ]
				);
				return false;
			},
			upSelector = function( ev ) {
				fillRGBAFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				fillHexFields( ev.data.cal.data( 'usof_colpick' ).color, ev.data.cal.get( 0 ) );
				$( document ).off( 'mouseup touchend', upSelector );
				$( document ).off( 'mousemove touchmove', moveSelector );
				return false;
			},
			//Submit button
			clickSubmit = function( ev ) {
				var cal = $( this ).parent();
				var col = cal.data( 'usof_colpick' ).color;
				cal.data( 'usof_colpick' ).origColor = col;
				setCurrentColor( col, cal.get( 0 ) );
				cal.data( 'usof_colpick' ).onSubmit( col, hsbaToHex( col ), hsbaToRgba( col ), cal.data( 'usof_colpick' ).el );
			},
			//Show/Hide the color picker
			show = function( ev ) {
				if ( ev ) {ev.stopPropagation();}
				var cal = $( '#' + $( this ).data( 'usof_colpickId' ) ),
					pos, top, bottomSpace, that = this,
					calWrap = $( this ).parent( '.usof-color' ).find( '.usof_colpick_wrap' )[ 0 ],
					calWrapH = $( calWrap ).height();
				cal.data( 'usof_colpick' ).onBeforeShow.apply( this, [ cal.get( 0 ) ] );
				pos = $( this ).offset();
				top = this.offsetHeight;
				bottomSpace = document.body.clientHeight - ( pos.top - window.pageYOffset );
				if ( bottomSpace < calWrapH ) {
					top = 0 - calWrapH;
				}
				$( calWrap ).css( { top: top + 'px' } );
				cal.data( 'usof_colpick' ).onShow.apply( this, [ cal.get( 0 ) ] );
			},
			hide = function( ev ) {
				if ( ev ) {ev.stopPropagation();}
				var cal = $( '#' + $( this ).data( 'usof_colpickId' ) );
				cal.data( 'usof_colpick' ).onHide.apply( this, [ cal.get( 0 ) ] );
				$( 'html' ).off( 'mousedown', hide );
			},
			//Fix the values if the user enters a negative or high value
			fixHSBA = function( hsba ) {
				if ( hsba.a === undefined ) {
					hsba.a = 1.;
				}
				return {
					h: Math.min( 360, Math.max( 0, hsba.h ) ),
					s: Math.min( 100, Math.max( 0, hsba.s ) ),
					b: Math.min( 100, Math.max( 0, hsba.b ) ),
					a: Math.min( 1., Math.max( 0., hsba.a ) )
				};
			},
			fixRGBA = function( rgba ) {
				if ( rgba.a === undefined ) {
					rgba.a = 1.;
				}
				return {
					r: Math.min( 255, Math.max( 0, rgba.r ) ),
					g: Math.min( 255, Math.max( 0, rgba.g ) ),
					b: Math.min( 255, Math.max( 0, rgba.b ) ),
					a: Math.min( 1., Math.max( 0., rgba.a ) )
				};
			},
			fixHex = function( hex ) {
				if ( hex.substr( 0, 5 ) == 'rgba(' ) {
					var parts = hex.substring( 5, s.length - 1 ).split( ',' ).map( parseFloat );
					if ( parts.length == 4 ) {
						return hex;
					}
				}
				var len = 6 - hex.length;
				if ( len > 0 ) {
					var o = [];
					for ( var i = 0; i < len; i ++ ) {
						o.push( '0' );
					}
					o.push( hex );
					hex = o.join( '' );
				}
				return hex;
			};
		return {
			init: function( opt ) {
				opt = $.extend( {}, defaults, opt || {} );
				var gradient;
				//Set color
				if ( typeof opt.color == 'string' ) {
					if ( isGradient( opt.color ) ) {
						gradient = gradientParser( opt.color );
						opt.color = hexToHsba( gradient.hex );
					} else {
						opt.color = hexToHsba( opt.color );
					}
				} else if ( opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined ) {
					opt.color = rgbaToHsba( opt.color );
				} else if ( opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined ) {
					opt.color = fixHSBA( opt.color );
				} else {
					return this;
				}
				//For each selected DOM element
				return this.each( function() {
					//If the element does not have an ID
					if ( !$( this ).data( 'usof_colpickId' ) ) {
						var options = $.extend( {}, opt );
						options.origColor = opt.color;
						//Generate and assign a random ID
						var id = 'collorpicker_' + parseInt( Math.random() * 100000 );
						$( this ).data( 'usof_colpickId', id );
						//Set the tpl's ID and get the HTML
						var cal = $( tpl ).attr( 'id', id );
						//Add class according to layout
						cal.addClass( 'usof_colpick_' + options.layout + ( options.submit ? '' : ' usof_colpick_' + options.layout + '_ns' ) );
						//Add class if the color scheme is not default
						if ( options.colorScheme != 'light' ) {
							cal.addClass( 'usof_colpick_' + options.colorScheme );
						}
						//Setup submit button
						cal.find( 'div.usof_colpick_submit' ).html( options.submitText ).click( clickSubmit );
						//Setup input fields
						options.fields = cal.find( 'input' ).change( change ).blur( blur ).focus( focus );
						//Setup hue selector
						options.selector = cal.find( 'div.usof_colpick_color' ).on( 'mousedown touchstart', downSelector );
						options.selectorIndic = options.selector.find( 'div.usof_colpick_selector_outer' );
						//Store parts of the plugin
						options.el = this;
						options.hue = cal.find( 'div.usof_colpick_hue_arrs' );
						var huebar = options.hue.parent();
						options.alphaContainer = cal.find( 'div.usof_colpick_alpha' );
						options.alpha = cal.find( 'div.usof_colpick_alpha_arrs' );

						//Paint the hue bar
						var UA = navigator.userAgent.toLowerCase();
						var isIE = navigator.appName === 'Microsoft Internet Explorer';
						var IEver = isIE ? parseFloat( UA.match( /msie ([0-9]{1,}[\.0-9]{0,})/ )[ 1 ] ) : 0;
						var ngIE = ( isIE && IEver < 10 );
						var stops = [
							'#ff0000',
							'#ff0080',
							'#ff00ff',
							'#8000ff',
							'#0000ff',
							'#0080ff',
							'#00ffff',
							'#00ff80',
							'#00ff00',
							'#80ff00',
							'#ffff00',
							'#ff8000',
							'#ff0000'
						];
						if ( ngIE ) {
							var i, div;
							for ( i = 0; i <= 11; i ++ ) {
								div = $( '<div></div>' ).attr( 'style', 'height:8.333333%; filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=' + stops[ i ] + ', endColorstr=' + stops[ i + 1 ] + '); -ms-filter: "progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=' + stops[ i ] + ', endColorstr=' + stops[ i + 1 ] + ')";' );
								huebar.append( div );
							}
						} else {
							var stopList = stops.join( ',' );
							huebar.attr( 'style', 'background:linear-gradient(to bottom,' + stopList + '); ' );
						}
						cal.find( 'div.usof_colpick_hue' ).on( 'mousedown touchstart', downHue );
						cal.find( 'div.usof_colpick_alpha' ).on( 'mousedown touchstart', downAlpha );
						options.newColor = cal.find( 'div.usof_colpick_new_color' );
						options.currentColor = cal.find( 'div.usof_colpick_current_color' );
						//Store options and fill with default color
						cal.data( 'usof_colpick', options );
						fillRGBAFields( options.color, cal.get( 0 ) );
						fillHSBAFields( options.color, cal.get( 0 ) );
						fillHexFields( options.color, cal.get( 0 ) );
						setHue( options.color, cal.get( 0 ) );
						setAlpha( options.color, cal.get( 0 ) );
						setSelector( options.color, cal.get( 0 ) );
						setCurrentColor( options.color, cal.get( 0 ) );
						setNewColor( options.color, cal.get( 0 ) );
						//Append to body if flat=false, else show in place
						if ( options.flat ) {
							cal.appendTo( this ).show();
							cal.css( {
								position: 'relative',
								display: 'block'
							} );
						} else {
							cal.appendTo( $( this ).parent().children( '.usof_colpick_wrap' ) );
							if ( options.showEvent == 'focus' ) {
								$( this ).on( 'focus', function( ev ) {
									var $that = $( this );
									setTimeout( function() {
										$that.select();
									}, 5 );
									show.call( this, ev );
								} );

								$( this ).on( 'blur', function( ev ) {
									hide.call( this, ev );
								} );
							} else {
								$( this ).on( options.showEvent, function( ev ) {
									show.call( this, ev, options.showEvent );
								} );
							}
						}
					}
				} );
			},
			showPicker: function() {
				return this.each( function() {
					if ( $( this ).data( 'usof_colpickId' ) ) {
						show.apply( this );
					}
				} );
			},
			hidePicker: function() {
				return this.each( function() {
					if ( $( this ).data( 'usof_colpickId' ) ) {
						hide.apply( this );
					}
				} );
			},
			//Sets a color as new and current (default)
			setColor: function( col, setCurrent ) {
				var gradient;
				setCurrent = ( typeof setCurrent === "undefined" ) ? 1 : setCurrent;
				if ( typeof col == 'string' ) {
					if ( isGradient( col ) ) {
						gradient = gradientParser( col );
						col = hexToHsba( gradient.hex );
					} else {
						col = hexToHsba( col );
					}
				} else if ( col.r != undefined && col.g != undefined && col.b != undefined ) {
					col = rgbaToHsba( col );
				} else if ( col.h != undefined && col.s != undefined && col.b != undefined ) {
					col = fixHSBA( col );
				} else {
					return this;
				}
				return this.each( function() {
					if ( $( this ).data( 'usof_colpickId' ) ) {
						var cal = $( '#' + $( this ).data( 'usof_colpickId' ) );
						cal.data( 'usof_colpick' ).color = col;
						cal.data( 'usof_colpick' ).origColor = col;
						fillRGBAFields( col, cal.get( 0 ) );
						fillHSBAFields( col, cal.get( 0 ) );
						fillHexFields( col, cal.get( 0 ) );
						setHue( col, cal.get( 0 ) );
						setAlpha( col, cal.get( 0 ) );
						setSelector( col, cal.get( 0 ) );

						setNewColor( col, cal.get( 0 ) );
						cal.data( 'usof_colpick' ).onChange.apply( cal.parent(), [
							col,
							hsbaToHex( col ),
							hsbaToRgba( col ),
							cal.data( 'usof_colpick' ).el,
							1,
							gradient,
						] );
						if ( setCurrent ) {
							setCurrentColor( col, cal.get( 0 ) );
						}
					}
				} );
			}
		};
	}();

	//Color space convertions
	var hexToRgba = function( hex ) {
		if ( hex.substr( 0, 5 ) == 'rgba(' ) {
			var parts = hex.substring( 5, hex.length - 1 ).split( ',' ).map( parseFloat );
			if ( parts.length == 4 ) {
				return { r: parts[ 0 ], g: parts[ 1 ], b: parts[ 2 ], a: parts[ 3 ] };
			}
		}
		if ( hex.length == 3 ) {
			hex = hex.charAt( 0 ) + hex.charAt( 0 ) + hex.charAt( 1 ) + hex.charAt( 0 ) + hex.charAt( 2 ) + hex.charAt( 2 );
		}
		hex = parseInt( ( ( hex.indexOf( '#' ) > - 1 ) ? hex.substring( 1 ) : hex ), 16 );
		return { r: hex >> 16, g: ( hex & 0x00FF00 ) >> 8, b: ( hex & 0x0000FF ), a: 1. };
	};

	var gradientParser = function( color ) {
		if ( m = /^linear-gradient\(([\D\d]+)\);?$/.exec( color ) ) {
			var gradient = m[ 1 ].split( ',' ),
				directions = [ 'to', 'top', 'right', 'bottom', 'left', 'turn', 'deg' ],
				index,
				colors = {
					colors: [],
					gradient: color,
				};

			// Find gradient direction
			for ( var i = 0; i < directions.length; i ++ ) {
				index = gradient[ 0 ].indexOf( directions[ i ] );
				if ( index != - 1 ) {
					colors.direction = gradient[ 0 ];
				}
			}

			// Find color values
			for ( var i = 0; i < gradient.length; i ++ ) {
				gradient[ i ] = gradient[ i ].trim();

				// Look for hex values
				var hex = gradient[ i ].indexOf( '#' );
				if ( hex !== - 1 ) {
					var normalizedHex = normalizeHex( gradient[ i ].replace( '#', '' ) );
					colors.colors.push( normalizedHex );
				}

				// Look for hex RGB
				var rgb = gradient[ i ].indexOf( 'rgb(' );
				if ( rgb !== - 1 ) {
					var rgbColor = {};

					rgbColor.r = parseInt( gradient[ i ].replace( 'rgb(', '' ).trim() );
					rgbColor.g = parseInt( gradient[ i + 1 ].trim() );
					rgbColor.b = parseInt( gradient[ i + 2 ].replace( ')', '' ).trim() );
					colors.colors.push( rgbColor );
				}

				// Look for hex RGBa
				var rgba = gradient[ i ].indexOf( 'rgba(' );
				if ( rgba !== - 1 ) {
					var rgbaColor = {};

					rgbaColor.r = parseInt( gradient[ i ].replace( 'rgba(', '' ).trim() );
					rgbaColor.g = parseInt( gradient[ i + 1 ].trim() );
					rgbaColor.b = parseInt( gradient[ i + 2 ].trim() );
					rgbaColor.a = parseFloat( gradient[ i + 3 ].trim().replace( ')', '' ).trim() );
					colors.colors.push( rgbaColor );
				}
			}

			if ( typeof colors.colors[ 0 ] == 'string' ) {
				colors.hex = colors.colors[ 0 ];
			} else {
				// Can be returned as rgba string if rgba object is passed
				colors.hex = rgbaToHex( colors.colors[ 0 ] );
			}

			return colors;
		} else {
			return false;
		}
	};

	// Hex should come without leading "#"
	var normalizeHex = function( hex ) {
		var hashString;
		if ( hex.length === 3 ) {
			hex = '#' + hex[ 0 ] + hex[ 0 ] + hex[ 1 ] + hex[ 1 ] + hex[ 2 ] + hex[ 2 ];
		} else if ( hex.length <= 6 ) {
			hashString = hex.split( '' );
			while ( hashString.length < 6 ) {
				hashString.unshift( '0' );
			}
			hex = '#' + hashString.join( '' );
		}

		return hex;
	};

	var isGradient = function( color ) {
		if ( m = /^linear-gradient\(.+\)$/.exec( color ) ) {
			return true
		} else {
			return false;
		}
	};


	var hexToHsba = function( hex ) {
		return rgbaToHsba( hexToRgba( hex ) );
	};
	var rgbaToHsba = function( rgba ) {
		var hsba = { h: 0, s: 0, b: 0 };
		var min = Math.min( rgba.r, rgba.g, rgba.b );
		var max = Math.max( rgba.r, rgba.g, rgba.b );
		var delta = max - min;
		hsba.b = max;
		hsba.s = max != 0 ? 255 * delta / max : 0;
		if ( hsba.s != 0 ) {
			if ( rgba.r == max ) {
				hsba.h = ( rgba.g - rgba.b ) / delta;
			} else if ( rgba.g == max ) {
				hsba.h = 2 + ( rgba.b - rgba.r ) / delta;
			} else {
				hsba.h = 4 + ( rgba.r - rgba.g ) / delta;
			}
		} else {
			hsba.h = - 1;
		}
		hsba.h *= 60;
		if ( hsba.h < 0 ) {
			hsba.h += 360;
		}
		hsba.s *= 100 / 255;
		hsba.b *= 100 / 255;
		hsba.a = rgba.a;
		return hsba;
	};
	var hsbaToRgba = function( hsba ) {
		var rgb = {};
		var h = hsba.h;
		var s = hsba.s * 255 / 100;
		var v = hsba.b * 255 / 100;
		if ( s == 0 ) {
			rgb.r = rgb.g = rgb.b = v;
		} else {
			var t1 = v;
			var t2 = ( 255 - s ) * v / 255;
			var t3 = ( t1 - t2 ) * ( h % 60 ) / 60;
			if ( h == 360 ) {
				h = 0;
			}
			if ( h < 60 ) {
				rgb.r = t1;
				rgb.b = t2;
				rgb.g = t2 + t3
			} else if ( h < 120 ) {
				rgb.g = t1;
				rgb.b = t2;
				rgb.r = t1 - t3
			} else if ( h < 180 ) {
				rgb.g = t1;
				rgb.r = t2;
				rgb.b = t2 + t3
			} else if ( h < 240 ) {
				rgb.b = t1;
				rgb.r = t2;
				rgb.g = t1 - t3
			} else if ( h < 300 ) {
				rgb.b = t1;
				rgb.g = t2;
				rgb.r = t2 + t3
			} else if ( h < 360 ) {
				rgb.r = t1;
				rgb.g = t2;
				rgb.b = t1 - t3
			} else {
				rgb.r = 0;
				rgb.g = 0;
				rgb.b = 0
			}
		}
		return { r: Math.round( rgb.r ), g: Math.round( rgb.g ), b: Math.round( rgb.b ), a: hsba.a };
	};
	var rgbaToHex = function( rgba ) {
		if ( rgba.a !== undefined && rgba.a < 1. ) {
			return 'rgba(' + rgba.r + ',' + rgba.g + ',' + rgba.b + ',' + Math.round( rgba.a * 100 ) / 100 + ')';
		}
		var hex = [
			rgba.r.toString( 16 ),
			rgba.g.toString( 16 ),
			rgba.b.toString( 16 )
		];
		$.each( hex, function( nr, val ) {
			if ( val.length == 1 ) {
				hex[ nr ] = '0' + val;
			}
		} );
		return '#' + hex.join( '' );
	};
	var hsbaToHex = function( hsba ) {
		return rgbaToHex( hsbaToRgba( hsba ) );
	};
	$.fn.extend( {
		usof_colpick: usof_colpick.init,
		usof_colpickSetColor: usof_colpick.setColor,
		usof_colpickShow: usof_colpick.showPicker,
		usof_colpickHide: usof_colpick.hidePicker
	} );
	$.extend( {
		usof_colpick: {
			normalizeHex: normalizeHex,
			rgbaToHex: rgbaToHex,
			rgbaToHsb: rgbaToHsba,
			hsbaToHex: hsbaToHex,
			hsbaToRgba: hsbaToRgba,
			hexToHsba: hexToHsba,
			hexToRgba: hexToRgba,
			gradientParser: gradientParser,
			isGradient: isGradient,
		}
	} );
} )( jQuery );
