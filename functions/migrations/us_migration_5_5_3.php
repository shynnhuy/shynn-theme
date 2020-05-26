<?php

class us_migration_5_5_3 extends US_Migration_Translator {

	// Theme Options
	public function translate_theme_options( &$options ) {
		$changed = FALSE;

		/*
		 * Grid Layout Buttons
		 */
		ob_start();
		$grid_layouts = get_posts( array( 'post_type' => 'us_grid_layout', 'numberposts' => - 1, ) );
		ob_end_clean();
		foreach ( $grid_layouts as $grid_layout ) {
			if ( ! empty( $grid_layout->post_content ) AND substr( strval( $grid_layout->post_content ), 0, 1 ) === '{' ) {
				try {
					$grid_settings = json_decode( $grid_layout->post_content, TRUE );
					$grid_layout_changed = FALSE;
					if ( isset( $grid_settings['data'] ) and is_array( $grid_settings['data'] ) ) {
						foreach ( $grid_settings['data'] as $name => $data ) {
							// HTML element
							if ( substr( $name, 0, 4 ) == 'html' ) {
								// Check if maybe the HTML was already encoded
								if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data['content'])) {
									continue;
								}
								$grid_settings['data'][ $name ]['content'] = base64_encode( rawurlencode( $data['content'] ) );
								$grid_layout_changed = TRUE;
							}
						}
					}

					if ( $grid_layout_changed ) {
						ob_start();
						wp_update_post(
							array(
								'ID' => $grid_layout->ID,
								'post_content' => str_replace( "\\'", "'", json_encode( wp_slash( $grid_settings ), JSON_UNESCAPED_UNICODE ) ),
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
		 * Header HTML Elements
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
							// HTML element
							if ( substr( $name, 0, 4 ) == 'html' ) {
								// Check if maybe the HTML was already encoded
								if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data['content'])) {
									continue;
								}
								$header_settings['data'][ $name ]['content'] = base64_encode( rawurlencode( $data['content'] ) );
								$header_changed = TRUE;
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

		return $changed;
	}
}
