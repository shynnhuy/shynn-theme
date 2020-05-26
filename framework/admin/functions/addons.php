<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Addons admin page
 */

if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! current_user_can( 'install_plugins' ) ) {
	return;
}

$js_composer_path = 'js_composer';
$installed_plugins = get_plugins();
$keys = array_keys( $installed_plugins );

foreach ( $keys as $key ) {
	if ( preg_match( '|^' . $js_composer_path . '/|', $key ) ) {
		$js_composer_path = $key;
		break;
	}
}

// Display admin notice when WPBakery Page Builder is not active
if ( ( ! get_option( 'us_dismiss_addons_install_notice' ) ) AND ( ! isset( $installed_plugins[ $js_composer_path ] ) ) ) {
	add_action( 'admin_notices', 'us_js_composer_install_admin_notice' );
}
if ( ( ! get_option( 'us_dismiss_addons_activate_notice' ) ) AND ( isset( $installed_plugins[ $js_composer_path ] ) ) AND is_plugin_inactive( $js_composer_path ) ) {
	add_action( 'admin_notices', 'us_js_composer_activate_admin_notice' );
}
function us_js_composer_install_admin_notice() {
	?>
	<div class="notice notice-warning us-addons-notice for-installing is-dismissible">
		<p><?php echo sprintf( __( 'This theme recommends to use %s plugin.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '">WPBakery Page Builder</a></strong>' ); ?></p>
	</div>
	<?php
}

function us_js_composer_activate_admin_notice() {
	?>
	<div class="notice notice-warning us-addons-notice for-activating is-dismissible">
		<p><?php echo sprintf( __( 'This theme recommends to use %s plugin.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '">WPBakery Page Builder</a></strong>' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_print_scripts', 'us_admin_addons_assets', 99 );
function us_admin_addons_assets() {
	?>
	<script>
		jQuery(document).on('click', '.us-addons-notice .notice-dismiss', function(){
			var $notice = jQuery(this).closest('.us-addons-notice'),
				pluginAction = ($notice.hasClass('for-activating')) ? 'activate' : 'install';
			jQuery.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				data: {
					action: 'us_dismiss_addons_notice',
					pluginAction: pluginAction
				}
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_us_dismiss_addons_notice', 'us_dismiss_addons_notice' );
function us_dismiss_addons_notice() {
	if ( $_GET['pluginAction'] == 'activate' ) {
		update_option( 'us_dismiss_addons_activate_notice', 1 );
	} elseif ( $_GET['pluginAction'] == 'install' ) {
		update_option( 'us_dismiss_addons_install_notice', 1 );
	}
}

add_action( 'admin_menu', 'us_add_addons_page', 20 );
function us_add_addons_page() {
	add_submenu_page( 'us-theme-options', US_THEMENAME . ': ' . __( 'Addons', 'us' ), __( 'Addons', 'us' ), 'manage_options', 'us-addons', 'us_addons_page', 11 );
}

function us_addons_page() {
	$plugins = us_config( 'addons', array() );

	foreach ( $plugins as $i => $plugin ) {
		if ( isset( $plugins[ $i ]['premium'] ) AND ! isset( $plugins[ $i ]['source'] ) ) {
			$plugins = us_api_addons( $plugins, TRUE );
			break;
		}
	}

	$installed_plugins = get_plugins();
	global $us_template_directory_uri;
	$plugins_html = '';

	foreach ( $plugins as $plugin ) {

		$keys = array_keys( get_plugins() );

		$plugin['file_path'] = $plugin['slug'];
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $plugin['slug'] . '/|', $key ) ) {
				$plugin['file_path'] = $key;
				break;
			}
		}

		$classes = ' ' . $plugin['slug'];
		if ( ! isset( $plugin['premium'] ) ) {
			$classes .= ' is_free';
		} else {
			$classes .= ' is_premium';
		}
		$btn_classes = $btn_atts = $btn_text = $btn_url = '';

		// Separate params for FREE and PREMIUM plugins
		if ( ! isset( $plugin['premium'] ) ) {
			$icon_file = isset( $plugin['icon_file'] ) ? $plugin['icon_file'] : 'png';
			$icon_url = 'https://ps.w.org/' . $plugin['slug'] . '/assets/icon-256x256.' . $icon_file;
			$plugin['url'] = 'https://wordpress.org/plugins/' . $plugin['slug'] . '/';
		} else {
			$icon_url = $us_template_directory_uri . '/framework/admin/img/' . $plugin['slug'] . '.png';
		}

		if ( is_plugin_active( $plugin['file_path'] ) ) {
			$classes .= ' status_active';

		} elseif ( ! isset( $installed_plugins[ $plugin['file_path'] ] ) ) {

			if ( isset( $plugin['premium'] ) AND ! get_option( 'us_license_activated', 0 ) ) {
				$classes .= ' status_locked';
			} else {
				$classes .= ' status_notinstalled';
				$btn_text = us_translate( 'Install Now' );
				$btn_url = 'javascript:void(0);';
				$btn_classes .= ' color_primary action-button';
				$btn_atts = ' data-plugin="' . $plugin['slug'] . '" data-action="install"';
			}

		} elseif ( is_plugin_inactive( $plugin['file_path'] ) ) {
			$classes .= ' status_notactive';
			$btn_text = us_translate( 'Activate Plugin' );
			$btn_url = 'javascript:void(0);';
			$btn_classes .= ' color_primary action-button';
			$btn_atts = ' data-plugin="' . $plugin['slug'] . '" data-action="activate"';
		}

		ob_start();

		?>
		<div class="us-addon<?php echo $classes; ?>">
			<div class="us-addon-content">
				<a href="<?php echo $plugin['url'] ?>" target="_blank">
					<img class="us-addon-icon" src="<?php echo $icon_url; ?>" alt="icon">
					<h2 class="us-addon-title"><?php echo $plugin['name'] ?></h2>
				</a>
				<p class="us-addon-desc"><?php echo $plugin['description']; ?></p>
			</div>
			<div class="us-addon-control">
				<div class="us-addon-status"><?php echo us_translate_x( 'Active', 'plugin' ); ?></div>
				<?php
				// Screenlock for premium plugins
				if ( isset( $plugin['premium'] ) AND ! get_option( 'us_license_activated', 0 ) ) {
					?>
					<div class="us-addon-lock">
						<div><?php echo sprintf( __( '<a href="%s">Activate the theme</a> to install premium addons', 'us' ), admin_url( 'admin.php?page=us-home#activation' ) ) ?></div>
					</div>
					<?php
				}
				if ( $btn_text != '' AND $btn_url != '' ) {
					?>
					<a class="usof-button<?php echo $btn_classes; ?>" href="<?php echo $btn_url; ?>" <?php echo $btn_atts; ?>><span><?php echo $btn_text; ?></span></a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		$plugins_html .= ob_get_clean();
	}

	?>
	<div class="us-addons">
		<h1 class="us-admin-title"><?php echo __( 'Recommended Plugins', 'us' ); ?></h1>
		<p class="us-admin-subtitle"><?php echo sprintf( __( 'Premium and free plugins, compatible with %s', 'us' ), US_THEMENAME ) ?></p>
		<div class="us-addons-list"><?php echo $plugins_html ?></div>
	</div>
	<script>
		jQuery(function($){
			var isRunning = false;
			$('.action-button').click(function(){
				if (isRunning) return;
				isRunning = true;
				$('.us-addons-list').addClass('disable-buttons');
				var plugin = $(this).attr('data-plugin'),
					action = $(this).attr('data-action'),
					$tile = $(this).closest('.us-addon'),
					$status = $tile.find('.us-addon-status'),
					$button = $(this);

				$tile.removeClass(function(index, css){
					return (css.match(/(^|\s)status_\S+/g) || []).join(' ');
				});
				if (action == 'install') {
					$tile.addClass('status_installing');
					$button.html('<i class="g-preloader type_1"></i><?php echo esc_js( us_translate( 'Installing...' ) ); ?>');
				} else {
					$tile.addClass('status_activating');
					$button.html('<i class="g-preloader type_1"></i><?php esc_js( _e( 'Activating...', 'us' ) ); ?>');
				}
				$.ajax({
					type: 'POST',
					url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
					data: {
						action: 'us_ajax_addons_' + action,
						plugin: plugin,
						security: '<?php echo wp_create_nonce( 'us-addons-actions' ); ?>'
					},
					success: function(data){
						isRunning = false;
						$('.us-addons-list').removeClass('disable-buttons');
						$button.hide();
						$tile.removeClass(function(index, css){
							return (css.match(/(^|\s)status_\S+/g) || []).join(' ');
						});
						if (data != undefined && data.success) {
							$tile.addClass('status_active');
							$status.html('<?php echo esc_js( us_translate_x( 'Active', 'plugin' ) ); ?>');
						} else {
							$tile.addClass('status_error');
							if (data != undefined && data.data != undefined && data.data.message != undefined) {
								$status.html(data.data.message);
							} else {
								$status.html('<?php echo esc_js( us_translate( 'An error has occurred. Please reload the page and try again.' ) ); ?>');
							}
						}
					}
				});
				return false;
			});
		});
	</script>

	<?php
}

add_action( 'wp_ajax_us_ajax_addons_install', 'us_ajax_addons_install' );
add_action( 'wp_ajax_us_ajax_addons_activate', 'us_ajax_addons_activate' );

function us_ajax_addons_activate() {

	if ( ! check_ajax_referer( 'us-addons-actions', 'security', FALSE ) ) {
		wp_send_json_error(
			array(
				'message' => us_translate( 'An error has occurred. Please reload the page and try again.' ),
			)
		);
	}

	if ( ! isset( $_POST['plugin'] ) OR ! $_POST['plugin'] ) {
		wp_send_json_error( array( 'message' => us_translate( 'An error has occurred. Please reload the page and try again.' ) ) );
	}

	$result = us_activate_plugin();

	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ) );
	}

	wp_send_json_success( array( 'plugin' => $_POST['plugin'] ) );

}

function us_activate_plugin() {
	if ( empty( $_POST['plugin'] ) ) {
		return FALSE;
	}

	$plugins = us_config( 'addons', array() );

	if ( empty( $plugins ) ) {
		return FALSE;
	}

	$_plugins = array();
	foreach ( $plugins as $i => $plugin ) {
		$_plugins[ $plugin['slug'] ] = $plugin;
	}

	$plugins = $_plugins;

	$slug = urldecode( $_POST['plugin'] );

	if ( ! isset( $plugins[ $slug ] ) ) {
		return FALSE;
	}

	$plugin = $plugins[ $slug ];

	$plugin_data = get_plugins( '/' . $plugin['slug'] ); // Retrieve all plugins.
	$plugin_file = array_keys( $plugin_data ); // Retrieve all plugin files from installed plugins.

	$plugin_to_activate = trailingslashit( $plugin['slug'] ) . $plugin_file[0]; // Match plugin slug with appropriate plugin file.
	ob_start();
	$activate = activate_plugin( $plugin_to_activate ); // Activate the plugin.
	ob_get_clean();

	if ( is_wp_error( $activate ) ) {
		return $activate;
	} else {
		return TRUE;
	}
}

function us_ajax_addons_install() {
	set_time_limit( 300 );

	if ( ! check_ajax_referer( 'us-addons-actions', 'security', FALSE ) ) {
		wp_send_json_error(
			array(
				'message' => us_translate( 'An error has occurred. Please reload the page and try again.' ),
			)
		);
	}

	if ( ! isset( $_POST['plugin'] ) OR ! $_POST['plugin'] ) {
		wp_send_json_error( array( 'message' => us_translate( 'An error has occurred. Please reload the page and try again.' ) ) );
	}

	$result = us_install_plugin();

	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ) );
	}

	wp_send_json_success( array( 'plugin' => $_POST['plugin'] ) );
}

