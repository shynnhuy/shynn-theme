<?php

class us_migration_5_1 extends US_Migration_Translator {

	// Content
	public function translate_content( &$content ) {
		return $this->_translate_content( $content );
	}

	public function translate_gallery( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['size'] ) AND $params['size'] == 'medium_large' ) {
			$params['size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_gallery( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['img_size'] ) AND $params['img_size'] == 'medium_large' ) {
			$params['img_size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_grid( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['items_offset'] ) AND $params['items_offset'] != '' ) {
			$params['exclude_items'] = 'offset';
			$changed = TRUE;
		}
		if ( isset( $params['img_size'] ) AND $params['img_size'] == 'medium_large' ) {
			$params['img_size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_image_slider( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['img_size'] ) AND $params['img_size'] == 'medium_large' ) {
			$params['img_size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_logos( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['img_size'] ) AND $params['img_size'] == 'medium_large' ) {
			$params['img_size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_person( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['img_size'] ) AND $params['img_size'] == 'medium_large' ) {
			$params['img_size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_single_image( &$name, &$params, &$content ) {
		$changed = FALSE;

		if ( isset( $params['size'] ) AND $params['size'] == 'medium_large' ) {
			$params['size'] = 'us_768_0';
			$changed = TRUE;
		}

		return $changed;
	}

	public function translate_us_social_links( &$name, &$params, &$content ) {

		if ( ! empty( $params['style'] ) AND $params['style'] == 'solid_square' ) {
			$params['style'] = 'solid';
			if ( us_get_option( 'rounded_corners' ) ) {
				$params['shape'] = 'rounded';
			}
		} elseif ( ! empty( $params['style'] ) AND $params['style'] == 'outlined_square' ) {
			$params['style'] = 'outlined';
			if ( us_get_option( 'rounded_corners' ) ) {
				$params['shape'] = 'rounded';
			}
		} elseif ( ! empty( $params['style'] ) AND $params['style'] == 'solid_circle' ) {
			$params['style'] = 'solid';
			$params['shape'] = 'circle';
		} elseif ( ! empty( $params['style'] ) AND $params['style'] == 'outlined_circle' ) {
			$params['style'] = 'outlined';
			$params['shape'] = 'circle';
		} else {
			$params['gap'] = '0';
		}

		return TRUE;
	}

	// Options
	public function translate_theme_options( &$options ) {
		/*
		 * Replace medim_large image size for Theme Options
		 */
		if ( isset( $options['post_preview_img_size'] ) AND $options['post_preview_img_size'] == 'medium_large' ) {
			$options['post_preview_img_size'] = 'us_768_0';
		}
		if ( isset( $options['post_related_img_size'] ) AND $options['post_related_img_size'] == 'medium_large' ) {
			$options['post_related_img_size'] = 'us_768_0';
		}
		if ( isset( $options['blog_img_size'] ) AND $options['blog_img_size'] == 'medium_large' ) {
			$options['blog_img_size'] = 'us_768_0';
		}
		if ( isset( $options['archive_img_size'] ) AND $options['archive_img_size'] == 'medium_large' ) {
			$options['archive_img_size'] = 'us_768_0';
		}
		if ( isset( $options['search_img_size'] ) AND $options['search_img_size'] == 'medium_large' ) {
			$options['search_img_size'] = 'us_768_0';
		}
		$new_img_size_present = FALSE;
		if ( empty( $options['img_size'] ) OR ! is_array( $options['img_size'] ) ) {
			$options['img_size'] = array();
		}
		foreach( $options['img_size'] as $i => $size ) {
			if ( $size['width'] == 768 AND $size['height'] == 0 AND ( $size['crop'] == array() OR empty( $size['crop'] ) ) ) {
				$new_img_size_present = TRUE;
			}
		}
		if ( ! $new_img_size_present ) {
			$options['img_size'][] = array(
				'width' => 768,
				'height' => 0,
				'crop' => array(),
			);
		}

		/*
		 * Translate Social links in headers
		 */
		ob_start();
		$headers = get_posts( array( 'post_type' => 'us_header', 'numberposts' => - 1, ) );
		ob_end_clean();
		foreach ( $headers as $header ) {
			if ( ! empty( $header->post_content ) AND substr( strval( $header->post_content ), 0, 1 ) === '{' ) {
				try {
					$header_settings = json_decode( $header->post_content, TRUE );
					$header_changed = FALSE;
					if ( isset( $header_settings['data'] ) and is_array( $header_settings['data'] ) ) {
						foreach ( $header_settings['data'] as $name => $data ) {
							// Find the social links element
							if ( substr( $name, 0, 7 ) == 'socials' ) {
								$social_items = array();
								foreach ( $this->old_social_links as $link_type => $link_title ) {
									if ( ! empty( $data[$link_type] ) ) {
										$social_items[] = array(
											'type' => $link_type,
											'url' => $data[$link_type],
										);
									}
								}
								if ( ! empty( $data['custom_url'] ) AND ! empty( $data['custom_icon'] ) ){
									$social_items[] = array(
										'type' => 'custom',
										'url' => $data['custom_url'],
										'icon' => $data['custom_icon'],
										'title' => ( ! empty ( $data['custom_title'] ) ) ? $data['custom_title'] : '',
										'color' => ( ! empty ( $data['custom_color'] ) ) ? $data['custom_color'] : '#1abc9c',
									);
								}
								if ( count( $social_items ) > 0 ) {
									$header_settings['data'][$name]['items'] = $social_items;
									$header_changed = TRUE;
								}
								if ( ! empty( $data['hover'] ) AND $data['hover'] == 'default' ) {
									$header_settings['data'][$name]['hover'] = 'slide';
								}
							}
						}
					}

					if ( $header_changed ) {
						ob_start();
						wp_update_post(
							array(
								'ID' => $header->ID,
								'post_content' => str_replace( "\\'", "'", json_encode( wp_slash( $header_settings ), JSON_UNESCAPED_UNICODE ) ),
							)
						);
						ob_end_clean();
					}
				}
				catch ( Exception $e ) {
				}
			}
		}

		/*
		 * Replace medim_large image size for Grid layouts
		 */
		ob_start();
		$grid_layouts = get_posts( array( 'post_type' => 'us_grid_layout', 'numberposts' => - 1, ) );
		ob_end_clean();
		foreach ( $grid_layouts as $grid_layout ) {
			if ( ! empty( $grid_layout->post_content ) AND substr( strval( $grid_layout->post_content ), 0, 1 ) === '{' ) {
				try {
					$grid_layout_settings = json_decode( $grid_layout->post_content, TRUE );
					$grid_layout_changed = FALSE;

					if ( isset( $grid_layout_settings['data'] ) and is_array( $grid_layout_settings['data'] ) ) {
						foreach ( $grid_layout_settings['data'] as $name => $data ) {
							// Post Image and Post Custom Field elements
							if ( substr( $name, 0, 10 ) == 'post_image' OR substr( $name, 0, 17 ) == 'post_custom_field' ) {
								if ( ! empty( $data['thumbnail_size'] ) AND $data['thumbnail_size'] == 'medium_large' ) {
									$header_settings['data'][$name]['thumbnail_size'] = 'us_768_0';
									$grid_layout_changed = TRUE;
								}
							}
						}
					}

					if ( $grid_layout_changed ) {
						ob_start();
						wp_update_post(
							array(
								'ID' => $grid_layout->ID,
								'post_content' => str_replace( "\\'", "'", json_encode( wp_slash( $grid_layout_settings ), JSON_UNESCAPED_UNICODE ) ),
							)
						);
						ob_end_clean();
					}
				}
				catch ( Exception $e ) {
				}
			}
		}

		/*
		 * Regenerate sizes data for images
		 */
		$attachments = get_posts(
			array(
				'post_type' => 'attachment',
				'posts_per_page' => - 1,
				'post_status' => 'any',
				'numberposts' => - 1,
			)
		);
		foreach ( $attachments as $attachment ) {
			$attachment_ID = $attachment->ID;
			if ( is_array( $imagedata = wp_get_attachment_metadata( $attachment_ID ) ) ) {
				if ( isset ( $imagedata['sizes']['medium_large'] ) ) {
					$imagedata['sizes']['us_768_0'] = $imagedata['sizes']['medium_large'];
				}
				wp_update_attachment_metadata( $attachment_ID, $imagedata );
			}
		}

		return TRUE;
	}

	public function translate_widgets( &$name, &$instance ) {
		$changed = FALSE;

		if ( $name == 'us_socials' ) {

			if ( ! empty( $instance['style'] ) AND $instance['style'] == 'solid_square' ) {
				$instance['style'] = 'solid';
				if ( us_get_option( 'rounded_corners' ) ) {
					$instance['shape'] = 'rounded';
				}
			} elseif ( ! empty( $instance['style'] ) AND $instance['style'] == 'outlined_square' ) {
				$instance['style'] = 'outlined';
				if ( us_get_option( 'rounded_corners' ) ) {
					$instance['shape'] = 'rounded';
				}
			} elseif ( ! empty( $instance['style'] ) AND $instance['style'] == 'solid_circle' ) {
				$instance['style'] = 'solid';
				$instance['shape'] = 'circle';
			} elseif ( ! empty( $instance['style'] ) AND $instance['style'] == 'outlined_circle' ) {
				$instance['style'] = 'outlined';
				$instance['shape'] = 'circle';
			}

			$changed = TRUE;
		}

		return $changed;

	}

	private $old_social_links = array(
		'email' => 'Email',
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
		'google' => 'Google',
		'linkedin' => 'LinkedIn',
		'youtube' => 'YouTube',
		'vimeo' => 'Vimeo',
		'flickr' => 'Flickr',
		'behance' => 'Behance',
		'instagram' => 'Instagram',
		'xing' => 'Xing',
		'pinterest' => 'Pinterest',
		'skype' => 'Skype',
		'whatsapp' => 'WhatsApp',
		'dribbble' => 'Dribbble',
		'vk' => 'Vkontakte',
		'tumblr' => 'Tumblr',
		'soundcloud' => 'SoundCloud',
		'twitch' => 'Twitch',
		'yelp' => 'Yelp',
		'deviantart' => 'DeviantArt',
		'foursquare' => 'Foursquare',
		'github' => 'GitHub',
		'odnoklassniki' => 'Odnoklassniki',
		's500px' => '500px',
		'houzz' => 'Houzz',
		'medium' => 'Medium',
		'tripadvisor' => 'Tripadvisor',
		'rss' => 'RSS',
	);

}
