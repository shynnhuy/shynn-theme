<?php

class us_migration_5_2 extends US_Migration_Translator {

	// Options
	public function translate_theme_options( &$options ) {
		/*
		 * Translate Text element in headers
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
							// Find the text element
							if ( substr( $name, 0, 4 ) == 'text' ) {
								if ( ! empty( $data['text'] ) AND strpos( $data['text'], '<strong' ) !== FALSE  ) {
									if ( empty( $header_settings['data'][$name]['text_style'] ) ) {
										$header_settings['data'][$name]['text_style'] = array();
									}
									if ( ! in_array( 'bold', $header_settings['data'][$name]['text_style'] ) ) {
										$header_settings['data'][$name]['text_style'][] = 'bold';
									}
									$header_changed = TRUE;

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

		return FALSE;
	}

}
