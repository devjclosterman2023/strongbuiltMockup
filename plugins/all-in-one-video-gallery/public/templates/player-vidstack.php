<?php

/**
 * Vidstack Player.
 *
 * @link     https://plugins360.com
 * @since    3.3.1
 *
 * @package All_In_One_Video_Gallery
 */

$settings = array(
	'uid'       => isset( $_GET['uid'] ) ? sanitize_text_field( $_GET['uid'] ) : 0,
	'post_id'   => $post_id,
	'post_type' => $post_type,
	'player'    => array(
		'volume' => 0.5
	)
);

$autoadvance = isset( $_GET['autoadvance'] ) ? (int) $_GET['autoadvance'] : 0;
if ( $autoadvance ) {
	$settings['autoadvance'] = 1;
}

// Video Sources
$sources = array();
$allowed_types = array( 'mp4', 'webm', 'ogv', 'hls', 'dash', 'youtube', 'vimeo' );

if ( ! empty( $post_meta ) ) {
	$type = $post_meta['type'][0];

	switch ( $type ) {
		case 'default':
			$types = array( 'mp4', 'webm', 'ogv' );

			foreach ( $types as $type ) {
				if ( ! empty( $post_meta[ $type ][0] ) ) {
					$ext   = $type;
					$label = '';

					if ( 'mp4' == $type ) {
						$ext = aiovg_get_file_ext( $post_meta[ $type ][0] );
						if ( ! in_array( $ext, array( 'webm', 'ogv' ) ) ) {
							$ext = 'mp4';
						}

						if ( ! empty( $post_meta['quality_level'][0] ) ) {
							$label = $post_meta['quality_level'][0];
						}
					}

					$sources[ $type ] = array(
						'type' => "video/{$ext}",
						'src'  => $post_meta[ $type ][0]
					);

					if ( ! empty( $label ) ) {
						$sources[ $type ]['label'] = $label;
					}
				}
			}

			if ( ! empty( $post_meta['sources'][0] ) ) {
				$_sources = unserialize( $post_meta['sources'][0] );

				foreach ( $_sources as $source ) {
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
			break;
		case 'adaptive':
			$hls = isset( $post_meta['hls'] ) ? $post_meta['hls'][0] : '';
			if ( ! empty( $hls ) ) {
				$sources['hls'] = array(
					'type' => 'application/x-mpegurl',
					'src'  => $hls
				);
			}

			$dash = isset( $post_meta['dash'] ) ? $post_meta['dash'][0] : '';
			if ( ! empty( $dash ) ) {
				$sources['dash'] = array(
					'type' => 'application/dash+xml',
					'src'  => $dash
				);
			}
			break;
		default:
			if ( in_array( $type, $allowed_types ) && ! empty( $post_meta[ $type ][0] ) ) {
				$src = $post_meta[ $type ][0];

				$sources[ $type ] = array(
					'type' => "video/{$type}",
					'src'  => $src
				);
			}
	}
} else {
	foreach ( $allowed_types as $type ) {		
		if ( isset( $_GET[ $type ] ) && ! empty( $_GET[ $type ] ) ) {
			switch ( $type ) {
				case 'hls': 
					$mime_type = 'application/x-mpegurl'; 
					break;
				case 'dash': 
					$mime_type = 'application/dash+xml'; 
					break;
				default: 
					$mime_type = "video/{$type}";
			}

			$src = aiovg_sanitize_url( $_GET[ $type ] );

			$sources[ $type ] = array(
				'type' => $mime_type,
				'src'  => $src
			);
		}	
	}
}

$sources = apply_filters( 'aiovg_iframe_vidstack_player_sources', $sources ); 

// Video Captions
$tracks = array();
$cc_load_policy = isset( $_GET['cc_load_policy'] ) ? (int) $_GET['cc_load_policy'] : (int) $player_settings['cc_load_policy'];

if ( ! empty( $post_meta['track'] ) ) {
	foreach ( $post_meta['track'] as $track ) {
		$tracks[] = unserialize( $track );
	}	

	if ( ! empty( $cc_load_policy ) ) {
		$settings['player']['captions'] = array(
			'active'   => true,
			'language' => 'auto',
			'update'   => false
		); 
	}
}

$tracks = apply_filters( 'aiovg_iframe_vidstack_player_tracks', $tracks );

// Video Attributes
$attributes = array(
	'id'       => 'player',
	'style'    => 'width: 100%; height: 100%;',
	'controls' => '',
	'preload'  => esc_attr( $player_settings['preload'] )
);

$autoplay = isset( $_GET['autoplay'] ) ? (int) $_GET['autoplay'] : (int) $player_settings['autoplay'];
if ( $autoplay ) {
	$attributes['autoplay'] = '';
	$settings['player']['autoplay'] = true;
}

$loop = isset( $_GET['loop'] ) ? (int) $_GET['loop'] : (int) $player_settings['loop'];
if ( $loop ) {
	$attributes['loop'] = '';
	$settings['player']['loop'] = array( 'active' => true );
}

$muted = isset( $_GET['muted'] ) ? (int) $_GET['muted'] : (int) $player_settings['muted'];
if ( $muted ) {
	$attributes['muted'] = '';
	$settings['player']['muted'] = true;
}

$playsinline = isset( $player_settings['playsinline'] ) && ! empty( $player_settings['playsinline'] );
if ( $playsinline ) {
	$attributes['playsinline'] = '';
	$settings['player']['playsinline'] = true;
}

$poster = '';
if ( isset( $_GET['poster'] ) ) {
	$poster = $_GET['poster'];
} elseif ( ! empty( $post_meta ) ) {
	$image_data = aiovg_get_image( $post_id, 'large' );
	$poster = $image_data['src'];
}

if ( ! empty( $poster ) ) {
	$attributes['data-poster'] = aiovg_sanitize_url( aiovg_resolve_url( $poster ) );
}

$attributes = apply_filters( 'aiovg_iframe_vidstack_player_attributes', $attributes );

// Player Controls
$has_play = isset( $_GET['playpause'] ) ? (int) $_GET['playpause'] : isset( $player_settings['controls']['playpause'] );
$has_current_time = isset( $_GET['current'] ) ? (int) $_GET['current'] : isset( $player_settings['controls']['current'] );
$has_progress = isset( $_GET['progress'] ) ? (int) $_GET['progress'] : isset( $player_settings['controls']['progress'] );
$has_duration = isset( $_GET['duration'] ) ? (int) $_GET['duration'] : isset( $player_settings['controls']['duration'] );
$has_volume = isset( $_GET['volume'] ) ? (int) $_GET['volume'] : isset( $player_settings['controls']['volume'] );
$has_quality_selector = isset( $_GET['quality'] ) ? (int) $_GET['quality'] : isset( $player_settings['controls']['quality'] );
$has_captions = isset( $_GET['tracks'] ) ? (int) $_GET['tracks'] : isset( $player_settings['controls']['tracks'] );
$has_speed_control = isset( $_GET['speed'] ) ? (int) $_GET['speed'] : isset( $player_settings['controls']['speed'] );
$has_fullscreen = isset( $_GET['fullscreen'] ) ? (int) $_GET['fullscreen'] : isset( $player_settings['controls']['fullscreen'] );

$controls = array();
$controls[] = 'play-large';

if ( $has_play ) {
	$controls[] = 'play';
}

if ( $has_current_time ) {
	$controls[] = 'current-time';
}

if ( $has_progress ) {
	$controls[] = 'progress';
}

if ( $has_duration ) {
	$controls[] = 'duration';
}

if ( $has_volume ) {
	$controls[] = 'mute';
	$controls[] = 'volume';
}

if ( $has_captions ) {
	$controls[] = 'captions';	
} else {
	if ( empty( $cc_load_policy ) ) {
		$tracks = array();
	}
}

if ( $has_quality_selector || $has_captions || $has_speed_control ) {
	$controls[] = 'settings';

	$settings['player']['settings'] = array();

	if ( $has_quality_selector ) {
		$settings['player']['settings'][] = 'quality';
	}

	if ( $has_captions ) {
		$settings['player']['settings'][] = 'captions';
	}

	if ( $has_speed_control ) {
		$settings['player']['settings'][] = 'speed';

		$settings['player']['speed'] = array(
			'selected' => 1,
			'options'  => array( 0.5, 0.75, 1, 1.5, 2 )
		);
	}
}

if ( isset( $sources['mp4'] ) ) {
	$has_download = isset( $player_settings['controls']['download'] );
	$download_url = '';

	if ( ! empty( $post_meta ) ) {
		if ( isset( $post_meta['download'] ) && empty( $post_meta['download'][0] ) ) {
			$has_download = 0;
		}

		$download_url = home_url( '?vdl=' . $post_id );
	}

	if ( isset( $_GET['download'] ) ) {
		$has_download = (int) $_GET['download'];
	}

	if ( $has_download ) {
		$controls[] = 'download';

		if ( empty( $download_url ) ) {
			$download_url = home_url( '?vdl=' . aiovg_get_temporary_file_download_id( $sources['mp4']['src'] ) );
		}

		$settings['player']['urls'] = array(
			'download' => esc_url( $download_url )
		);
	}
}

if ( $has_fullscreen ) {
	$controls[] = 'fullscreen';
}

$settings['player']['controls'] = $controls;

// Keyboard Hotkeys
if ( isset( $player_settings['hotkeys'] ) && ! empty( $player_settings['hotkeys'] ) ) {
    $settings['player']['keyboard'] = array(
        'focused' => true,
        'global'  => true
    );
}

// HLS
if ( isset( $sources['hls'] ) ) {
	$settings['hls'] = $sources['hls']['src'];

    $settings['player']['captions'] = array(
        'active'   => ! empty( $cc_load_policy ) ? true : false,
        'language' => 'auto',
        'update'   => true
    ); 
}

// Dash
if ( isset( $sources['dash'] ) ) {
	$settings['dash'] = $sources['dash']['src'];

    $settings['player']['captions'] = array(
        'active'   => ! empty( $cc_load_policy ) ? true : false,
        'language' => 'auto',
        'update'   => true
    ); 
}

// Share
$has_share = isset( $_GET['share'] ) ? (int) $_GET['share'] : isset( $player_settings['controls']['share'] );
if ( $has_share ) {
	$socialshare_settings = get_option( 'aiovg_socialshare_settings' );

	$share_url = $post_url;

	$share_title = $post_title;
	$share_title = str_replace( ' ', '%20', $share_title );
	$share_title = str_replace( '|', '%7C', $share_title );

	$share_image = isset( $attributes['data-poster'] ) ? $attributes['data-poster'] : '';

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

	$share_buttons = apply_filters( 'aiovg_player_socialshare_buttons', $share_buttons );
	if ( ! empty( $share_buttons ) ) {
		$settings['share'] = 1;
	}
}

// Embed
$has_embed = isset( $_GET['embed'] ) ? (int) $_GET['embed'] : isset( $player_settings['controls']['embed'] );
if ( $has_embed ) {
	$protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';
	$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = aiovg_remove_query_arg( array( 'uid', 'autoadvance' ), $current_url );

	$embed_code = sprintf(
		'<div style="position:relative;padding-bottom:%s;height:0;overflow:hidden;"><iframe src="%s" title="%s" width="100%%" height="100%%" style="position:absolute;width:100%%;height:100%%;top:0px;left:0px;overflow:hidden" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>',
		( isset( $_GET['ratio'] ) ? (float) $_GET['ratio'] : (float) $player_settings['ratio'] ) . '%',
		esc_url( $current_url ),
		esc_attr( $post_title )
	);

	$settings['embed'] = 1;
}

// Logo
if ( ! empty( $brand_settings ) ) {
	$has_logo = ! empty( $brand_settings['logo_image'] ) ? (int) $brand_settings['show_logo'] : 0;
	if ( $has_logo ) {
		$settings['logo'] = array(
			'image'    => aiovg_sanitize_url( aiovg_resolve_url( $brand_settings['logo_image'] ) ),
			'link'     => ! empty( $brand_settings['logo_link'] ) ? esc_url_raw( $brand_settings['logo_link'] ) : 'javascript:void(0)',
			'position' => sanitize_text_field( $brand_settings['logo_position'] ),
			'margin'   => ! empty( $brand_settings['logo_margin'] ) ? (int) $brand_settings['logo_margin'] : 15
		);
	}

	$has_contextmenu = ! empty( $brand_settings['copyright_text'] ) ? 1 : 0;
	if ( $has_contextmenu ) {
		$settings['contextmenu'] = array(
			'content' => apply_filters( 'aiovg_translate_strings', sanitize_text_field( $brand_settings['copyright_text'] ), 'copyright_text' )
		);
	}
}

$settings = apply_filters( 'aiovg_iframe_vidstack_player_settings', $settings );
?>
<!DOCTYPE html>
<html translate="no">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">

    <?php if ( $post_id > 0 ) : ?>    
        <title><?php echo wp_kses_post( $post_title ); ?></title>    
        <link rel="canonical" href="<?php echo esc_url( $post_url ); ?>" />
    <?php endif; ?>

	<link rel="stylesheet" href="<?php echo AIOVG_PLUGIN_URL; ?>vendor/vidstack/plyr.css?v=3.7.8" />

	<style type="text/css">
		* {
            font-family: Verdana, sans-serif;
        }

        html, 
        body {
            width: 100%;
            height: 100%;
            margin: 0; 
            padding: 0; 
            overflow: hidden;
        }			

		@font-face {
			font-family: 'aiovg-icons';
			src: url('<?php echo AIOVG_PLUGIN_URL; ?>public/assets/fonts/aiovg-icons.eot?794k8m');
			src: url('<?php echo AIOVG_PLUGIN_URL; ?>public/assets/fonts/aiovg-icons.eot?794k8m#iefix') format('embedded-opentype'),
				url('<?php echo AIOVG_PLUGIN_URL; ?>public/assets/fonts/aiovg-icons.ttf?794k8m') format('truetype'),
				url('<?php echo AIOVG_PLUGIN_URL; ?>public/assets/fonts/aiovg-icons.woff?794k8m') format('woff'),
				url('<?php echo AIOVG_PLUGIN_URL; ?>public/assets/fonts/aiovg-icons.svg?794k8m#aiovg-icons') format('svg');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
		}
		
		[class^="aiovg-icon-"],
		[class*=" aiovg-icon-"] {
			/* use !important to prevent issues with browser extensions that change fonts */
			font-family: 'aiovg-icons' !important;
			speak: none;
			color: #fff;
			font-style: normal;
			font-weight: normal;
			font-variant: normal;
			text-transform: none;
			line-height: 1;
		
			/* Better Font Rendering =========== */
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}

		.aiovg-icon-share:before {
			content: "\ea82";
		}

		.aiovg-icon-facebook:before {
			content: "\ea90";
		}

		.aiovg-icon-twitter:before {
			content: "\eab9";
		}

		.aiovg-icon-linkedin:before {
			content: "\eaca";
		}

		.aiovg-icon-pinterest:before {
			content: "\ead1";
		}

		.aiovg-icon-tumblr:before {
			content: "\eab9";
		}

		.aiovg-icon-whatsapp:before {
			content: "\ea93";
		}

		.aiovg-icon-close:before {
			content: "\ea0f";
		}		

		.aiovg-player .plyr {
            width: 100%;
            height: 100%;
        }

		.aiovg-player .plyr a,
        .aiovg-player .plyr a:hover,
        .aiovg-player .plyr a:focus {
            text-decoration: none;
        } 
		
		.aiovg-player .plyr__ads .plyr__control--overlaid {
			z-index: 999;
		}

		.aiovg-player .plyr__cues {
			visibility: hidden;
		}

        .aiovg-player .plyr__share-embed-button {
			position: absolute;  
			width: 35px;
			height: 35px;          	
			top: 15px;		
			right: 15px; 
			background-color: rgba( 0, 0, 0, 0.5 );	
            border-radius: 2px;	
			text-align: center;  
			line-height: 1;	  
			opacity: 1;
            -webkit-transition: opacity .5s;
               -moz-transition: opacity .5s;
                -ms-transition: opacity .5s;
                 -o-transition: opacity .5s;
                    transition: opacity .5s;
			z-index: 1;	
		} 

		.aiovg-player .plyr--hide-controls .plyr__share-embed-button {
			opacity: 0;
		}         

		.aiovg-player .plyr__share-embed-button:hover,
		.aiovg-player .plyr__share-embed-button:focus {        
			background-color: #00B3FF;	
		} 

		.aiovg-player .plyr__share-embed-modal {
			position: absolute;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;   
			top: 0;
			left: 0;
			background-color: #222;	
			opacity: 0;			
			-webkit-transition: opacity .5s;
               -moz-transition: opacity .5s;
                -ms-transition: opacity .5s;
                 -o-transition: opacity .5s;
                    transition: opacity .5s;
			pointer-events: none;
			z-index: 9;			
        }

		.aiovg-player .plyr__share-embed-modal.fadein {
			opacity: 1;
			pointer-events: auto;
		}

		.aiovg-player .plyr__share-embed-modal-content {
            width: 100%;
        }

		.aiovg-player .plyr__share-embed-modal-close-button {
			display: block;
			position: absolute;
			width: 35px;
			height: 35px;
			top: 15px;
			right: 15px;
            border-radius: 2px;	
			text-align: center;
			line-height: 1;		
			cursor: pointer;	
            z-index: 9;				
		}

		.aiovg-player .plyr__share-embed-modal-close-button:hover,
		.aiovg-player .plyr__share-embed-modal-close-button:focus {        
			background-color: #00B3FF;	
		} 

		.aiovg-player .plyr__share {
            text-align: center;
        }

		.aiovg-player .plyr__share-button {
            display: inline-block;
			margin: 2px;
            width: 40px;
			height: 40px;
            line-height: 1;
			vertical-align: middle;
        }       

		.aiovg-player .plyr__share-button:hover {
            opacity: 0.9;
        }

        .aiovg-player .plyr__share-button-facebook {
            background-color: #3B5996;
        }   
		
		.aiovg-player .plyr__share-button-twitter {
            background-color: #55ACEE;
        }

        .aiovg-player .plyr__share-button-linkedin {
            background-color: #006699;
        }

        .aiovg-player .plyr__share-button-pinterest {
            background-color: #C00117;
        }

        .aiovg-player .plyr__share-button-tumblr {
            background-color: #28364B;
        } 
		
		.aiovg-player .plyr__share-button-whatsapp {
            background-color: #25d366;
        }  

        .aiovg-player .plyr__share-button span {
            color: #fff;
            font-size: 24px;
			line-height: 40px;
        }

        .aiovg-player .plyr__embed {	
			max-width: 720px;		
            margin: auto;
			padding: 20px;
        }

        .aiovg-player .plyr__embed p {
			margin: 0 0 7px 0;
			color: #fff;
			font-size: 11px;
            text-align: center;
			text-transform: uppercase;
        }

		.aiovg-player .plyr__embed input {
            width: 100%;			
            padding: 7px;
            background: #fff;
            border: 1px solid #fff;
			border-radius: 0;
			box-sizing: border-box;
            color: #000;
			line-height: 1;
        }

        .aiovg-player .plyr__embed input:focus {
            border: 1px solid #fff;
            outline-style: none;
        }

		.aiovg-player .plyr__logo {
			opacity: 1;
            -webkit-transition: opacity .5s;
               -moz-transition: opacity .5s;
                -ms-transition: opacity .5s;
                 -o-transition: opacity .5s;
                    transition: opacity .5s;
		}

		.aiovg-player .plyr--hide-controls .plyr__logo {
			opacity: 0;
		}

		.aiovg-player .plyr__logo a {
			position: absolute;
			opacity: 0.5;
			transition: opacity 0.1s;
			z-index: 3; 
		}

		.aiovg-player .plyr__logo a:hover {
			opacity: 1;
		}

		.aiovg-player .plyr__logo img {
			max-width: 150px;
		}		

		.aiovg-player .plyr__contextmenu {
            position: absolute;
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
            background: rgba( 0, 0, 0, 0.5 );
			border-radius: 2px;
            z-index: 9999999999; /* make sure it shows on fullscreen */
        }
        
        .aiovg-player .plyr__contextmenu-content {
            margin: 0;
            padding: 8px 12px;
            font-size: 11px;
            color: #fff;	
			line-height: 1;		
            white-space: nowrap;
            cursor: pointer;
        }
    </style>

	<?php do_action( 'aiovg_iframe_vidstack_player_head', $settings, $attributes, $sources, $tracks ); ?>
</head>
<body class="aiovg-player">
	<?php
	// YouTube
	if ( isset( $sources['youtube'] ) ) {
		$embed_url = sprintf(
			'https://www.youtube.com/embed/%s?iv_load_policy=3&amp;modestbranding=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1',
			aiovg_get_youtube_id_from_url( $sources['youtube']['src'] )
		);

		parse_str( $sources['youtube']['src'], $queries );
		
		if ( isset( $queries['start'] ) ) {
			$embed_url .= '&amp;start=' . (int) $queries['start'];
		}
	
		if ( isset( $queries['t'] ) ) {
			$embed_url .= '&amp;start=' . (int) $queries['t'];
		}
	
		if ( isset( $queries['end'] ) ) {
			$embed_url .= '&amp;end=' . (int) $queries['end'];
		}

		printf(
			'<div id="%s" class="plyr__video-embed" style="%s"><iframe src="%s" data-poster="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
			esc_attr( $attributes['id'] ),
			esc_attr( $attributes['style'] ),
			esc_attr( $embed_url ),
			( isset( $attributes['data-poster'] ) ? esc_attr( $attributes['data-poster'] ) : '' )
		);
	}

	// Vimeo
	elseif ( isset( $sources['vimeo'] ) ) {
		$oembed = aiovg_get_vimeo_oembed_data( $sources['vimeo']['src'] );
		
		$embed_url = sprintf(
			'https://player.vimeo.com/video/%s?byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media',
			$oembed['video_id']
		);

		printf(
			'<div id="%s" class="plyr__video-embed" style="%s"><iframe src="%s" data-poster="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
			esc_attr( $attributes['id'] ),
			esc_attr( $attributes['style'] ),
			esc_attr( $embed_url ),
			( isset( $attributes['data-poster'] ) ? esc_attr( $attributes['data-poster'] ) : '' )
		);
	}

	// HLS or Dash
	elseif ( isset( $sources['hls'] ) || isset( $sources['dash'] ) ) {
		printf( '<video %s></video>', aiovg_combine_video_attributes( $attributes ) );
	}

	// HTML5 Video
	elseif ( ! empty( $sources ) ) {
		printf( '<video %s>', aiovg_combine_video_attributes( $attributes ) );

		// Video Sources
		foreach ( $sources as $source ) {
			printf( 
				'<source src="%s" type="%s" size="%d" />', 				
				esc_attr( aiovg_resolve_url( $source['src'] ) ),
				esc_attr( $source['type'] ), 
				( isset( $source['label'] ) ? (int) $source['label'] : '' )
			);
		}

		// Video Tracks		
		foreach ( $tracks as $index => $track ) {
        	printf( 
				'<track kind="captions" src="%s" label="%s" srclang="%s" />', 
				esc_attr( aiovg_resolve_url( $track['src'] ) ), 				
				esc_attr( $track['label'] ),
				esc_attr( $track['srclang'] )
			);
		}

		echo '</video>';
	}
	?>

	<?php if ( isset( $settings['share'] ) || isset( $settings['embed'] ) ) : ?>
		<div id="plyr__share-embed-modal" class="plyr__share-embed-modal" style="display: none;">
			<div class="plyr__share-embed-modal-content">
				<?php if ( isset( $settings['share'] ) ) : ?>
					<!-- Share Buttons -->
					<div class="plyr__share">
						<?php
						foreach ( $share_buttons as $button ) {
							printf( 
								'<a href="%s" class="plyr__share-button plyr__share-button-%s" target="_blank"><span class="%s"></span></a>',							
								esc_attr( $button['url'] ), 
								esc_attr( $button['service'] ),
								esc_attr( $button['icon'] ) 
							);
						}
						?>
					</div>
				<?php endif; ?>

				<?php if ( isset( $settings['embed'] ) ) : ?>
					<!-- Embed Code -->
					<div class="plyr__embed">
						<p><?php esc_html_e( 'Paste this code in your HTML page', 'all-in-one-video-gallery' ); ?></p>
						<input type="text" id="plyr__embed-code-input" value="<?php echo htmlspecialchars( $embed_code ); ?>" readonly />
					</div>
				<?php endif; ?>

				<!-- Close Button -->
				<button type="button" id="plyr__share-embed-modal-close-button" class="plyr__controls__item plyr__control plyr__share-embed-modal-close-button aiovg-icon-close"></button>

			</div>
		</div>
	<?php endif; ?>
    
	<script src="<?php echo AIOVG_PLUGIN_URL; ?>vendor/vidstack/plyr.polyfilled.js?v=3.7.8" type="text/javascript"></script>

	<?php if ( isset( $sources['hls'] ) ) : ?>
		<script src="<?php echo AIOVG_PLUGIN_URL; ?>vendor/vidstack/hls.min.js?v=1.4.3" type="text/javascript"></script>
	<?php endif; ?>

	<?php if ( isset( $sources['dash'] ) ) : ?>
		<script src="<?php echo AIOVG_PLUGIN_URL; ?>vendor/vidstack/dash.all.min.js" type="text/javascript"></script>
	<?php endif; ?>

	<?php do_action( 'aiovg_iframe_vidstack_player_footer', $settings, $attributes, $sources, $tracks ); ?>

    <script type="text/javascript">
		'use strict';			
			
		/**
		 * Vars
		 */ 

		var settings = <?php echo json_encode( $settings ); ?>;		

		/**
		 * Helper Functions
		 */ 

		function update_views_count() {
			var xmlhttp;

			if ( window.XMLHttpRequest ) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject( 'Microsoft.XMLHTTP' );
			};
			
			xmlhttp.onreadystatechange = function() {				
				if ( 4 == xmlhttp.readyState && 200 == xmlhttp.status ) {					
					if ( xmlhttp.responseText ) {
						// Do nothing
					}						
				}					
			};	

			xmlhttp.open( 'GET', '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=aiovg_update_views_count&post_id=<?php echo $post_id; ?>&security=<?php echo wp_create_nonce( 'aiovg_views_nonce' ); ?>', true );
			xmlhttp.send();							
		}

		/**
		 * Initialize the player.
		 */		
		var video  = document.getElementById( 'player' ); 
		var player = new Plyr( video, settings.player );

		var plyr = document.getElementsByClassName( 'plyr' )[0];

		// Dispatch an event
		var evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( 'player.init', false, false, { player: player, settings: settings } );
		window.dispatchEvent( evt );

		// Update views count
		var viewed = false;

		player.on( 'playing', function() {
			if ( ! viewed ) {
				viewed = true;

				if ( 'aiovg_videos' == settings.post_type ) {
					update_views_count();
				}
			}
		});

		// On ended
		var message_sent = false;

		player.on( 'ended', function() {
			plyr.className += ' plyr--stopped';

			 // Autoplay next video
			 if ( settings.hasOwnProperty( 'autoadvance' ) ) {
				if ( ! message_sent ) {
					message_sent = true;

					window.parent.postMessage(
						{ 				
							message: 'aiovg-video-ended',			
							id: settings.uid
						},
						'*'
					); 
				}
			}
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
			
			plyr.appendChild( share_button );					

			var close_button = document.getElementById( 'plyr__share-embed-modal-close-button' );

			var modal = document.getElementById( 'plyr__share-embed-modal' );
			plyr.appendChild( modal );	
			modal.style.display = '';

			// Show Modal
			var was_playing = false;

			share_button.addEventListener( 'click',  function() {
				if ( player.playing ) {
					was_playing = true;
					player.pause();
				} else {
					was_playing = false;
				}                    

				share_button.style.display = 'none';						
				modal.className += ' fadein';
			});

			// Hide Modal
			close_button.addEventListener( 'click',  function() {
				if ( was_playing ) {
					player.play();
				}

				modal.className = modal.className.replace( ' fadein', '' );
				setTimeout(function() {
                    share_button.style.display = ''; 
                }, 500 );				                           	
			});

			// Copy Embedcode
			if ( settings.hasOwnProperty( 'embed' ) ) {
				document.getElementById( 'plyr__embed-code-input' ).addEventListener( 'focus', function() {
					this.select();	
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

			plyr.appendChild( logo );
		}

		// Custom ContextMenu
		if ( settings.hasOwnProperty( 'contextmenu' ) ) {
			var contextmenu = document.createElement( 'div' );
			contextmenu.className = 'plyr__contextmenu';
			contextmenu.innerHTML = '<div class="plyr__contextmenu-content">' +  settings.contextmenu.content +' </div>';
			contextmenu.style.display = 'none';

			plyr.appendChild( contextmenu );

			var timeout_handler = '';
			
			document.addEventListener( 'contextmenu', function( e ) {						
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
					top.window.location.href = settings.logo.link;
				});
			}
			
			document.addEventListener( 'click', function() {
				contextmenu.style.display = 'none';								 
			});	
		}		
    </script>	
</body>
</html>