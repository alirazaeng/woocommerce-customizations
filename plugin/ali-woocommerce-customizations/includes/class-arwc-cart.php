<?php
/**
 * Cart customization examples.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cart customization module.
 */
class ARWC_Cart {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_check_cart_items', array( $this, 'validate_minimum_order' ) );
		add_action( 'woocommerce_before_cart', array( $this, 'render_cart_message' ) );
	}

	/**
	 * Enforce an opt-in minimum merchandise subtotal.
	 *
	 * This example intentionally uses the cart subtotal before shipping and
	 * taxes. Real projects should document the exact business rule first.
	 *
	 * @return void
	 */
	public function validate_minimum_order() {
		if ( ! ARWC_Plugin::feature_enabled( 'minimum_order' ) || ! WC()->cart ) {
			return;
		}

		$minimum = (float) apply_filters( 'arwc_minimum_order_amount', 50.0 );

		if ( $minimum <= 0 || WC()->cart->get_subtotal() >= $minimum ) {
			return;
		}

		wc_add_notice(
			sprintf(
				/* translators: %s: formatted money amount. */
				esc_html__( 'A minimum merchandise subtotal of %s is required to check out.', 'ali-woocommerce-customizations' ),
				wp_kses_post( wc_price( $minimum ) )
			),
			'error'
		);
	}

	/**
	 * Render a configurable cart message.
	 *
	 * @return void
	 */
	public function render_cart_message() {
		if ( ! ARWC_Plugin::feature_enabled( 'cart_message' ) ) {
			return;
		}

		$message = (string) apply_filters(
			'arwc_cart_message',
			__( 'Review quantities, shipping details, and totals before continuing to checkout.', 'ali-woocommerce-customizations' )
		);

		if ( '' === trim( $message ) ) {
			return;
		}

		wc_print_notice( esc_html( $message ), 'notice' );
	}
}