function us_install_plugin() {
	if ( empty( $_POST['plugin'] ) ) {
		return FALSE;
	}

	$plugins = us_config( 'addons', array() );

	foreach ( $plugins as $i => $plugin ) {
		if ( ! isset( $plugins[ $i ]['premium'] ) ) {
			// Define WordPress repository plugin URL based on slug
			$plugins[ $i ]['source'] = 'https://downloads.wordpress.org/plugin/' . $plugin['slug'] . '.latest-stable.zip';
		} elseif ( empty( $plugins[ $i ]['source'] ) ) {
			unset( $plugins[ $i ] );
		}
	}

	if ( empty( $plugins ) ) {
		return FALSE;
	}

	$_plugins = array();
	foreach ( $plugins as $i => $plugin ) {
		$_plugins[ $plugin['slug'] ] = $plugin;
	}

	$plugins = $_plugins;

	$slug = urldecode( $_POST['plugin'] );

	if ( ! isset( $plugins[ $slug ] ) ) {
		return FALSE;
	}

	$plugin = $plugins[ $slug ];

	if ( file_exists( trailingslashit( WP_PLUGIN_DIR ) . $slug ) ) {
		return us_activate_plugin();
	}

	if ( ! filesystem_permission_check() ) {
		return new WP_Error( 'us-addons', __( 'Please adjust file permissions to allow plugins installation', 'us' ) );
	}

	$us_template_directory = get_template_directory();

	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$skin = new WP_Ajax_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );

	$install_result = $upgrader->install( $plugin['source'] );

	if ( is_wp_error( $install_result ) ) {
		return $install_result;
	}

	if ( ! $install_result ) {
		return new WP_Error( 'plugin_error', us_translate( 'An error has occurred. Please reload the page and try again.' ) );
	}

	ob_start();
	$activate = activate_plugin( $upgrader->plugin_info() );
	ob_get_clean();
	if ( is_wp_error( $activate ) ) {
		return $activate;
	}

	return $skin->result;
}

function filesystem_permission_check() {
	ob_start();
	$creds = request_filesystem_credentials( '', '', FALSE, FALSE, NULL );
	ob_get_clean();

	// Abort if permissions were not available.
	if ( ! WP_Filesystem( $creds ) ) {
		return FALSE;
	}

	return TRUE;
}
