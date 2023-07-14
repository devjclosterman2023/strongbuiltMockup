(function( $ ) {
	'use strict';	

	/**
	 * Initialize the player.
	 *
	 * @since 3.3.1
	 */
	function init( $elem ) {
		// Vars		
		var player_id = $elem.data( 'id' );		
		var settings  = $elem.data( 'params' );				

		// Player
		var init_player = function() {
			// Is iframe?
			if ( 'iframe' == settings.type ) {
				var embed_url = $elem.data( 'src' );
				$( '#' + player_id ).replaceWith( '<iframe src="' + embed_url + '" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>' );
				
				update_views_count( settings );
			} else {
				var video  = document.getElementById( player_id );
				var player = new Plyr( video, settings.player );

				var $plyr  = $elem.find( '.plyr' );
				
				// Dispatch an event
				var options = {	
					player_id: player_id,				
					player: player,
					settings: settings						
				};

				$elem.trigger( 'player.init', options );

				// Update views count
				var viewed = false;

				player.on( 'playing', function( e ) {
					if ( ! viewed ) {
						viewed = true;
						update_views_count( settings );
					}
				});

				// On ended
				player.on( 'ended', function() {
					$plyr.addClass( 'plyr--stopped' );
				});

				// HLS
				if ( settings.hasOwnProperty( 'hls' ) ) {
					const hls = new Hls();
					hls.loadSource( settings.hls );
					hls.attachMedia( video );
					window.hls = hls;
					
					// Handle changing captions
					player.on( 'languagechange', () => {
						setTimeout( () => hls.subtitleTrack = player.currentTrack, 50 );
					});
				}

				// Dash
				if ( settings.hasOwnProperty( 'dash' ) ) {
					const dash = dashjs.MediaPlayer().create();
					dash.initialize( video, settings.dash, true );
					window.dash = dash;
				}

				// Share / Embed				
				if ( settings.hasOwnProperty( 'share' ) || settings.hasOwnProperty( 'embed' ) ) {					
					var share_button = document.createElement( 'button' );
					share_button.type = 'button';
					share_button.className = 'plyr__controls__item plyr__control plyr__share-embed-button aiovg-icon-share';
				
					$plyr.append( share_button );
						
					var $share_button = $( share_button );
					var $close_button = $elem.find( '.plyr__share-embed-modal-close-button' );
					var $modal = $elem.find( '.plyr__share-embed-modal' );

					$modal.appendTo( $plyr ).show();	

					// Show Modal
					var was_playing = false;

					$share_button.on( 'click', function() {
						if ( player.playing ) {
							was_playing = true;
							player.pause();
						} else {
							was_playing = false;
						}                    

						$share_button.hide();						
						$modal.addClass( 'fadein' );				
					});

					// Hide Modal
					$close_button.on( 'click', function() {
						if ( was_playing ) {
							player.play();
						}

						$modal.removeClass( 'fadein' );
						setTimeout(function() {
							$share_button.show(); 
						}, 500 );				                           	
					});

					// Copy Embedcode
					if ( settings.hasOwnProperty( 'embed' ) ) {
						$elem.find( '.plyr__embed-code-input' ).on( 'focus', function() {
							$( this ).select();	
							document.execCommand( 'copy' );					
						});
					}				
				}

				// Logo
				if ( settings.hasOwnProperty( 'logo' ) ) {
					var style = 'bottom:50px; left:' +  settings.logo.margin +'px;';

					switch ( settings.logo.position ) {
						case 'topleft':
							style = 'top:' +  settings.logo.margin +'px; left:' +  settings.logo.margin +'px;';
							break;
						case 'topright':
							style = 'top:' + settings.logo.margin +'px; right:' + settings.logo.margin +'px;';
							break;					
						case 'bottomright':
							style = 'bottom:50px; right:' +  settings.logo.margin +'px;';
							break;		
					}

					var logo = document.createElement( 'div' );
					logo.className = 'plyr__logo';
					logo.innerHTML = '<a href="' + settings.logo.link + '" style="' + style + '" target="_blank"><img src="' + settings.logo.image + '" alt="" /></a>';

					$plyr.append( logo );	
				}

				// Custom ContextMenu
				if ( settings.hasOwnProperty( 'contextmenu' ) ) {
					if ( ! $( '#aiovg-contextmenu' ).length ) {
						$( 'body' ).append( '<div id="aiovg-contextmenu" style="display: none;"><div class="aiovg-contextmenu-content">' + settings.contextmenu.content + '</div></div>' );
					}

					var contextmenu = document.getElementById( 'aiovg-contextmenu' );
					var timeout_handler = '';
					
					$plyr.on( 'contextmenu', function( e ) {						
						if ( 3 === e.keyCode || 3 === e.which ) {
							e.preventDefault();
							e.stopPropagation();
							
							var width = contextmenu.offsetWidth,
								height = contextmenu.offsetHeight,
								x = e.pageX,
								y = e.pageY,
								doc = document.documentElement,
								scrollLeft = ( window.pageXOffset || doc.scrollLeft ) - ( doc.clientLeft || 0 ),
								scrollTop = ( window.pageYOffset || doc.scrollTop ) - ( doc.clientTop || 0 ),
								left = x + width > window.innerWidth + scrollLeft ? x - width : x,
								top = y + height > window.innerHeight + scrollTop ? y - height : y;
					
							contextmenu.style.display = '';
							contextmenu.style.left = left + 'px';
							contextmenu.style.top = top + 'px';
							
							clearTimeout( timeout_handler );
							timeout_handler = setTimeout(function() {
								contextmenu.style.display = 'none';
							}, 1500 );				
						}														 
					});
					
					if ( settings.hasOwnProperty( 'logo' ) ) {
						contextmenu.addEventListener( 'click', function() {
							window.location.href = settings.logo.link;
						});
					}
					
					document.addEventListener( 'click', function() {
						contextmenu.style.display = 'none';								 
					});					
				}
			}
		}		

		// ...
		if ( settings.cookie_consent ) {
			$elem.find( '.aiovg-privacy-consent-button' ).on( 'click', function() {
				$( this ).html( '...' );

				if ( 'iframe' != settings.type ) {
					settings.player.autoplay = true;
				}

				set_cookie(function() {
					init_player();
					$elem.find( '.aiovg-privacy-wrapper' ).remove();

					$( '.aiovg-player-standard' ).trigger( 'cookieConsent', { player_id: player_id } );
				});
			});

			$elem.on( 'cookieConsent', function( event, args ) {
				if ( player_id != args.player_id ) {
					init_player();
					$elem.find( '.aiovg-privacy-wrapper' ).remove();
				}
			});
		} else {
			init_player();
		}
	}

	/**
	 * Set GDPR cookie
	 *
	 * @since 3.3.1
	 */
	var set_cookie = function( callback ) {		
		var data = {
			'action': 'aiovg_set_cookie',
			'security': aiovg_player.ajax_nonce
		};

		$.post( 
			aiovg_player.ajax_url, 
			data, 
			function( response ) {
				if ( response.success ) {
					callback();					
				}
			}
		);
	}

	/**
	 * Update video views count.
	 *
	 * @since 3.3.1
	 */
	function update_views_count( settings ) {
		if ( 'aiovg_videos' == settings.post_type ) {
			var data = {
				'action': 'aiovg_update_views_count',
				'post_id': settings.post_id,
				'security': aiovg_player.views_nonce
			};

			$.post( 
				aiovg_player.ajax_url, 
				data, 
				function( response ) {
					// Do nothing
				}
			);
		}
	}

	/**
	 * Refresh iframe player elements upon cookie confirmation.
	 *
	 * @since 3.3.1
	 */
	window.onmessage = function( e ) {
		if ( e.data == 'aiovg-cookie-consent' ) {
			$( '.aiovg-player-iframe iframe' ).each(function() {
				var url = $( this ).attr( 'src' );
				if ( url.indexOf( 'refresh=1' ) === -1 ) {
                    var separator = url.indexOf( '?' ) > -1 ? '&' : '?';
					$( this ).attr( 'src', url + separator + 'refresh=1' );
				}
			});
		}
	};

	/**
	 * Called when the page has loaded.
	 *
	 * @since 3.3.1
	 */
	$(function() {
		
		// Update views count for the non-iframe embeds
		$( '.aiovg-player-raw' ).each(function() {
			var settings = $( this ).data( 'params' );
			update_views_count( settings );
		});

		// Initialize the player
		$( '.aiovg-player-standard' ).each(function() {
			init( $( this ) );
		});		

	});

})( jQuery );
