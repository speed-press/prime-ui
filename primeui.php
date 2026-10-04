<?php
/**
 * Plugin Name:       PrimeUI Widgets for Elementor
 * Plugin URI:        https://wpspeedpress.com
 * Description:       Free Elementor widgets with layout, color, type, and spacing controls. Not affiliated with Elementor.
 * Version:           1.2.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            PrimeUI
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       primeui
 * Elementor tested up to: 3.25.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PUI_VERSION', '1.2.0' );
define( 'PUI_FILE', __FILE__ );
define( 'PUI_PATH', plugin_dir_path( __FILE__ ) );
define( 'PUI_URL', plugin_dir_url( __FILE__ ) );
define( 'PUI_MIN_PHP', '7.4' );
define( 'SPAE_VERSION', PUI_VERSION );
define( 'SPAE_PATH', PUI_PATH );
define( 'SPAE_URL', PUI_URL );

function pui_load() {
	if ( version_compare( PHP_VERSION, PUI_MIN_PHP, '<' ) ) {
		add_action( 'admin_notices', 'pui_php_notice' );
		return;
	}
	require_once PUI_PATH . 'includes/class-plugin.php';
	\SpeedPress\Addons\Plugin::instance();
}
add_action( 'plugins_loaded', 'pui_load' );

function pui_php_notice() {
	echo '<div class="notice notice-error"><p>' . esc_html( sprintf( 'PrimeUI Widgets requires PHP %s or newer.', PUI_MIN_PHP ) ) . '</p></div>';
}

function spae_missing_elementor_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'PrimeUI Widgets needs the free Elementor plugin active.', 'primeui' ) . '</p></div>';
}
