<?php
/**
 * Classic checkout customization examples.
 *
 * These hooks apply to the classic shortcode checkout. Checkout Blocks use a
 * different extensibility model and are documented separately.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

class ARWC_Checkout {

	/**
	 * Order meta key used by the example.
	 */
	const META_KEY = '_arwc_delivery_note';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_delivery_note_field' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_delivery_note' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order', array( $this, 'save_delivery_note' ), 10, 2 );
	}

	/**
	 * Add an optional classic-checkout field.
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function add_delivery_note_field( $fields ) {
		if ( ! ARWC_Plugin::feature_enabled( 'delivery_note' ) ) {
			return $fields;
		}

		$fields['order']['arwc_delivery_note'] = array(
			'type'        => 'text',
			'label'       => __( 'Delivery note', 'ali-woocommerce-customizations' ),
			'placeholder' => __( 'Optional delivery instructions', 'ali-woocommerce-customizations' ),
			'required'    => false,
			'priority'    => 25,
			'class'       => array( 'form-row-wide' ),
		);

		return $fields;
	}

	/**
	 * Validate custom input after WooCommerce core validation.
	 *
	 * @param array    $data   Sanitized checkout data.
	 * @param WP_Error $errors Validation error collection.
	 * @return void
	 */
	public function validate_delivery_note( $data, $errors ) {
		if ( ! ARWC_Plugin::feature_enabled( 'delivery_note' ) ) {
			return;
		}

		$value = isset( $data['arwc_delivery_note'] ) ? sanitize_text_field( $data['arwc_delivery_note'] ) : '';

		if ( strlen( $value ) > 180 ) {
			$errors->add(
				'arwc_delivery_note_too_long',
				__( 'Delivery note must be 180 characters or fewer.', 'ali-woocommerce-customizations' )
			);
		}
	}

	/**
	 * Persist the field through the WooCommerce order CRUD object.
	 *
	 * @param WC_Order $order Order being created.
	 * @param array    $data  Checkout data.
	 * @return void
	 */
	public function save_delivery_note( $order, $data ) {
		if ( ! ARWC_Plugin::feature_enabled( 'delivery_note' ) || ! $order instanceof WC_Order ) {
			return;
		}

		$value = isset( $data['arwc_delivery_note'] ) ? sanitize_text_field( $data['arwc_delivery_note'] ) : '';

		if ( '' === $value ) {
			return;
		}

		$order->update_meta_data( self::META_KEY, $value );
	}
}
