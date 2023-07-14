<?php

/**
 * Video Player.
 *
 * @link    https://plugins360.com
 * @since   2.4.0
 *
 * @package All_In_One_Video_Gallery
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * AIOVG_Player class.
 *
 * @since 2.4.0
 */
class AIOVG_Player {

	/**
	 * The only instance of the class.
	 *
	 * @since  2.4.0
	 * @static
	 * @var    AIOVG_Player	 
	 */
	public static $instance;

	/**
	 * Current player uid.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    int	 
	 */
	protected $uid;

	/**
	 * Create a new instance of the main class.
	 *
	 * @since  2.4.0
	 * @static
	 * @return AIOVG_Player
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
            self::$instance = new self();
        }

		return self::$instance;
	}

	/**
	 * Get things started.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->uid = 0;
	}

	/**
	 * Get the player HTML.
	 *
	 * @since  2.4.0
	 * @param  int    $post_id Post ID.
 	 * @param  array  $atts    Player configuration data.
 	 * @return string $html    Player HTML.
	 */
	public function create( $post_id, $atts ) {
		$post_id = (int) $post_id;
		$params  = $this->get_params( $post_id, $atts );
		$html    = '';

		switch ( $params['player'] ) {
			case 'amp':
				$html = $this->get_player_amp( $params );
				break;

			case 'raw':
				wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

				$json_array = array(
					'type'      => 'raw',
					'post_id'   => (int) $params['post_id'],
					'post_type' => 'aiovg_videos'
				);
 
				$html = sprintf(
					'<div class="aiovg-player-raw" data-params=\'%s\'>%s</div>',
					wp_json_encode( $json_array ),
					$params['player_html']				
				);
				break;

			case 'iframe':
				if ( ! empty( $params['embed_url'] ) ) {
					if ( ! empty( $params['cookie_consent'] ) ) {
						wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );
					}

					$html = sprintf( 
						'<div class="aiovg-player-container" style="max-width: %s;">', 
						( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
					);	
	
					$html .= sprintf( 
						'<div class="aiovg-player aiovg-player-iframe" style="padding-bottom: %s%%;">',
						(float) $params['ratio']					);	

					$html .= sprintf( 
						'<iframe src="%s" title="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>', 
						esc_attr( $params['embed_url'] ),
						esc_attr( $params['post_title'] ) 
					);	

					$html .= '</div>';
					$html .= '</div>';
				}
				break;	

			default:
				if ( ! empty( $params['embed_url'] ) ) {
					wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );
					wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

					$json_array = array(
						'type'           => 'iframe',
						'post_id'        => (int) $params['post_id'],
						'post_type'      => sanitize_text_field( $params['post_type'] ),				
						'cookie_consent' => (int) $params['cookie_consent']
					);					

					$html = sprintf( 
						'<div class="aiovg-player-container" style="max-width: %s;">', 
						( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
					);

					$html .= sprintf( 
						'<div class="aiovg-player aiovg-player-embed aiovg-player-standard" style="padding-bottom: %s%%;" data-id="%s" data-src="%s" data-params=\'%s\'>',
						(float) $params['ratio'],
						esc_attr( $params['uid'] ),
						( isset( $params['embed_url'] ) ? esc_attr( $params['embed_url'] ) : '' ),
						wp_json_encode( $json_array )
					);

					// Player
					$html .= sprintf(
						'<div id="%s"></div>',
						esc_attr( $params['uid'] )				
					);

					// GDPR
					if ( ! empty( $params['cookie_consent'] ) ) {
						$consent_message = apply_filters( 'aiovg_translate_strings', $params['consent_message'], 'consent_message' );
						$consent_button_label = apply_filters( 'aiovg_translate_strings', $params['consent_button_label'], 'consent_button_label' );

						$html .= sprintf(
							'<div class="aiovg-privacy-wrapper" %s><div class="aiovg-privacy-consent-block"><div class="aiovg-privacy-consent-message">%s</div><div class="aiovg-privacy-consent-button">%s</div></div></div>',
							( ! empty( $params['poster'] ) ? 'style="background-image: url(' . esc_attr( $params['poster'] ) . ');"' : '' ),
							wp_kses_post( trim( $consent_message ) ),
							esc_html( $consent_button_label )
						);
					}

					$html .= '</div>';
					$html .= '</div>';					
				} else {					
					if ( 'vidstack' == $params['player'] ) {
						$html = $this->get_player_vidstack( $params );					
					} else {
						$html = $this->get_player_videojs( $params );	
					}
				}									
		}
	
		return apply_filters( 'aiovg_player_html', $html, $params );
	}
	
	/**
	 * Get the videojs player.
	 * 
	 * @since  3.3.1
	 * @access private
 	 * @param  array   $params Player params.
 	 * @return string          Player HTML.
	 */
	private function get_player_videojs( $params ) {
		$player_html = '';
		$json_array  = array();		

		$settings = array(
			'type'           => 'html5',
			'post_id'        => (int) $params['post_id'],
			'post_type'      => sanitize_text_field( $params['post_type'] ),
			'cookie_consent' => (int) $params['cookie_consent'],
			'cc_load_policy' => (int) $params['cc_load_policy'],			
			'hotkeys'        => (int) $params['hotkeys'],
			'player'         => array(
				'controlBar'                => array(),
				'playbackRates'             => array( 0.5, 0.75, 1, 1.5, 2 ),
				'suppressNotSupportedError' => true
			)
		);		

		// Video Sources			
		$types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash', 'youtube', 'vimeo', 'dailymotion' );
		$sources = array();

		foreach ( $types as $type ) {
			if ( ! empty( $params[ $type ] ) ) {
				$mime_type = "video/{$type}";
				$label = '';

				switch ( $type ) {
					case 'mp4':
						$ext = aiovg_get_file_ext( $params[ $type ] );
						if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
							$ext = 'mp4';
						}

						$mime_type = "video/{$ext}";
	
						if ( ! empty( $params['quality_level'] ) ) {
							$label = $params['quality_level'];
						}
						break;
					case 'hls':
						$mime_type = 'application/x-mpegurl';
						break;
					case 'dash':
						$mime_type = 'application/dash+xml';
						break;
				}

				$sources[ $type ] = array(
					'type' => $mime_type,
					'src'  => $params[ $type ]
				);

				if ( ! empty( $label ) ) {
					$sources[ $type ]['label'] = $label;
				}
			}
		}

