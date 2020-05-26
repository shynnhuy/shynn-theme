<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

class US_Layout {

	/**
	 * @var US_Layout
	 */
	protected static $instance;

	/**
	 * Singleton pattern: US_Layout::instance()->do_something()
	 *
	 * @return US_Layout
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @var string Canvas type: wide / boxed
	 */
	public $canvas_type;

	/**
	 * @var string Default-state header orientation: 'hor' / 'ver'
	 */
	public $header_orientation;

	/**
	 * @var string Default-state header position: 'static' / 'fixed'
	 */
	public $header_pos;

	/**
	 * @var string Default-state header background: 'solid' / 'transparent'
	 */
	public $header_bg;

	/**
	 * @var string Default-state header show: 'always' / 'never'
	 */
	public $header_show;

	protected function __construct() {

		do_action( 'us_layout_before_init', $this );

		if ( WP_DEBUG AND ! ( isset( $GLOBALS['post'] ) OR is_404() OR is_search() OR is_archive() OR ( is_home() AND ! have_posts() ) ) ) {
			wp_die( 'US_Layout can be inited only after the current post is obtained' );
		}

		$this->canvas_type = us_get_option( 'canvas_layout', 'wide' );
		$this->header_orientation = us_get_header_option( 'orientation', 'default', 'hor' );
		$this->header_pos = us_get_header_option( 'sticky', 'default', FALSE ) ? 'fixed' : 'static';
		$this->header_initial_pos = 'top';
		$this->header_bg = us_get_header_option( 'transparent', 'default', FALSE ) ? 'transparent' : 'solid';
		$this->header_shadow = us_get_header_option( 'shadow', 'default', 'thin' );
		$this->header_show = 'always';

		// Some of the options may be overloaded by post's meta settings
		$public_cpt = array_keys( us_get_public_cpt() );
		$postID = NULL;
		if ( is_singular() ) {
			$postID = get_the_ID();
		}
		if ( is_404() ) {
			$postID = us_get_option( 'page_404' );
		}
		if ( is_search() AND ! is_post_type_archive( 'product' ) ) {
			$postID = us_get_option( 'search_page' );
		}
		if ( is_home() ) {
			$postID = us_get_option( 'posts_page' );
		}
		if ( is_singular(
				array_merge(
					array(
						'post',
						'page',
						'us_portfolio',
						'product',
					), $public_cpt
				)
			) OR ( ( is_404() OR is_search() OR is_home() ) AND $postID != NULL AND $postID != 'default' ) ) {

			if ( metadata_exists( 'post', $postID, 'us_header_id' ) AND usof_meta( 'us_header_id', array(), $postID ) == '' ) {
				$this->header_show = 'never';
				$this->header_orientation = 'none';
			} elseif ( usof_meta( 'us_header_sticky_pos', array(), $postID ) != '' AND $this->header_orientation == 'hor' ) {
				$this->header_initial_pos = usof_meta( 'us_header_sticky_pos', array(), $postID );
			}
		}

		// Remove header for popup iframes (available in Grid Overriding Link)
		global $us_iframe;
		if ( isset( $us_iframe ) AND $us_iframe ) {
			$this->header_show = 'never';
			$this->header_orientation = 'none';
		}

		$this->post_id = $postID;

		if ( $this->header_orientation == 'ver' ) {
			$this->header_pos = 'fixed';
			$this->header_bg = 'solid';
		}

		do_action( 'us_layout_after_init', $this );
	}

	/**
	 * Obtain theme-defined CSS classes for <html> element
	 *
	 * @return string
	 */
	public function html_classes() {
		$classes = '';

		if ( ! us_get_option( 'responsive_layout', TRUE ) ) {
			$classes .= 'no-responsive';
		}

		return $classes;
	}

	/**
	 * Obtain theme-defined CSS classes for <body> element
	 *
	 * @return string
	 */
	public function body_classes() {
		$classes = US_THEMENAME . '_' . US_THEMEVERSION;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'us-header-builder/us-header-builder.php' ) ) {
			$classes .= ' HB';
			if ( defined( 'US_HB_VERSION' ) ) {
				$classes .= '_' . US_HB_VERSION;
			} else {
				$hb_data = @get_plugin_data( ABSPATH . 'wp-content/plugins/us-header-builder/us-header-builder.php' );
				$classes .= '_' . $hb_data['Version'];
			}
		}
		$classes .= ' header_' . $this->header_orientation;
		$classes .= ' headerinpos_' . $this->header_initial_pos;
		if ( us_get_option( 'links_underline' ) == TRUE ) {
			$classes .= ' links_underline';
		}
		if ( us_get_option( 'rounded_corners' ) !== NULL AND us_get_option( 'rounded_corners' ) == FALSE ) {
			$classes .= ' rounded_none';
		}
		$classes .= ' state_default';

		global $us_iframe;
		if ( isset( $us_iframe ) AND $us_iframe ) {
			$classes .= ' us_iframe';
		}

		return $classes;
	}

	/**
	 * Obtain CSS classes for .l-canvas
	 *
	 * @return string
	 */
	public function canvas_classes() {

		$classes = 'type_' . $this->canvas_type;

		// Language modificator
		if ( defined( 'ICL_LANGUAGE_CODE' ) AND ICL_LANGUAGE_CODE ) {
			$classes .= ' wpml_lang_' . ICL_LANGUAGE_CODE;
		}

		return $classes;
	}

	/**
	 * Obtain CSS classes for .l-header
	 *
	 * @return string
	 */
	public function header_classes() {

		$classes = 'pos_' . $this->header_pos;
		$classes .= ' bg_' . $this->header_bg;
		$classes .= ' shadow_' . $this->header_shadow;

		return $classes;
	}

}
