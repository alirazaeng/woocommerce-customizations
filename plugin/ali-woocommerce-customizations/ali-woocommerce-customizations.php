<?php
/**
 * Plugin Name: Ali WooCommerce Customizations
 * Description: Modular, opt-in WooCommerce customization patterns for products, cart, checkout, accounts, orders, and frontend assets.
 * Version: 0.1.0
 * Author: Engineer Ali Raza
 * License: MIT
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * WC requires at least: 8.0
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

define( 'ARWC_VERSION', '0.1.0' );
define( 'ARWC_FILE', __FILE__ );
define( 'ARWC_DIR', plugin_dir_path( __FILE__ ) );
define( 'ARWC_URL', plugin_dir_url( __FILE__ ) );

/**
 * Declare compatibility with WooCommerce features when the API is available.
 *
 * High-Performance Order Storage compatibility is declared because order
 * metadata is read and written through WooCommerce CRUD objects.
 *
 * @return void
 */
function arwc_declare_woocommerce_compatibility() {
	$features_util = '\\Automattic\\WooCommerce\\Utilities\\FeaturesUtil';

	if ( class_exists( $features_util ) ) {
		$features_util::declare_compatibility(
			'custom_order_tables',
			ARWC_FILE,
			true
		);
	}
}
add_action( 'before_woocommerce_init', 'arwc_declare_woocommerce_compatibility' );

/**
 * Bootstrap after plugins are loaded so WooCommerce availability is known.
 *
 * @return void
 */
function arwc_bootstrap() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'arwc_missing_woocommerce_notice' );
		return;
	}

	require_once ARWC_DIR . 'includes/class-arwc-plugin.php';

	ARWC_Plugin::instance()->init();
}
add_action( 'plugins_loaded', 'arwc_bootstrap' );

/**
 * Register rewrite state before flushing during activation.
 *
 * @return void
 */
function arwc_activate() {
	add_rewrite_endpoint( 'project-notes', EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'arwc_activate' );

/**
 * Flush rewrite rules after deactivation.
 *
 * @return void
 */
function arwc_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'arwc_deactivate' );

/**
 * Admin notice shown when WooCommerce is unavailable.
 *
 * @return void
 */
function arwc_missing_woocommerce_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p>';
	echo esc_html__( 'Ali WooCommerce Customizations requires WooCommerce to be active.', 'ali-woocommerce-customizations' );
	echo '</p></div>';
}
