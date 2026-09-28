<?php
/**
 * Product customization examples.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

class ARWC_Product {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_single_product_summary', array( $this, 'render_low_stock_message' ), 21 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'render_catalog_badge' ), 9 );
	}

	/**
	 * Display a low-stock message using WooCommerce product APIs.
	 *
	 * @return void
	 */
	public function render_low_stock_message() {
		if ( ! ARWC_Plugin::feature_enabled( 'low_stock_message' ) ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product || ! $product->managing_stock() ) {
			return;
		}

		$quantity = $product->get_stock_quantity();

		if ( null === $quantity || $quantity <= 0 ) {
			return;
		}

		$threshold = (int) apply_filters( 'arwc_low_stock_threshold', 3, $product );

		if ( $quantity > $threshold ) {
			return;
		}

		printf(
			'<p class="arwc-low-stock">%s</p>',
			esc_html(
				sprintf(
					/* translators: %d: stock quantity. */
					_n( 'Only %d item left in stock.', 'Only %d items left in stock.', $quantity, 'ali-woocommerce-customizations' ),
					$quantity
				)
			)
		);
	}

	/**
	 * Add an opt-in catalog badge without editing WooCommerce templates.
	 *
	 * @return void
	 */
	public function render_catalog_badge() {
		if ( ! ARWC_Plugin::feature_enabled( 'catalog_badge' ) ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product || ! $product->is_on_sale() ) {
			return;
		}

		echo '<span class="arwc-product-badge">';
		echo esc_html__( 'Special Offer', 'ali-woocommerce-customizations' );
		echo '</span>';
	}
}
