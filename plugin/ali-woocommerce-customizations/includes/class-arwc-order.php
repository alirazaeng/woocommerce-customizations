<?php
/**
 * Order customization examples.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

/**
 * Order customization module.
 */
class ARWC_Order {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'render_admin_delivery_note' ) );
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'render_customer_delivery_note' ) );
	}

	/**
	 * Display custom order data in WooCommerce admin.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function render_admin_delivery_note( $order ) {
		if ( ! ARWC_Plugin::feature_enabled( 'delivery_note' ) || ! $order instanceof WC_Order ) {
			return;
		}

		$value = $order->get_meta( ARWC_Checkout::META_KEY );

		if ( ! is_string( $value ) || '' === trim( $value ) ) {
			return;
		}

		echo '<p><strong>';
		echo esc_html__( 'Delivery note:', 'ali-woocommerce-customizations' );
		echo '</strong> ';
		echo esc_html( $value );
		echo '</p>';
	}

	/**
	 * Display the same value to the customer on their order details screen.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function render_customer_delivery_note( $order ) {
		if ( ! ARWC_Plugin::feature_enabled( 'delivery_note' ) || ! $order instanceof WC_Order ) {
			return;
		}

		if ( get_current_user_id() !== (int) $order->get_user_id() && ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$value = $order->get_meta( ARWC_Checkout::META_KEY );

		if ( ! is_string( $value ) || '' === trim( $value ) ) {
			return;
		}

		echo '<section class="woocommerce-order-details arwc-order-note">';
		echo '<h2>' . esc_html__( 'Delivery note', 'ali-woocommerce-customizations' ) . '</h2>';
		echo '<p>' . esc_html( $value ) . '</p>';
		echo '</section>';
	}
}
