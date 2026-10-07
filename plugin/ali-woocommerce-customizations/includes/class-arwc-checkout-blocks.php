<?php
/**
 * Checkout Blocks customization examples.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

/**
 * Checkout Blocks customization module.
 */
class ARWC_Checkout_Blocks {

	/**
	 * Additional Checkout Fields API identifier.
	 */
	const FIELD_ID = 'arwc/delivery-note';

	/**
	 * Register hooks.
	 *
	 * Additional checkout fields must be registered on woocommerce_init or
	 * later so WooCommerce Blocks services and translations are available.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_init', array( $this, 'register_delivery_note_field' ) );
	}

	/**
	 * Register an optional order-level delivery note for Checkout Blocks.
	 *
	 * The Additional Checkout Fields API is available in WooCommerce 8.9+.
	 * Older WooCommerce versions keep the classic checkout implementation
	 * available without triggering fatal errors.
	 *
	 * @return void
	 */
	public function register_delivery_note_field() {
		if (
			! ARWC_Plugin::feature_enabled( 'delivery_note' )
			|| ! function_exists( 'woocommerce_register_additional_checkout_field' )
		) {
			return;
		}

		woocommerce_register_additional_checkout_field(
			array(
				'id'                => self::FIELD_ID,
				'label'             => __( 'Delivery note', 'ali-woocommerce-customizations' ),
				'optionalLabel'     => __( 'Delivery note (optional)', 'ali-woocommerce-customizations' ),
				'location'          => 'order',
				'type'              => 'text',
				'required'          => false,
				'attributes'        => array(
					'maxLength' => 180,
				),
				'sanitize_callback' => array( $this, 'sanitize_delivery_note' ),
				'validate_callback' => array( $this, 'validate_delivery_note' ),
			)
		);
	}

	/**
	 * Sanitize a Checkout Blocks delivery note.
	 *
	 * @param mixed $value Submitted field value.
	 * @return string
	 */
	public function sanitize_delivery_note( $value ) {
		if ( ! is_string( $value ) ) {
			return '';
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Validate a Checkout Blocks delivery note.
	 *
	 * @param mixed $value Sanitized field value.
	 * @return WP_Error|void
	 */
	public function validate_delivery_note( $value ) {
		if ( ! is_string( $value ) ) {
			return new WP_Error(
				'arwc_delivery_note_invalid',
				__( 'Delivery note must be text.', 'ali-woocommerce-customizations' )
			);
		}

		if ( strlen( $value ) > 180 ) {
			return new WP_Error(
				'arwc_delivery_note_too_long',
				__( 'Delivery note must be 180 characters or fewer.', 'ali-woocommerce-customizations' )
			);
		}
	}
}
