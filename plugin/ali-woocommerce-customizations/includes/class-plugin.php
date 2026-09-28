<?php
/**
 * Main plugin coordinator.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

final class ARWC_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var ARWC_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the plugin instance.
	 *
	 * @return ARWC_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load modules.
	 *
	 * @return void
	 */
	public function init() {
		$files = array(
			'class-product.php',
			'class-cart.php',
			'class-checkout.php',
			'class-account.php',
			'class-order.php',
			'class-assets.php',
		);

		foreach ( $files as $file ) {
			require_once ARWC_DIR . 'includes/' . $file;
		}

		( new ARWC_Product() )->init();
		( new ARWC_Cart() )->init();
		( new ARWC_Checkout() )->init();
		( new ARWC_Account() )->init();
		( new ARWC_Order() )->init();
		( new ARWC_Assets() )->init();
	}

	/**
	 * Whether an optional example feature is enabled.
	 *
	 * Every runtime customization is disabled by default. Integrators opt in
	 * through the named filter so installing the portfolio plugin cannot
	 * unexpectedly change a live store.
	 *
	 * @param string $feature Feature slug.
	 * @return bool
	 */
	public static function feature_enabled( $feature ) {
		$feature = sanitize_key( $feature );

		/**
		 * Filter whether a customization feature is active.
		 *
		 * The hook is dynamic, e.g. arwc_enable_low_stock_message.
		 *
		 * @param bool $enabled Whether the feature should run.
		 */
		return (bool) apply_filters( 'arwc_enable_' . $feature, false );
	}
}
