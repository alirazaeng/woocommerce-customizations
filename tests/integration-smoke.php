<?php
/**
 * Disposable wp-env integration smoke tests.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

WP_CLI::add_command(
	'arwc-test',
	static function () {
		$assert = static function ( $condition, $message ) {
			if ( ! $condition ) {
				WP_CLI::error( $message );
			}

			WP_CLI::log( 'PASS: ' . $message );
		};

		$assert( class_exists( 'WooCommerce' ), 'WooCommerce is active.' );
		$assert( class_exists( 'ARWC_Plugin' ), 'Ali WooCommerce Customizations booted.' );
		$assert( class_exists( 'ARWC_Product' ), 'Product customization module loaded.' );
		$assert( false === ARWC_Plugin::feature_enabled( 'low_stock_message' ), 'Optional features remain disabled by default.' );

		$product = new WC_Product_Simple();
		$product->set_name( 'ARWC Integration Product' );
		$product->set_status( 'publish' );
		$product->set_regular_price( '25' );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( 2 );
		$product_id = $product->save();

		$assert( $product_id > 0, 'A real WooCommerce product can be created in the disposable store.' );

		$GLOBALS['product'] = $product;
		$module             = new ARWC_Product();

		ob_start();
		$module->render_low_stock_message();
		$default_output = trim( ob_get_clean() );

		$assert( '' === $default_output, 'Low-stock output is absent until explicitly enabled.' );

		add_filter( 'arwc_enable_low_stock_message', '__return_true' );

		ob_start();
		$module->render_low_stock_message();
		$enabled_output = wp_strip_all_tags( ob_get_clean() );

		remove_filter( 'arwc_enable_low_stock_message', '__return_true' );

		$assert(
			false !== strpos( $enabled_output, 'Only 2 items left in stock.' ),
			'Low-stock messaging renders from a real WC_Product when enabled.'
		);

		wp_delete_post( $product_id, true );

		WP_CLI::success( 'WooCommerce runtime integration smoke tests passed.' );
	}
);
