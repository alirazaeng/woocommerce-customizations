<?php
/**
 * My Account customization examples.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account customization module.
 */
class ARWC_Account {

	/**
	 * Endpoint slug.
	 */
	const ENDPOINT = 'project-notes';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_endpoint' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_menu_item' ) );
		add_action( 'woocommerce_account_' . self::ENDPOINT . '_endpoint', array( $this, 'render_endpoint' ) );
	}

	/**
	 * Register the rewrite endpoint.
	 *
	 * @return void
	 */
	public function register_endpoint() {
		add_rewrite_endpoint( self::ENDPOINT, EP_ROOT | EP_PAGES );
	}

	/**
	 * Add endpoint to WooCommerce account navigation when enabled.
	 *
	 * @param array $items Existing account menu items.
	 * @return array
	 */
	public function add_menu_item( $items ) {
		if ( ! ARWC_Plugin::feature_enabled( 'account_endpoint' ) ) {
			return $items;
		}

		$logout = isset( $items['customer-logout'] ) ? $items['customer-logout'] : null;
		unset( $items['customer-logout'] );

		$items[ self::ENDPOINT ] = __( 'Project Notes', 'ali-woocommerce-customizations' );

		if ( null !== $logout ) {
			$items['customer-logout'] = $logout;
		}

		return $items;
	}

	/**
	 * Render account endpoint content.
	 *
	 * @return void
	 */
	public function render_endpoint() {
		if ( ! ARWC_Plugin::feature_enabled( 'account_endpoint' ) || ! is_user_logged_in() ) {
			return;
		}

		echo '<h2>' . esc_html__( 'Project Notes', 'ali-woocommerce-customizations' ) . '</h2>';
		echo '<p>';
		echo esc_html__(
			'This example endpoint demonstrates how to extend My Account without editing WooCommerce account templates.',
			'ali-woocommerce-customizations'
		);
		echo '</p>';
	}
}
