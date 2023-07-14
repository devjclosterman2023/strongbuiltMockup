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
				settings.player.html5 = {
					vhs: {
						overrideNative: ! videojs.browser.IS_ANY_SAFARI,
					}
				};

				var player = videojs( player_id, settings.player );				

				var overlays = [];
				
				// Trigger ready event
				var options = {					
					id: player_id, // Backward compatibility to 3.3.0
					player_id: player_id,
					config: settings, // Backward compatibility to 3.3.0
					settings: settings,
					player: player					
				};

				$elem.trigger( 'player.init', options );

				// Fired when the player is ready
				player.ready(function() {
					$elem.removeClass( 'vjs-waiting' );
				});

				// On metadata loaded
				player.one( 'loadedmetadata', function() {
					// Standard quality selector
					$elem.find( '.vjs-quality-selector .vjs-menu-item' ).each(function( e ) {
						var $this = $( this );

						var text = $this.find( '.vjs-menu-item-text' ).html();
						var resolution = text.replace( /\D/g, '' );

						if ( resolution >= 2160 ) {
							$this.append( '<span class="vjs-quality-menu-item-sub-label">4K</span>' );
						} else if ( resolution >= 720 ) {
							$this.append( '<span class="vjs-quality-menu-item-sub-label">HD</span>' );
						}
					});

					// Add support for SRT
					if ( settings.hasOwnProperty( 'tracks' ) ) {
						for ( var i = 0, max = settings.tracks.length; i < max; i++ ) {
							var track = settings.tracks[ i ];

							var mode = '';
							if ( 0 == i && 1 == settings.cc_load_policy ) {
								mode = 'showing';
							}

							if ( /srt/.test( track.src.toLowerCase() ) ) {
								add_srt_text_track( player, track, mode );
							} else {
								var track_obj = {
									kind: 'subtitles',
									src: track.src,									
									label: track.label,
									srclang: track.srclang
								};

								if ( '' != mode ) {
									track_obj.mode = mode;
								}

								player.addRemoteTextTrack( track_obj, true ); 
							}					               
						}
					}              
				});

				// Fired the first time a video is played
				var viewed = false;

				player.on( 'play', function( e ) {
					if ( ! viewed ) {
						viewed = true;
						update_views_count( settings );
					}

					$( '.aiovg-player-standard' ).trigger( 'playRequested', { player_id: player_id } );
				});

				$elem.on( 'playRequested', function( event, args ) {
					if ( player_id != args.player_id ) {
						player.pause();
					}
				});

				player.on( 'playing', function() {
					player.trigger( 'controlsshown' );
				});
	
				player.on( 'ended', function() {
					player.trigger( 'controlshidden' );
				});

				// Standard quality selector
				player.on( 'qualitySelected', function( event, source ) {
					var resolution = source.label.replace( /\D/g, '' );

					player.removeClass( 'vjs-4k' );
					player.removeClass( 'vjs-hd' );

					if ( resolution >= 2160 ) {
						player.addClass( 'vjs-4k' );
					} else if ( resolution >= 720 ) {
						player.addClass( 'vjs-hd' );
					}
				});

				// HLS quality selector
				var src = player.src();

				if ( /.m3u8/.test( src ) || /.mpd/.test( src ) ) {
					if ( settings.player.controlBar.children.indexOf( 'qualitySelector' ) !== -1 ) {
						player.qualityMenu();
					};
				};

				// Offset
				var offset = {};

				if ( settings.hasOwnProperty( 'start' ) ) {
					offset.start = settings.start;
				}

				if ( settings.hasOwnProperty( 'end' ) ) {
					offset.end = settings.end;
				}
				
				if ( Object.keys( offset ).length > 1 ) {
					offset.restart_beginning = false;
					player.offset( offset );
				}				

				// Share / Embed
				if ( settings.hasOwnProperty( 'share' ) || settings.hasOwnProperty( 'embed' ) ) {
					overlays.push({
						content: '<a href="javascript:void(0)" class="vjs-share-embed-button" style="text-decoration:none;"><span class="vjs-icon-share"></span></a>',
						class: 'vjs-share',
						align: 'top-right',
						start: 'controlsshown',
						end: 'controlshidden',
						showBackground: false					
					});					
				}

				// Download
				if ( settings.hasOwnProperty( 'download' ) ) {
					var __class = 'vjs-download';

					if ( settings.hasOwnProperty( 'share' ) || settings.hasOwnProperty( 'embed' ) ) {
						__class += ' vjs-has-share';
					}

					overlays.push({
						content: '<a href="' + settings.download.url + '" class="vjs-download-button" style="text-decoration:none;" target="_blank"><span class="aiovg-icon-download"></span></a>',
						class: __class,
						align: 'top-right',
						start: 'controlsshown',
						end: 'controlshidden',
						showBackground: false					
					});
				}

				// Logo
				if ( settings.hasOwnProperty( 'logo' ) ) {
					var attributes = [];
					attributes['src'] = settings.logo.image;

					if ( settings.logo.margin ) {
						settings.logo.margin = settings.logo.margin - 5;
					}

					var align = 'bottom-left';
					attributes['style'] = 'margin: ' + settings.logo.margin + 'px;';
					switch ( settings.logo.position ) {
						case 'topleft':
							align = 'top-left';
							attributes['style'] = 'margin: ' + settings.logo.margin + 'px;';
							break;
						case 'topright':
							align = 'top-right';
							attributes['style'] = 'margin: ' + settings.logo.margin + 'px;';
							break;					
						case 'bottomright':
							align = 'bottom-right';
							attributes['style'] = 'margin: ' + settings.logo.margin + 'px;';
							break;				
					}

					if ( settings.logo.link ) {
						attributes['onclick'] = "window.location.href='" + settings.logo.link + "';";
					}

					overlays.push({
						content: '<img ' +  merge_attributes( attributes ) + ' alt="" />',
						class: 'vjs-logo',
						align: align,
						start: 'controlsshown',
						end: 'controlshidden',
						showBackground: false					
					});
				}

				// Overlay
				if ( overlays.length > 0 ) {
					player.overlay({
						content: '',
						overlays: overlays
					});

					if ( settings.hasOwnProperty( 'share' ) || settings.hasOwnProperty( 'embed' ) ) {
						var options = {};
						options.content = $elem.find( '.vjs-share-embed' ).get(0);
						options.temporary = false;
	
						var ModalDialog = videojs.getComponent( 'ModalDialog' );
						var modal = new ModalDialog( player, options );
						modal.addClass( 'vjs-modal-dialog-share-embed' );
	
						player.addChild( modal );
	
						var was_playing = true;
						$elem.find( '.vjs-share-embed-button' ).on( 'click', function() {
							was_playing = ! player.paused;
							modal.open();						
						});
	
						modal.on( 'modalclose', function() {
							if ( was_playing ) {
								player.play();
							}						
						});
					}
	
					if ( settings.hasOwnProperty( 'embed' ) ) {
						$elem.find( '.vjs-copy-embed-code' ).on( 'focus', function() {
							$( this ).select();	
							document.execCommand( 'copy' );					
						});
					}
				}

				// Keyboard hotkeys
				if ( settings.hotkeys ) {
					player.hotkeys();
				}

				// Custom contextmenu
				if ( settings.hasOwnProperty( 'contextmenu' ) ) {
					if ( ! $( '#aiovg-contextmenu' ).length ) {
						$( 'body' ).append( '<div id="aiovg-contextmenu" style="display: none;"><div class="aiovg-contextmenu-content">' + settings.contextmenu.content + '</div></div>' );
					}
		
					var contextmenu = document.getElementById( 'aiovg-contextmenu' );
					var timeout_handler = '';
					
					$( '#' + player_id ).on( 'contextmenu', function( e ) {						
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
	 * Merge attributes.
	 *
	 * @since 3.3.1
	 */
	function merge_attributes( attributes ) {
		var str = '';

		for ( var key in attributes ) {
			str += ( key + '="' + attributes[ key ] + '" ' );
		}

		return str;
	}

	/**
	 * Convert SRT to WebVTT.
	 *
	 * @since 3.3.1
	 */
	function srt_to_webvtt( data ) {
		// Remove dos newlines
		var srt = data.replace( /\r+/g, '' );

		// Trim white space start and end
		srt = srt.replace( /^\s+|\s+$/g, '' );

		// Get cues
		var cuelist = srt.split( '\n\n' );
		var result = "";

		if ( cuelist.length > 0 ) {
		  result += "WEBVTT\n\n";
		  for ( var i = 0; i < cuelist.length; i = i+1 ) {
			  result += convert_srt_cue( cuelist[ i ] );
		  }
		}

		return result;
  	}

  	function convert_srt_cue( caption ) {
		// Remove all html tags for security reasons
		// srt = srt.replace( /<[a-zA-Z\/][^>]*>/g, '' );

		var cue = "";
		var s = caption.split( /\n/ );

		// Concatenate muilt-line string separated in array into one
		while ( s.length > 3 ) {
			for ( var i = 3; i < s.length; i++ ) {
				s[2] += "\n" + s[ i ]
			}
			s.splice( 3, s.length - 3 );
		}

		var line = 0;

		// Detect identifier
		if ( ! s[0].match( /\d+:\d+:\d+/ ) && s[1].match( /\d+:\d+:\d+/ ) ) {
		  cue += s[0].match( /\w+/ ) + "\n";
		  line += 1;
		}

		// Get time strings
		if ( s[ line ].match( /\d+:\d+:\d+/ ) ) {
		  // Convert time string
		  var m = s[1].match( /(\d+):(\d+):(\d+)(?:,(\d+))?\s*--?>\s*(\d+):(\d+):(\d+)(?:,(\d+))?/ );
		  if ( m ) {
				cue += m[1] + ":" + m[2] + ":" + m[3] + "." + m[4] + " --> " + m[5] + ":" + m[6] + ":" + m[7] + "." + m[8] + "\n";
				line += 1;
		  } else {
				// Unrecognized timestring
				return "";
		  }
		} else {
		  // File format error or comment lines
		  return "";
		}

		// Get cue text
		if ( s[ line ] ) {
		  cue += s[ line ] + "\n\n";
		}

		return cue;
  	}

	function add_srt_text_track( player, track, mode ) {
		var xmlhttp;

		if ( window.XMLHttpRequest ) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject( 'Microsoft.XMLHTTP' );
		};
		
		xmlhttp.onreadystatechange = function() {				
			if ( 4 == xmlhttp.readyState && 200 == xmlhttp.status ) {					
				if ( xmlhttp.responseText ) {
					var vtt_text = srt_to_webvtt( xmlhttp.responseText );

					if ( '' != vtt_text ) {
						var vtt_blob = new Blob([ vtt_text ], { type : 'text/vtt' });
						var blob_url = URL.createObjectURL( vtt_blob );

						var track_obj = {
							kind: 'subtitles',
							src: blob_url,							
							label: track.label,
							srclang: track.srclang							
						};

						if ( '' != mode ) {
							track_obj.mode = mode;
						}

						player.addRemoteTextTrack( track_obj, true );
					}
				}						
			}					
		};	

		xmlhttp.open( 'GET', track.src, true );
		xmlhttp.send();							
	}		

	/**
	 * Set GDPR cookie.
	 *
	 * @since 3.3.1
	 */
	function set_cookie( callback ) {		
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

		// Custom error message
		if ( typeof videojs !== "undefined" ) {
			videojs.hook( 'beforeerror', function( player, err ) {
				var error = player.error();

				// Prevent current error from being cleared out
				if ( err === null ) {
					return error;
				}

				// But allow changing to a new error
				if ( err.code == 2 || err.code == 4 ) {
					var src = player.src();

					if ( /.m3u8/.test( src ) || /.mpd/.test( src ) ) {
						return {
							code: err.code,
							message: aiovg_player.i18n.stream_not_found
						};
					}
				}
				
				return err;
			});
		}

	});

})( jQuery );