		if ( isset( $params['sources'] ) ) {
			foreach ( $params['sources'] as $source ) {
				if ( ! empty( $source['quality'] ) && ! empty( $source['src'] ) ) {	
					$ext = aiovg_get_file_ext( $source['src'] );
					if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
						$ext = 'mp4';
					}

					$label = $source['quality'];

					$sources[ $label ] = array(
						'type'  => "video/{$ext}",
						'src'   => $source['src'],
						'label' => $label
					);
				}
			}
		}

		$params['sources'] = apply_filters( 'aiovg_player_sources', $sources, $params ); // Backward compatibility to 3.3.0
		$params['sources'] = apply_filters( 'aiovg_videojs_player_sources', $sources, $params );

		// Video Tracks
		$tracks = array();

		if ( ! empty( $params['tracks'] ) ) {
			$tracks = $params['tracks'];

			$has_srt_found = 0;

			foreach ( $tracks as $index => $track ) {
				$ext = pathinfo( $track['src'], PATHINFO_EXTENSION );
				if ( 'srt' == strtolower( $ext ) ) {
					$has_srt_found = 1;			
					break;
				}
			}

			if ( $has_srt_found ) {
				$settings['tracks'] = $tracks;
				$tracks = array();
			}
		}

		$params['tracks'] = apply_filters( 'aiovg_player_tracks', $tracks, $params ); // Backward compatibility to 3.3.0
		$params['tracks'] = apply_filters( 'aiovg_videojs_player_tracks', $tracks, $params );

		// Video Attributes
		$attributes = array(
			'id'       => esc_attr( $params['uid'] ),
			'class'    => 'vjs-fill',
			'style'    => 'width: 100%; height: 100%;',
			'controls' => '',
			'preload'  => esc_attr( $params['preload'] )
		);

		if ( ! empty( $params['autoplay'] ) ) {
			$settings['player']['autoplay'] = true;
		}

		if ( ! empty( $params['loop'] ) ) {
			$attributes['loop'] = '';
		}

		if ( ! empty( $params['muted'] ) ) {
			$attributes['muted'] = '';
		}		

		if ( ! empty( $params['playsinline'] ) ) {
			$attributes['playsinline'] = '';
		}

		if ( ! empty( $params['poster'] ) ) {
			$attributes['poster'] = esc_attr( $params['poster'] );
		}

		if ( ! empty( $params['copyright_text'] ) ) {
			$attributes['controlsList']  = 'nodownload';
			$attributes['oncontextmenu'] = 'return false;';
		}

		$params['attributes'] = apply_filters( 'aiovg_player_attributes', $attributes, $params ); // Backward compatibility to 3.3.0
		$params['attributes'] = apply_filters( 'aiovg_videojs_player_attributes', $attributes, $params );

		// Player Settings
		$controls = array( 
			'playpause'  => 'PlayToggle', 
			'current'    => 'CurrentTimeDisplay', 
			'progress'   => 'progressControl', 
			'duration'   => 'durationDisplay',
			'tracks'     => 'SubtitlesButton',
			'audio'      => 'AudioTrackButton',				
			'speed'      => 'PlaybackRateMenuButton', 
			'quality'    => 'qualitySelector', 
			'volume'     => 'VolumePanel', 
			'fullscreen' => 'fullscreenToggle'
		);
		
		foreach ( $controls as $index => $control ) {		
			if ( ! in_array( $index, $params['controls'] ) ) {	
				unset( $controls[ $index ] );	
			} else {
				if ( 'tracks' == $index ) {
					$params['controls'][] = 'audio';
				}
			}	
		}
		
		$settings['player']['controlBar']['children'] = array_values( $controls );
		if ( empty( $settings['player']['controlBar']['children'] ) ) {
			$params['attributes']['class'] = 'vjs-no-control-bar';
		}

		if ( ! empty( $params['share'] ) ) {
			$settings['share'] = 1;
		}

		if ( ! empty( $params['embed'] ) ) {
			$settings['embed'] = 1;
		}		

		if ( ! empty( $params['download'] ) ) {
			$settings['download'] = array(
				'url' => esc_url( $params['download_url'] )
			);
		}

		if ( ! empty( $params['show_logo'] ) ) {
			$settings['logo'] = array(
				'image'    => aiovg_sanitize_url( $params['logo_image'] ),
				'link'     => esc_url_raw( $params['logo_link'] ),
				'position' => sanitize_text_field( $params['logo_position'] ),
				'margin'   => (int) $params['logo_margin']
			);
		}

		if ( ! empty( $params['copyright_text'] ) ) {
			$settings['contextmenu'] = array(
				'content' => apply_filters( 'aiovg_translate_strings', sanitize_text_field( htmlspecialchars( $params['copyright_text'] ) ), 'copyright_text' )
			);
		}

		if ( isset( $params['sources']['youtube'] ) ) {
			$settings['player']['techOrder'] = array( 'youtube' );
			$settings['player']['youtube']   = array( 
				'iv_load_policy' => 3 
			);

			parse_str( $params['sources']['youtube']['src'], $queries );

			if ( isset( $queries['start'] ) ) {
				$settings['start'] = (int) $queries['start'];
			}

			if ( isset( $queries['t'] ) ) {
				$settings['start'] = (int) $queries['t'];
			}

			if ( isset( $queries['end'] ) ) {
				$settings['end'] = (int) $queries['end'];
			}
		}
		
		if ( isset( $params['sources']['vimeo'] ) ) {
			$settings['player']['techOrder'] = array( 'vimeo2' );

			if ( strpos( $params['sources']['vimeo']['src'], 'player.vimeo.com' ) !== false ) {
				$oembed = aiovg_get_vimeo_oembed_data( $params['sources']['vimeo']['src'] );
				$params['sources']['vimeo']['src'] = 'https://vimeo.com/' . $oembed['video_id'];
			}
		}
		
		if ( isset( $params['sources']['dailymotion'] ) ) {
			if ( ! isset( $params['attributes']['poster'] ) ) {
				$settings['player']['bigPlayButton'] = false;
			}
			$settings['player']['techOrder'] = array( 'dailymotion' );
		}

		$params['settings'] = apply_filters( 'aiovg_player_settings', $settings, $params ); // Backward compatibility to 3.3.0
		$params['settings'] = apply_filters( 'aiovg_videojs_player_settings', $settings, $params );

		$json_array = $params['settings'];

		// Dependencies
		wp_enqueue_style( 
			AIOVG_PLUGIN_SLUG . '-videojs', 
			AIOVG_PLUGIN_URL . 'vendor/videojs/video-js.min.css', 
			array(), 
			'7.21.1', 
			'all' 
		);

		if ( in_array( 'qualitySelector', $params['settings']['player']['controlBar']['children'] ) ) {
			if ( isset( $params['sources']['mp4'] ) || isset( $params['sources']['webm'] ) || isset( $params['sources']['ogv'] ) ) {
				wp_enqueue_style( 
					AIOVG_PLUGIN_SLUG . '-quality-selector', 
					AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/quality-selector/quality-selector.min.css', 
					array(), 
					'1.2.5', 
					'all' 
				);
			}

			if ( isset( $params['sources']['hls'] ) || isset( $params['sources']['dash'] ) ) {
				wp_enqueue_style( 
					AIOVG_PLUGIN_SLUG . '-videojs-quality-menu', 
					AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/videojs-quality-menu/videojs-quality-menu.min.css', 
					array(), 
					'2.0.1',
					'all' 
				);
			}
		}

		if ( isset( $params['settings']['share'] ) || isset( $params['settings']['embed'] ) || isset( $params['settings']['download'] ) || isset( $params['settings']['logo'] ) ) {
			wp_enqueue_style( 
				AIOVG_PLUGIN_SLUG . '-overlay', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/overlay/videojs-overlay.min.css', 
				array(), 
				'2.1.5', 
				'all' 
			);
		}

		wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );

		wp_enqueue_script( 
			AIOVG_PLUGIN_SLUG . '-videojs', 
			AIOVG_PLUGIN_URL . 'vendor/videojs/video.min.js', 
			array(), 
			'7.21.1', 
			false 
		);

		if ( in_array( 'qualitySelector', $params['settings']['player']['controlBar']['children'] ) ) {
			if ( isset( $params['sources']['mp4'] ) || isset( $params['sources']['webm'] ) || isset( $params['sources']['ogv'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-quality-selector', 
					AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/quality-selector/silvermine-videojs-quality-selector.min.js', 
					array(), 
					'1.2.5', 
					false 
				);
			}

			if ( isset( $params['sources']['hls'] ) || isset( $params['sources']['dash'] ) ) {
				wp_enqueue_script( 
					AIOVG_PLUGIN_SLUG . '-videojs-quality-menu', 
					AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/videojs-quality-menu/videojs-quality-menu.min.js', 
					array(), 
					'2.0.1', 
					false 
				);	
			}
		}

		if ( isset( $params['sources']['youtube'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-youtube', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/youtube/Youtube.min.js', 
				array(), 
				'2.6.1',
				false 
			);
		}

		if ( isset( $params['settings']['start'] ) || isset( $params['settings']['end'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-offset', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/offset/videojs-offset.min.js', 
				array(), 
				'2.1.3',
				false 
			);
		}

		if ( isset( $params['sources']['vimeo'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-vimeo', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/vimeo/videojs-vimeo2.min.js', 
				array(), 
				'2.0.0', 
				false 
			);
		}

		if ( isset( $params['sources']['dailymotion'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-dailymotion', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/dailymotion/videojs-dailymotion.min.js', 
				array(), 
				'2.0.0', 
				false 
			);
		}

		if ( isset( $params['settings']['share'] ) || isset( $params['settings']['embed'] ) || isset( $params['settings']['download'] ) || isset( $params['settings']['logo'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-overlay', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/overlay/videojs-overlay.min.js', 
				array(), 
				'2.1.5', 
				false 
			);
		}

		if ( ! empty( $params['settings']['hotkeys'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-hotkeys', 
				AIOVG_PLUGIN_URL . 'vendor/videojs/plugins/hotkeys/videojs.hotkeys.min.js', 
				array(), 
				'0.2.28', 
				false 
			);
		}
		
		do_action( 'aiovg_player_scripts', $params ); // Backward compatibility to 3.3.0
		do_action( 'aiovg_videojs_player_scripts', $params );
		
		wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

		// Output
		$player_html .= sprintf( '<video-js %s>', aiovg_combine_video_attributes( $params['attributes'] ) );
		
		foreach ( $params['sources'] as $source ) { // Sources
			$player_html .= sprintf( 
				'<source type="%s" src="%s" label="%s" />', 
				esc_attr( $source['type'] ), 
				esc_attr( $source['src'] ),
				( isset( $source['label'] ) ? esc_attr( $source['label'] ) : '' ) 
			);
		}		
		
		foreach ( $params['tracks'] as $index => $track ) { // Tracks
			$player_html .= sprintf( 
				'<track kind="subtitles" src="%s" label="%s" srclang="%s" %s/>', 
				esc_attr( $track['src'] ), 				
				esc_attr( $track['label'] ),
				esc_attr( $track['srclang'] ), 
				( 0 == $index && 1 == (int) $params['settings']['cc_load_policy'] ? 'default' : '' ) 
			);
		}

		$player_html .= '</video-js>';

		// Share / Embed
		if ( isset( $params['settings']['share'] ) || isset( $params['settings']['embed'] ) ) {
			$player_html .= '<div class="vjs-share-embed" style="display: none;">';
			$player_html .= '<div class="vjs-share-embed-content">';

			// Share Buttons
			if ( isset( $params['settings']['share'] ) ) {
				$player_html .= '<div class="vjs-share-buttons">';
				foreach ( $params['share_buttons'] as $button ) {
					$player_html .= sprintf( 
						'<a href="%s" class="vjs-share-button vjs-share-button-%s" target="_blank"><span class="%s"></span></a>',							
						esc_attr( $button['url'] ), 
						esc_attr( $button['service'] ),
						esc_attr( $button['icon'] ) 
					);
				}
				$player_html .= '</div>';
			}

			// Embed Code
			if ( isset( $params['settings']['embed'] ) ) {
				$player_html .= '<div class="vjs-embed-code">';
				$player_html .= '<p>' . esc_html__( 'Paste this code in your HTML page', 'all-in-one-video-gallery' ) . '</p>';
				$player_html .= '<input type="text" class="vjs-copy-embed-code" value="' . htmlspecialchars( $params['embed_code'] ) . '" readonly />';
				$player_html .= '</div>';
			}

			$player_html .= '</div>';
			$player_html .= '</div>';
		}

		// GDPR
		if ( ! empty( $params['settings']['cookie_consent'] ) ) {
			$consent_message = apply_filters( 'aiovg_translate_strings', $params['consent_message'], 'consent_message' );
			$consent_button_label = apply_filters( 'aiovg_translate_strings', $params['consent_button_label'], 'consent_button_label' );

			$player_html .= sprintf(
				'<div class="aiovg-privacy-wrapper" %s><div class="aiovg-privacy-consent-block"><div class="aiovg-privacy-consent-message">%s</div><div class="aiovg-privacy-consent-button">%s</div></div></div>',
				( isset( $params['attributes']['poster'] ) ? 'style="background-image: url(' . esc_attr( $params['attributes']['poster'] ) . ');"' : '' ),
				wp_kses_post( trim( $consent_message ) ),
				esc_html( $consent_button_label )
			);
		}

		// Return
		$html = sprintf( 
			'<div class="aiovg-player-container" style="max-width: %s;">', 
			( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
		);

		$html .= sprintf( 
			'<div class="aiovg-player aiovg-player-videojs aiovg-player-standard vjs-waiting" style="padding-bottom: %s%%;" data-id="%s" data-params=\'%s\'>',
			(float) $params['ratio'],
			esc_attr( $params['attributes']['id'] ),
			wp_json_encode( $json_array )
		);

		$html .= $player_html;

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the vidstack player.
	 * 
	 * @since  3.3.1
	 * @access private
 	 * @param  array   $params Player params.
 	 * @return string          Player HTML.
	 */
	private function get_player_vidstack( $params ) {		
		$player_html = '';
		$json_array  = array();		

		$settings = array(
			'type'           => 'html5',
			'post_id'        => (int) $params['post_id'],
			'post_type'      => sanitize_text_field( $params['post_type'] ),
			'cookie_consent' => (int) $params['cookie_consent'],
			'player'         => array(
				'volume' => 0.5				
			)
		);			

		// Video Sources			
		$types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash', 'youtube', 'vimeo' );
		$sources = array();

		foreach ( $types as $type ) {
			if ( ! empty( $params[ $type ] ) ) {
				$mime_type = "video/{$type}";
				$label = '';

				switch ( $type ) {
					case 'mp4':
						$ext = aiovg_get_file_ext( $params[ $type ] );
						if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
							$ext = 'mp4';
						}

						$mime_type = "video/{$ext}";
	
						if ( ! empty( $params['quality_level'] ) ) {
							$label = $params['quality_level'];
						}
						break;
					case 'hls':
						$mime_type = 'application/x-mpegurl';
						break;
					case 'dash':
						$mime_type = 'application/dash+xml';
						break;
				}

				$sources[ $type ] = array(
					'type' => $mime_type,
					'src'  => $params[ $type ]
				);

				if ( ! empty( $label ) ) {
					$sources[ $type ]['label'] = $label;
				}
			}
		}

		if ( isset( $params['sources'] ) ) {
			foreach ( $params['sources'] as $source ) {
				if ( ! empty( $source['quality'] ) && ! empty( $source['src'] ) ) {	
					$ext = aiovg_get_file_ext( $source['src'] );
					if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
						$ext = 'mp4';
					}

					$label = $source['quality'];

					$sources[ $label ] = array(
						'type'  => "video/{$ext}",
						'src'   => $source['src'],
						'label' => $label
					);
				}
			}
		}

		$params['sources'] = apply_filters( 'aiovg_vidstack_player_sources', $sources, $params );

		// Video Captions
		$tracks = array();

		if ( ! empty( $params['tracks'] ) ) {
			$tracks = $params['tracks'];	
			
			if ( ! empty( $params['cc_load_policy'] ) ) {
				$settings['player']['captions'] = array(
					'active'   => true,
					'language' => 'auto',
					'update'   => false
				); 
			}
		}

		$params['tracks'] = apply_filters( 'aiovg_vidstack_player_tracks', $tracks, $params );

		// Video Attributes
		$attributes = array(
			'id'       => esc_attr( $params['uid'] ),
			'style'    => 'width: 100%; height: 100%',
			'controls' => '',
			'preload'  => esc_attr( $params['preload'] )
		);

		if ( ! empty( $params['autoplay'] ) ) {
			$attributes['autoplay'] = '';
			$settings['player']['autoplay'] = true;
		}

		if ( ! empty( $params['loop'] ) ) {
			$attributes['loop'] = '';
			$settings['player']['loop'] = array( 'active' => true );
		}

		if ( ! empty( $params['muted'] ) ) {
			$attributes['muted'] = '';
			$settings['player']['muted'] = true;
		}	
		
		if ( ! empty( $params['playsinline'] ) ) {
			$attributes['playsinline'] = '';
			$settings['player']['playsinline'] = true;
		}

		if ( ! empty( $params['poster'] ) ) {
			$attributes['data-poster'] = aiovg_sanitize_url( $params['poster'] );
		}			

		$params['attributes'] = apply_filters( 'aiovg_vidstack_player_attributes', $attributes, $params );

		// Player Settings
		$controls = array();
		$controls[] = 'play-large';

		if ( in_array( 'playpause', $params['controls'] ) ) {
			$controls[] = 'play';
		}

		if ( in_array( 'current', $params['controls'] ) ) {
			$controls[] = 'current-time';
		}

		if ( in_array( 'progress', $params['controls'] ) ) {
			$controls[] = 'progress';
		}

		if ( in_array( 'duration', $params['controls'] ) ) {
			$controls[] = 'duration';
		}

		if ( in_array( 'volume', $params['controls'] ) ) {
			$controls[] = 'mute';
			$controls[] = 'volume';
		}

		if ( in_array( 'tracks', $params['controls'] ) ) {
			$controls[] = 'captions';			
		} else {
			if ( empty( $params['cc_load_policy'] ) ) {
				$params['tracks'] = array();
			}
		}

		if ( in_array( 'quality', $params['controls'] ) || in_array( 'tracks', $params['controls'] ) || in_array( 'speed', $params['controls'] ) ) {
			$controls[] = 'settings';
		
			$settings['player']['settings'] = array();

			if ( in_array( 'quality', $params['controls'] ) ) {
				$settings['player']['settings'][] = 'quality';
			}

			if ( in_array( 'tracks', $params['controls'] ) ) {
				$settings['player']['settings'][] = 'captions';
			}

			if ( in_array( 'speed', $params['controls'] ) ) {
				$settings['player']['settings'][] = 'speed';

				$settings['player']['speed'] = array(
					'selected' => 1,
					'options'  => array( 0.5, 0.75, 1, 1.5, 2 )
				);
			}
		}

		if ( ! empty( $params['download'] ) ) {
			$controls[] = 'download';

			$settings['player']['urls'] = array(
				'download' => esc_url( $params['download_url'] )
			);
		}

		if ( in_array( 'fullscreen', $params['controls'] ) ) {
			$controls[] = 'fullscreen';
		}
		
		$settings['player']['controls'] = $controls;

		if ( ! empty( $params['hotkeys'] ) ) {
			$settings['player']['keyboard'] = array(
				'focused' => true,
				'global'  => false
			);
		}

		if ( ! empty( $params['share'] ) ) {
			$settings['share'] = 1;
		}

		if ( ! empty( $params['embed'] ) ) {
			$settings['embed'] = 1;
		}			

		if ( ! empty( $params['show_logo'] ) ) {
			$settings['logo'] = array(
				'image'    => aiovg_sanitize_url( $params['logo_image'] ),
				'link'     => ! empty( $params['logo_link'] ) ? esc_url_raw( $params['logo_link'] ) : 'javascript:void(0)',
				'position' => sanitize_text_field( $params['logo_position'] ),
				'margin'   => ! empty( $params['logo_margin'] ) ? (int) $params['logo_margin'] : 15
			);
		}

		if ( ! empty( $params['copyright_text'] ) ) {
			$settings['contextmenu'] = array(
				'content' => apply_filters( 'aiovg_translate_strings', sanitize_text_field( htmlspecialchars( $params['copyright_text'] ) ), 'copyright_text' )
			);
		}			
		
		// HLS
		if ( isset( $params['sources']['hls'] ) ) {
			$settings['hls'] = $params['sources']['hls']['src'];

			$settings['player']['captions'] = array(
				'active'   => ! empty( $params['cc_load_policy'] ) ? true : false,
				'language' => 'auto',
				'update'   => true
			); 
		}

		// Dash
		if ( isset( $params['sources']['dash'] ) ) {
			$settings['dash'] = $params['sources']['dash']['src'];

			$settings['player']['captions'] = array(
				'active'   => ! empty( $params['cc_load_policy'] ) ? true : false,
				'language' => 'auto',
				'update'   => true
			); 
		}			

		$params['settings'] = apply_filters( 'aiovg_vidstack_player_settings', $settings, $params );
		$json_array = $params['settings'];

		// Include Dependencies
		wp_enqueue_style( 
			AIOVG_PLUGIN_SLUG . '-vidstack', 
			AIOVG_PLUGIN_URL . 'vendor/vidstack/plyr.css', 
			array(), 
			'3.7.8', 
			'all' 
		);

		wp_enqueue_style( AIOVG_PLUGIN_SLUG . '-public' );

		wp_enqueue_script( 
			AIOVG_PLUGIN_SLUG . '-vidstack', 
			AIOVG_PLUGIN_URL . 'vendor/vidstack/plyr.polyfilled.js', 
			array(), 
			'3.7.8', 
			false 
		);		

		if ( isset( $params['sources']['hls'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-vidstack-hls', 
				AIOVG_PLUGIN_URL . 'vendor/vidstack/hls.min.js', 
				array(), 
				'1.4.3', 
				false 
			);	
		}

		if ( isset( $params['sources']['dash'] ) ) {
			wp_enqueue_script( 
				AIOVG_PLUGIN_SLUG . '-vidstack-dash', 
				AIOVG_PLUGIN_URL . 'vendor/vidstack/dash.all.min.js', 
				array(), 
				AIOVG_PLUGIN_VERSION, 
				false 
			);
		}
		
		do_action( 'aiovg_vidstack_player_scripts', $params );
		
		wp_enqueue_script( AIOVG_PLUGIN_SLUG . '-player' );

		// Output: YouTube
		if ( isset( $params['sources']['youtube'] ) ) {
			$video_id = aiovg_get_youtube_id_from_url( $params['sources']['youtube']['src'] );

			if ( ! empty( $params['settings']['cookie_consent'] ) ) {
				$player_html .= sprintf(
					'<div id="%s" style="%s" data-plyr-provider="youtube" data-plyr-embed-id="%s" data-poster="%s"></div>',
					esc_attr( $params['attributes']['id'] ),
					esc_attr( $params['attributes']['style'] ),
					esc_attr( $video_id ),
					( isset( $params['attributes']['data-poster'] ) ? esc_attr( $params['attributes']['data-poster'] ) : '' )
				);
			} else {
				$embed_url = sprintf(
					'https://www.youtube.com/embed/%s?iv_load_policy=3&amp;modestbranding=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1',
					$video_id
				);
	
				parse_str( $params['sources']['youtube']['src'], $queries );
	
				if ( isset( $queries['start'] ) ) {
					$embed_url .= '&amp;start=' . (int) $queries['start'];
				}
	
				if ( isset( $queries['t'] ) ) {
					$embed_url .= '&amp;start=' . (int) $queries['t'];
				}
	
				if ( isset( $queries['end'] ) ) {
					$embed_url .= '&amp;end=' . (int) $queries['end'];
				}

				$player_html .= sprintf(
					'<div id="%s" class="plyr__video-embed" style="%s;"><iframe src="%s" data-poster="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
					esc_attr( $params['attributes']['id'] ),
					esc_attr( $params['attributes']['style'] ),
					esc_attr( $embed_url ),
					( isset( $params['attributes']['data-poster'] ) ? esc_attr( $params['attributes']['data-poster'] ) : '' )
				);
			}
		}

		// Output: Vimeo
		elseif ( isset( $params['sources']['vimeo'] ) ) {
			$oembed   = aiovg_get_vimeo_oembed_data( $params['sources']['vimeo']['src'] );
			$video_id = $oembed['video_id'];
	
			if ( ! empty( $params['settings']['cookie_consent'] ) ) {
				$player_html .= sprintf(
					'<div id="%s" style="%s" data-plyr-provider="vimeo" data-plyr-embed-id="%s" data-poster="%s"></div>',
					esc_attr( $params['attributes']['id'] ),
					esc_attr( $params['attributes']['style'] ),
					esc_attr( $video_id ),
					( isset( $params['attributes']['data-poster'] ) ? esc_attr( $params['attributes']['data-poster'] ) : '' )
				);
			} else {
				$embed_url = sprintf(
					'https://player.vimeo.com/video/%s?byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media',
					$video_id
				);

				$player_html .= sprintf(
					'<div id="%s" class="plyr__video-embed" style="%s"><iframe src="%s" data-poster="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
					esc_attr( $params['attributes']['id'] ),
					esc_attr( $params['attributes']['style'] ),
					esc_attr( $embed_url ),
					( isset( $params['attributes']['data-poster'] ) ? esc_attr( $params['attributes']['data-poster'] ) : '' )
				);
			}
		}

		// Output: HLS or Dash
		elseif ( isset( $params['sources']['hls'] ) || isset( $params['sources']['dash'] ) ) {
			$player_html .= sprintf( '<video %s></video>', aiovg_combine_video_attributes( $params['attributes'] ) );
		}

		// Output: HTML5 Video
		elseif ( ! empty( $params['sources'] ) ) {
			$player_html .= sprintf( '<video %s>', aiovg_combine_video_attributes( $params['attributes'] ) );
		
			foreach ( $params['sources'] as $source ) { // Sources
				$player_html .= sprintf( 
					'<source type="%s" src="%s" size="%d" />', 
					esc_attr( $source['type'] ), 
					esc_attr( $source['src'] ),
					( isset( $source['label'] ) ? (int) $source['label'] : '' )
				);
			}		
			
			foreach ( $params['tracks'] as $index => $track ) { // Tracks
				$player_html .= sprintf( 
					'<track kind="captions" src="%s" label="%s" srclang="%s" />', 
					esc_attr( $track['src'] ),						 
					esc_attr( $track['label'] ),
					esc_attr( $track['srclang'] )
				);
			}

			$player_html .= '</video>';
		}
		
		// Share / Embed
		if ( isset( $params['settings']['share'] ) || isset( $params['settings']['embed'] ) ) {
			$player_html .= '<div class="plyr__share-embed-modal" style="display: none;">';
			$player_html .= '<div class="plyr__share-embed-modal-content">';

			// Share Buttons
			if ( isset( $params['settings']['share'] ) ) {
				$player_html .= '<div class="plyr__share">';
				foreach ( $params['share_buttons'] as $button ) {
					$player_html .= sprintf( 
						'<a href="%s" class="plyr__share-button plyr__share-button-%s" target="_blank"><span class="%s"></span></a>',							
						esc_attr( $button['url'] ), 
						esc_attr( $button['service'] ),
						esc_attr( $button['icon'] ) 
					);
				}
				$player_html .= '</div>';
			}

			// Embed Code
			if ( isset( $params['settings']['embed'] ) ) {
				$player_html .= '<div class="plyr__embed">';
				$player_html .= '<p>' . esc_html__( 'Paste this code in your HTML page', 'all-in-one-video-gallery' ) . '</p>';
				$player_html .= '<input type="text" class="plyr__embed-code-input" value="' . htmlspecialchars( $params['embed_code'] ) . '" readonly />';
				$player_html .= '</div>';
			}

			// Close Button
			$player_html .= '<button type="button" class="plyr__controls__item plyr__control plyr__share-embed-modal-close-button aiovg-icon-close"></button>';

			$player_html .= '</div>';
			$player_html .= '</div>';
		}

		// GDPR
		if ( ! empty( $params['settings']['cookie_consent'] ) ) {
			$consent_message = apply_filters( 'aiovg_translate_strings', $params['consent_message'], 'consent_message' );
			$consent_button_label = apply_filters( 'aiovg_translate_strings', $params['consent_button_label'], 'consent_button_label' );

			$player_html .= sprintf(
				'<div class="aiovg-privacy-wrapper" %s><div class="aiovg-privacy-consent-block"><div class="aiovg-privacy-consent-message">%s</div><div class="aiovg-privacy-consent-button">%s</div></div></div>',
				( isset( $params['attributes']['data-poster'] ) ? 'style="background-image: url(' . esc_attr( $params['attributes']['data-poster'] ) . ');"' : '' ),
				wp_kses_post( trim( $consent_message ) ),
				esc_html( $consent_button_label )
			);
		}

		// Return
		$html = sprintf( 
			'<div class="aiovg-player-container" style="max-width: %s;">', 
			( ! empty( $params['width'] ) ? (int) $params['width'] . 'px' : '100%' )
		);

		$html .= sprintf( 
			'<div class="aiovg-player aiovg-player-vidstack aiovg-player-standard" style="padding-bottom: %s%%;" data-id="%s" data-params=\'%s\'>',
			(float) $params['ratio'],
			esc_attr( $params['attributes']['id'] ),
			wp_json_encode( $json_array )
		);

		$html .= $player_html;

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the AMP player.
	 * 
	 * @since  2.4.0
	 * @access private
 	 * @param  array   $params  Player params.
 	 * @return string  $html    Player HTML.
	 */
	private function get_player_amp( $params ) {
		$html = '';

		$width  = ! empty( $params['width'] ) ? (int) $params['width'] : 640;
		$ratio  = ! empty( $params['ratio'] ) ? (float) $params['ratio'] : 56.25;
		$height = ( $width * $ratio ) / 100;

		$attributes = array(
			'width'  => $width,
			'height' => $height,
			'layout' => 'responsive'
		);

		// Embedcode
		if ( ! empty( $params['embed_url'] ) ) {
			$placeholder = '';
			if ( ! empty( $params['poster'] ) ) {
				$placeholder = sprintf(
					'<amp-img layout="fill" src="%s" placeholder></amp-img>',
					esc_attr( $params['poster'] )
				);
			}

			$attributes['src'] = esc_attr( $params['embed_url'] );

			$attributes['sandbox'] = 'allow-scripts allow-same-origin allow-popups';
			$attributes['allowfullscreen'] = '';
			$attributes['frameborder'] = '0';

			$html = sprintf(
				'<amp-iframe %s>%s</amp-iframe>',
				aiovg_combine_video_attributes( $attributes ),
				$placeholder
			);

			return $html;
		}

		// youtube, vimeo & dailymotion
		$services = array( 'youtube', 'vimeo', 'dailymotion' );
		
		foreach ( $services as $service ) {			
			if ( ! empty( $params[ $service ] ) ) {
				$src = esc_url_raw( $params[ $service ] );

				switch ( $service ) {
					case 'youtube':
						$attributes['data-videoid'] = aiovg_get_youtube_id_from_url( $src );

						$attributes['data-param-showinfo'] = 0;
						$attributes['data-param-rel'] = 0;
						$attributes['data-param-iv_load_policy'] = 3;

						if ( empty( $params['controls'] ) ) {
							$attributes['data-param-controls'] = 0;
						}

						if ( ! in_array( 'fullscreen', $params['controls'] ) ) {
							$attributes['data-param-fs'] = 0;
						}

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}

						if ( ! empty( $params['loop'] ) ) {
							$attributes['loop'] = '';
						}                
						break;
					case 'vimeo':
						$oembed = aiovg_get_vimeo_oembed_data( $src );
						$attributes['data-videoid'] = $oembed['video_id'];

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}
						break;
					case 'dailymotion':
						$attributes['data-videoid'] = aiovg_get_dailymotion_id_from_url( $src );

						if ( empty( $params['controls'] ) ) {
							$attributes['data-param-controls'] = 'false';
						}

						if ( ! empty( $params['autoplay'] ) ) {
							$attributes['autoplay'] = '';
						}

						if ( ! empty( $params['muted'] ) ) {
							$attributes['mute'] = 'true';
						}

						$attributes['data-endscreen-enable'] = 'false';
						$attributes['data-sharing-enable'] = 'false';
						$attributes['data-ui-logo'] = 'false';

						$attributes['data-param-queue-autoplay-next'] = 0;
						$attributes['data-param-queue-enable'] = 0;
						break;					
				}                

				$html = sprintf(
					'<amp-%1$s %2$s></amp-%1$s>',
					$service,
					aiovg_combine_video_attributes( $attributes )
				);

				break;
			}
		}

		if ( ! empty( $html ) ) {
			return $html;
		}

		// mp4, webm, ogv, hls & dash
		$types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash' );            
		$sources = array();

		foreach ( $types as $type ) {
			if ( ! empty( $params[ $type ] ) ) {
				$mime_type = "video/{$type}";
				if ( 'hls' == $type ) $mime_type = 'application/x-mpegurl';
				if ( 'dash' == $type ) $mime_type = 'application/dash+xml';

				$src = esc_attr( $params[ $type ] );
				$src = str_replace( 'http://', '//', $src );

				$sources[] = sprintf(
					'<source type="%s" src="%s" />',
					$mime_type,
					$src
				);
			}               
		}			

		if ( count( $sources ) > 0 ) {
			if ( ! empty( $params['tracks'] ) ) { // tracks
				$tracks = array();
				
				foreach ( $params['tracks'] as $track ) {
					$src = str_replace( 'http://', '//', $track['src'] );

					$sources[] = sprintf( 
						'<track src="%s" kind="subtitles" srclang="%s" label="%s">', 
						esc_attr( $src ), 
						esc_attr( $track['srclang'] ), 
						esc_attr( $track['label'] ) 
					);
				}
			}

			if ( ! empty( $params['controls'] ) ) {
				$attributes['controls'] = '';
			}

			if ( ! empty( $params['autoplay'] ) ) {
				$attributes['autoplay'] = '';
			}

			if ( ! empty( $params['loop'] ) ) {
				$attributes['loop'] = '';
			}            

			if ( ! empty( $params['poster'] ) ) {
				$attributes['poster'] = esc_attr( $params['poster'] );
			}

			$html = sprintf(
				'<amp-video %s>%s</amp-video>',
				aiovg_combine_video_attributes( $attributes ),
				implode( '', $sources )
			);
		}        

		return $html;
	}

	/**
	 * Get the player params.
	 *
	 * @since  2.4.0
	 * @param  int   $post_id Post ID.
 	 * @param  array $atts    Player configuration data.
 	 * @return array $params  Player params.
	 */
	private function get_params( $post_id, $atts ) {
		$player_settings      = get_option( 'aiovg_player_settings' );		
		$brand_settings       = get_option( 'aiovg_brand_settings', array() );
		$socialshare_settings = get_option( 'aiovg_socialshare_settings' );
		$privacy_settings     = get_option( 'aiovg_privacy_settings' );		

		$defaults = array(
			'uid'                  => 'aiovg-player-' . ++$this->uid,
			'player'               => ( 'vidstack' == $player_settings['player'] ? 'vidstack' : 'videojs' ),
			'post_id'              => $post_id,			
			'post_type'            => 'page',	
			'post_title'           => '',		
			'width'                => $player_settings['width'],
			'ratio'                => $player_settings['ratio'],
			'preload'              => $player_settings['preload'],
			'playsinline'          => isset( $player_settings['playsinline'] ) ? $player_settings['playsinline'] : 0,
			'autoplay'             => $player_settings['autoplay'],
			'autoadvance'          => 0,
			'loop'                 => $player_settings['loop'],
			'muted'                => $player_settings['muted'],
			'playpause'            => isset( $player_settings['controls']['playpause'] ),
			'current'              => isset( $player_settings['controls']['current'] ),
			'progress'             => isset( $player_settings['controls']['progress'] ),
			'duration'             => isset( $player_settings['controls']['duration'] ),
			'tracks'               => isset( $player_settings['controls']['tracks'] ),
			'speed'                => isset( $player_settings['controls']['speed'] ),
			'quality'              => isset( $player_settings['controls']['quality'] ),			
			'volume'               => isset( $player_settings['controls']['volume'] ),
			'fullscreen'           => isset( $player_settings['controls']['fullscreen'] ),
			'share'			       => isset( $player_settings['controls']['share'] ),
			'embed'			       => isset( $player_settings['controls']['embed'] ),
			'download'			   => 0,
			'hotkeys'              => isset( $player_settings['hotkeys'] ) ? $player_settings['hotkeys'] : 0,
			'cc_load_policy'       => $player_settings['cc_load_policy'],
			'show_logo'            => ! empty( $brand_settings['logo_image'] ) ? $brand_settings['show_logo'] : 0,
			'copyright_text'       => ! empty( $brand_settings['copyright_text'] ) ? $brand_settings['copyright_text'] : '',
			'cookie_consent'       => 0,
			'consent_message'      => $privacy_settings['consent_message'],
			'consent_button_label' => $privacy_settings['consent_button_label'],
			'mp4'                  => '',
			'webm'                 => '',
			'ogv'                  => '',
			'hls'                  => '',
			'dash'                 => '',
			'youtube'              => '',
			'vimeo'                => '',
			'dailymotion'          => '',
			'rumble'               => '',
			'facebook'             => '',
			'poster'               => ''			
		);

		if ( empty( $player_settings['force_js_initialization'] ) ) {
			$defaults['player'] = 'iframe';
		}

		if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
			$defaults['player'] = 'amp';
		}

		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			$defaults['player'] = 'amp';
		}

		$params = array_merge( $defaults, $atts );		

		$params['width'] = ! empty( $params['width'] ) ? $params['width'] : '';
		$params['ratio'] = ! empty( $params['ratio'] ) ? $params['ratio'] : 56.25;

		if ( $post_id > 0 ) {
			$params['post_type']  = get_post_type( $post_id );
			$params['post_title'] = get_the_title( $post_id );
		}		

		if ( 'iframe' == $params['player'] ) {
			$params['embed_url'] = aiovg_get_player_page_url( $post_id, $atts );
		} else {
			// Controls
			$controls = array( 'playpause', 'current', 'progress', 'duration', 'tracks', 'speed', 'quality', 'volume', 'fullscreen' );		
			$params['controls'] = array();

			foreach ( $controls as $control ) {
				if ( ! empty( $params[ $control ] ) ) {	
					$params['controls'][] = $control;
				}

				unset( $params[ $control ] );
			}

			// Sources
			$post_meta = array();
			$embed_url = '';

			if ( $post_id > 0 && 'aiovg_videos' == $params['post_type'] ) {
				$post_meta = get_post_meta( $post_id );
				$source_type = $post_meta['type'][0];
				
				switch ( $source_type ) {
					case 'default':
						$params['mp4'] = isset( $post_meta['mp4'] ) ? $post_meta['mp4'][0] : '';
						$params['webm'] = isset( $post_meta['webm'] ) ? $post_meta['webm'][0] : '';
						$params['ogv'] = isset( $post_meta['ogv'] ) ? $post_meta['ogv'][0] : '';
						
						if ( ! empty( $post_meta['quality_level'][0] ) ) {
							$params['quality_level'] = $post_meta['quality_level'][0];
						}

						if ( ! empty( $post_meta['sources'][0] ) ) {
							$params['sources'] = unserialize( $post_meta['sources'][0] );
						}
						break;
					case 'adaptive':
						$params['hls'] = isset( $post_meta['hls'] ) ? $post_meta['hls'][0] : '';
						$params['dash'] = isset( $post_meta['dash'] ) ? $post_meta['dash'][0] : '';
						break;
					case 'youtube':
					case 'vimeo':
					case 'dailymotion':
					case 'rumble':
					case 'facebook':
						$params[ $source_type ] = isset( $post_meta[ $source_type ] ) ? $post_meta[ $source_type ][0] : '';
						break;
					case 'embedcode':
						$embedcode = isset( $post_meta['embedcode'] ) ? $post_meta['embedcode'][0] : '';

						$document = new DOMDocument();
						@$document->loadHTML( $embedcode );

						$iframes = $document->getElementsByTagName( 'iframe' ); 
						
						if ( $iframes->length > 0 ) {
							if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
								$embed_url = $iframes->item(0)->getAttribute( 'src' );
							}
						} else {
							$params['player'] = 'raw'; 
							$params['player_html'] = $embedcode;
						}
						break;
				}

				// Poster
				$image_data = aiovg_get_image( $post_id, 'large' );
				$params['poster'] = $image_data['src'];

				// Tracks
				if ( in_array( 'tracks', $params['controls'] ) && ! empty( $post_meta['track'] ) ) {
					$params['tracks'] = array();

					foreach ( $post_meta['track'] as $track ) {
						$track = unserialize( $track );
						$track['src'] = aiovg_resolve_url( $track['src'] );

						$params['tracks'][] = $track;
					}
				}
			}

			// Rumble
			if ( ! empty( $params['rumble'] ) ) {
				$oembed = aiovg_get_rumble_oembed_data( $params['rumble'] );
				$html = $oembed['html'];						

				$document = new DOMDocument();
				@$document->loadHTML( $html );

				$iframes = $document->getElementsByTagName( 'iframe' ); 
				
				if ( $iframes->length > 0 ) {
					if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
						$embed_url = $iframes->item(0)->getAttribute( 'src' );

						$embed_url = add_query_arg( 'rel', 0, $embed_url );	
						
						if ( ! empty( $params['autoplay'] ) ) {
							$embed_url = add_query_arg( 'autoplay', 2, $embed_url );	
						}									
					}
				}
			}

			// Facebook
			if ( ! empty( $params['facebook'] ) ) {
				$embed_url = 'https://www.facebook.com/plugins/video.php?href=' . urlencode(  $params['facebook'] ) . '&width=560&height=315&show_text=false&appId';
		
				$embed_url = add_query_arg( 'autoplay', (int) $params['autoplay'], $embed_url );				
				$embed_url = add_query_arg( 'loop', (int) $params['loop'], $embed_url );			
				$embed_url = add_query_arg( 'muted', (int) $params['muted'], $embed_url );
			}

			// Embedcode
			if ( ! in_array( $params['player'], array( 'amp', 'raw' ) ) ) {
				$services = array( 'youtube', 'vimeo', 'dailymotion' );

				foreach ( $services as $service ) {
					$use_native_controls = isset( $player_settings['use_native_controls'][ $service ] );

					if ( 'vidstack' == $params['player'] && 'dailymotion' == $service ) {
						$use_native_controls = true;
					}

					$use_native_controls = apply_filters( 'aiovg_use_native_controls', $use_native_controls, $service );
					
					if ( $use_native_controls ) {
						if ( ! empty( $params[ $service ] ) ) {  
							$embed_url = $params[ $service ];

							switch ( $service ) {
								case 'youtube':
									parse_str( $embed_url, $parsed_url );

									$embed_url = 'https://www.youtube.com/embed/' . aiovg_get_youtube_id_from_url( $embed_url ) . '?showinfo=0&rel=0&iv_load_policy=3';									
									
									if ( isset( $parsed_url['start'] ) ) {
										$embed_url = add_query_arg( 'start', $parsed_url['start'], $embed_url );
									}
									
									if ( isset( $parsed_url['end'] ) ) {
										$embed_url = add_query_arg( 'end', $parsed_url['end'], $embed_url );
									}
									break;
								case 'vimeo':
									$oembed = aiovg_get_vimeo_oembed_data( $embed_url );
									$embed_url = 'https://player.vimeo.com/video/' . $oembed['video_id'] . '?title=0&byline=0&portrait=0';

									if ( ! empty( $oembed['html'] ) ) {
										$document = new DOMDocument();
										@$document->loadHTML( $oembed['html'] );
										
										$iframes = $document->getElementsByTagName( 'iframe' ); 
								
										if ( $iframes->item(0)->hasAttribute( 'src' ) ) {
											$original_src = $iframes->item(0)->getAttribute( 'src' );

											$query = parse_url( $original_src, PHP_URL_QUERY );
											parse_str( $query, $parsed_url );
				
											if ( isset( $parsed_url['h'] ) ) {
												$embed_url = add_query_arg( 'h', $parsed_url['h'], $embed_url );
											}
				
											if ( isset( $parsed_url['app_id'] ) ) {
												$embed_url = add_query_arg( 'app_id', $parsed_url['app_id'], $embed_url );
											}
										}
									}
									break;				
								case 'dailymotion':
									$embed_url = 'https://www.dailymotion.com/embed/video/' . aiovg_get_dailymotion_id_from_url( $embed_url ) . '?queue-autoplay-next=0&queue-enable=0&sharing-enable=0&ui-logo=0&ui-start-screen-info=0';
									break;								
							}
					
							if ( empty( $params['controls'] ) ) {
								$embed_url = add_query_arg( 'controls', 0, $embed_url );
							} else {
								if ( ! in_array( 'fullscreen', $params['controls'] ) ) {
									$embed_url = add_query_arg( 'fs', 0, $embed_url );
								}
							}
					
							$embed_url = add_query_arg( 'autoplay', (int) $params['autoplay'], $embed_url );				
							$embed_url = add_query_arg( 'loop', (int) $params['loop'], $embed_url );			
							$embed_url = add_query_arg( 'muted', (int) $params['muted'], $embed_url );	
							break;
						}
					}
				}
			}

			if ( ! empty( $embed_url ) ) {
				$params['embed_url'] = $embed_url;
			}
			
			// Share
			if ( ! empty( $params['share'] ) ) {
				$share_url = get_permalink( $post_id );
			
				$share_title = $params['post_title'];
				$share_title = str_replace( ' ', '%20', $share_title );
				$share_title = str_replace( '|', '%7C', $share_title );
			
				$share_image = aiovg_resolve_url( $params['poster'] );
			
				$share_buttons = array();
					
				if ( isset( $socialshare_settings['services']['facebook'] ) ) {
					$share_buttons[] = array(
						'service' => 'facebook',		
						'url'     => "https://www.facebook.com/sharer/sharer.php?u={$share_url}",
						'icon'    => 'aiovg-icon-facebook'				
					);
				}
			
				if ( isset( $socialshare_settings['services']['twitter'] ) ) {
					$share_buttons[] = array(
						'service' => 'twitter',			
						'url'     => "https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}",
						'icon'    => 'aiovg-icon-twitter'
					);
				}		
			
				if ( isset( $socialshare_settings['services']['linkedin'] ) ) {
					$share_buttons[] = array(
						'service' => 'linkedin',			
						'url'     => "https://www.linkedin.com/shareArticle?url={$share_url}&amp;title={$share_title}",
						'icon'    => 'aiovg-icon-linkedin'
					);
				}
			
				if ( isset( $socialshare_settings['services']['pinterest'] ) ) {
					$pinterest_url = "https://pinterest.com/pin/create/button/?url={$share_url}&amp;description={$share_title}";
			
					if ( ! empty( $share_image ) ) {
						$pinterest_url .= "&amp;media={$share_image}";
					}
			
					$share_buttons[] = array(
						'service' => 'pinterest',			
						'url'     => $pinterest_url,
						'icon'    => 'aiovg-icon-pinterest'
					);
				}
			
				if ( isset( $socialshare_settings['services']['tumblr'] ) ) {
					$tumblr_url = "https://www.tumblr.com/share/link?url={$share_url}&amp;name={$share_title}";
			
					$share_description = aiovg_get_excerpt( $post_id, 160, '', false ); 
					if ( ! empty( $share_description ) ) {
						$share_description = str_replace( ' ', '%20', $share_description );
						$share_description = str_replace( '|', '%7C', $share_description );	
			
						$tumblr_url .= "&amp;description={$share_description}";
					}
			
					$share_buttons[] = array(	
						'service' => 'tumblr',		
						'url'     => $tumblr_url,
						'icon'    => 'aiovg-icon-tumblr'
					);
				}
			
				if ( isset( $socialshare_settings['services']['whatsapp'] ) ) {
					if ( wp_is_mobile() ) {
						$whatsapp_url = "whatsapp://send?text={$share_title} " . rawurlencode( $share_url );
					} else {
						$whatsapp_url = "https://api.whatsapp.com/send?text={$share_title}&nbsp;{$share_url}";
					}
			
					$share_buttons[] = array(
						'service' => 'whatsapp',				
						'url'     => $whatsapp_url,
						'icon'    => 'aiovg-icon-whatsapp'
					);
				}
			
				$params['share_buttons'] = apply_filters( 'aiovg_player_socialshare_buttons', $share_buttons );
				if ( empty( $params['share_buttons'] ) ) {
					$params['share'] = 0;
				}
			}

			// Embed
			if ( ! empty( $params['embed'] ) ) {
				$params['embed_code'] = sprintf(
					'<div style="position:relative;padding-bottom:%s%%;height:0;overflow:hidden;"><iframe src="%s" title="%s" width="100%%" height="100%%" style="position:absolute;width:100%%;height:100%%;top:0px;left:0px;overflow:hidden;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>',
					(float) $params['ratio'],
					esc_url( aiovg_get_player_page_url( $post_id, $atts ) ),
					esc_attr( $params['post_title'] )
				);
			}

			// Download
			if ( ! empty( $params['mp4'] ) ) {
				$params['download'] = isset( $player_settings['controls']['download'] );
				$download_url = '';

				if ( ! empty( $post_meta ) ) {
					if ( isset( $post_meta['download'] ) && empty( $post_meta['download'][0] ) ) {
						$params['download'] = 0;
					}

					$download_url =  home_url( '?vdl=' . $post_id );
				}

				if ( isset( $atts['download'] ) ) {
					$params['download'] = (int) $atts['download'];
				}

				if ( ! empty( $params['download'] ) ) {
					if ( empty( $download_url ) ) {
						$download_url = home_url( '?vdl=' . aiovg_get_temporary_file_download_id( $params['mp4'] ) );
					}

					$params['download_url'] = $download_url;
				}
			}

			// Logo
			if ( ! empty( $params['show_logo'] ) ) {
				$params['logo_image'] = $brand_settings['logo_image'];
				$params['logo_link'] = $brand_settings['logo_link'];
				$params['logo_position'] = $brand_settings['logo_position'];
				$params['logo_margin'] = $brand_settings['logo_margin'];
			}			

			// Resolve relative file paths as absolute URLs
			$fields = array( 'mp4', 'webm', 'ogv', 'poster', 'logo_image' );

			foreach ( $fields as $field ) {
				if ( ! empty( $params[ $field ] ) ) {
					$params[ $field ] = aiovg_resolve_url( $params[ $field ] );
				}
			}
		}

		// GDPR			
		if ( ! isset( $_COOKIE['aiovg_gdpr_consent'] ) && ! empty( $privacy_settings['show_consent'] ) && ! empty( $privacy_settings['consent_message'] ) && ! empty( $privacy_settings['consent_button_label'] ) ) {
			if ( ! in_array( $params['player'], array( 'amp', 'raw' ) ) ) {
				$services = array( 'youtube', 'vimeo', 'dailymotion', 'embed_url' );

				foreach ( $services as $service ) {
					if ( ! empty( $params[ $service ] ) ) {
						$params['cookie_consent'] = 1;
						$params['autoplay'] = 0;
					}
				}
			}
		}

		return $params;
	}
		
}
