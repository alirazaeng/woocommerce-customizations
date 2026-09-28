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
 * WC tested up to: 10.0
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
 */
function arwc_declare_woocommerce_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		AutomatticWooCommerceUtilitiesFeaturesUtil::declare_compatibility(
			'custom_order_tables',
			ARWC_FILE,
			true
		);
	}
}
add_action( 'before_woocommerce_init', 'arwc_declare_woocommerce_compatibility' );

/**
 * Bootstrap after plugins are loaded so WooCommerce availability is known.
 */
function arwc_bootstrap() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'arwc_missing_woocommerce_notice' );
		return;
	}

	require_once ARWC_DIR . 'includes/class-plugin.php';

	ARWC_Plugin::instance()->init();
}
add_action( 'plugins_loaded', 'arwc_bootstrap' );

/**
 * Admin notice shown when WooCommerce is unavailable.
 */
function arwc_missing_woocommerce_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p>';
	echo esc_html__( 'Ali WooCommerce Customizations requires WooCommerce to be active.', 'ali-woocommerce-customizations' );
	echo '</p></div>';
}
