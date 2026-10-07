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
		$assert( class_exists( 'ARWC_Cart' ), 'Cart customization module loaded.' );
		$assert( class_exists( 'ARWC_Checkout' ), 'Checkout customization module loaded.' );
		$assert( class_exists( 'ARWC_Checkout_Blocks' ), 'Checkout Blocks customization module loaded.' );
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
		$product_module     = new ARWC_Product();

		ob_start();
		$product_module->render_low_stock_message();
		$default_output = trim( ob_get_clean() );

		$assert( '' === $default_output, 'Low-stock output is absent until explicitly enabled.' );

		add_filter( 'arwc_enable_low_stock_message', '__return_true' );

		ob_start();
		$product_module->render_low_stock_message();
		$enabled_output = wp_strip_all_tags( ob_get_clean() );

		remove_filter( 'arwc_enable_low_stock_message', '__return_true' );

		$assert(
			false !== strpos( $enabled_output, 'Only 2 items left in stock.' ),
			'Low-stock messaging renders from a real WC_Product when enabled.'
		);

		/*
		 * Cart/session integration.
		 *
		 * wp-env runs this command after WordPress and WooCommerce have booted,
		 * so loading the cart here exercises WooCommerce's actual cart/session
		 * infrastructure rather than a hand-written mock.
		 */
		wc_load_cart();
		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $product_id, 1 );
		WC()->cart->calculate_totals();

		$assert(
			25.0 === (float) WC()->cart->get_subtotal(),
			'Real WooCommerce cart subtotal is available to customization logic.'
		);

		$enable_minimum_order = static function () {
			return true;
		};
		$minimum_amount = static function () {
			return 50.0;
		};

		add_filter( 'arwc_enable_minimum_order', $enable_minimum_order );
		add_filter( 'arwc_minimum_order_amount', $minimum_amount );

		wc_clear_notices();
		( new ARWC_Cart() )->validate_minimum_order();

		$error_notices = wc_get_notices( 'error' );

		$assert(
			! empty( $error_notices )
			&& false !== strpos( wp_strip_all_tags( $error_notices[0]['notice'] ), 'minimum merchandise subtotal' ),
			'Minimum-order validation adds a real WooCommerce error notice below the threshold.'
		);

		remove_filter( 'arwc_enable_minimum_order', $enable_minimum_order );
		remove_filter( 'arwc_minimum_order_amount', $minimum_amount );
		wc_clear_notices();

		/*
		 * Classic checkout field lifecycle.
		 */
		$enable_delivery_note = static function () {
			return true;
		};

		add_filter( 'arwc_enable_delivery_note', $enable_delivery_note );

		$checkout = new ARWC_Checkout();
		$fields   = $checkout->add_delivery_note_field(
			array(
				'order' => array(),
			)
		);

		$assert(
			isset( $fields['order']['arwc_delivery_note'] ),
			'Delivery-note field is added only after explicit opt-in.'
		);

		$errors = new WP_Error();
		$checkout->validate_delivery_note(
			array(
				'arwc_delivery_note' => str_repeat( 'x', 181 ),
			),
			$errors
		);

		$assert(
			$errors->has_errors()
			&& in_array( 'arwc_delivery_note_too_long', $errors->get_error_codes(), true ),
			'Classic checkout validation rejects delivery notes longer than 180 characters.'
		);

		/*
		 * Checkout Blocks Additional Checkout Fields API.
		 */
		$assert(
			function_exists( 'woocommerce_register_additional_checkout_field' ),
			'WooCommerce Additional Checkout Fields API is available.'
		);

		$blocks_checkout = new ARWC_Checkout_Blocks();
		$blocks_checkout->register_delivery_note_field();

		$checkout_fields = \Automattic\WooCommerce\Blocks\Package::container()->get(
			\Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::class
		);
		$additional_fields = $checkout_fields->get_additional_fields();

		$assert(
			isset( $additional_fields[ ARWC_Checkout_Blocks::FIELD_ID ] )
			&& 'order' === $additional_fields[ ARWC_Checkout_Blocks::FIELD_ID ]['location']
			&& false === $additional_fields[ ARWC_Checkout_Blocks::FIELD_ID ]['required'],
			'Delivery note registers as an optional Checkout Blocks order field.'
		);

		$assert(
			'Leave at the side door.' === $blocks_checkout->sanitize_delivery_note( '  Leave at the side door.  ' ),
			'Checkout Blocks delivery note sanitization uses WordPress text sanitization.'
		);

		$blocks_error = $blocks_checkout->validate_delivery_note( str_repeat( 'x', 181 ) );

		$assert(
			$blocks_error instanceof WP_Error
			&& in_array( 'arwc_delivery_note_too_long', $blocks_error->get_error_codes(), true ),
			'Checkout Blocks validation rejects delivery notes longer than 180 characters.'
		);

		/*
		 * HPOS + order CRUD.
		 *
		 * WooCommerce enables HPOS for fresh stores. Verify the active data
		 * store before asserting persistence through WC_Order APIs.
		 */
		$hpos_option = 'woocommerce_custom_orders_table_enabled';
		$original_hpos_value = get_option( $hpos_option, 'no' );

		update_option( $hpos_option, 'yes' );

		$hpos_enabled = class_exists( '\\Automattic\\WooCommerce\\Utilities\\OrderUtil' )
			&& \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();

		$assert( $hpos_enabled, 'High-Performance Order Storage is explicitly enabled for the runtime test.' );

		$order = wc_create_order();

		$assert( $order instanceof WC_Order, 'A real WooCommerce order can be created under HPOS.' );

		$checkout->save_delivery_note(
			$order,
			array(
				'arwc_delivery_note' => '  Leave at the side door.  ',
			)
		);
		$order->save();

		$order_id     = $order->get_id();
		$loaded_order = wc_get_order( $order_id );

		$assert(
			$loaded_order instanceof WC_Order
			&& 'Leave at the side door.' === $loaded_order->get_meta( ARWC_Checkout::META_KEY ),
			'Sanitized checkout metadata persists and reads back through WC_Order CRUD.'
		);

		remove_filter( 'arwc_enable_delivery_note', $enable_delivery_note );

		if ( $loaded_order instanceof WC_Order ) {
			$loaded_order->delete( true );
		}

		update_option( $hpos_option, $original_hpos_value );

		WC()->cart->empty_cart();
		wp_delete_post( $product_id, true );

		WP_CLI::success( 'WooCommerce runtime integration tests passed.' );
	}
);
