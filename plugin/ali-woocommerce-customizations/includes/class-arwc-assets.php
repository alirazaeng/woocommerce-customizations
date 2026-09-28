<?php
/**
 * Conditional frontend assets.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

/**
 * Conditional frontend asset module.
 */
class ARWC_Assets {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Load plugin assets only on relevant WooCommerce views.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( is_admin() ) {
			return;
		}

		$is_relevant = is_woocommerce() || is_cart() || is_checkout() || is_account_page();

		if ( ! $is_relevant ) {
			return;
		}

		wp_enqueue_style(
			'arwc-frontend',
			ARWC_URL . 'assets/css/frontend.css',
			array(),
			ARWC_VERSION
		);

		wp_enqueue_script(
			'arwc-frontend',
			ARWC_URL . 'assets/js/frontend.js',
			array(),
			ARWC_VERSION,
			true
		);
	}
}
