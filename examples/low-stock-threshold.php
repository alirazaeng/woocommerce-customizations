<?php
/**
 * Customize the low-stock threshold.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'arwc_enable_low_stock_message', '__return_true' );

/**
 * Example per-product threshold filter.
 *
 * @param int        $threshold Existing threshold.
 * @param WC_Product $product   Product object.
 * @return int
 */
function arwc_example_low_stock_threshold( $threshold, $product ) {
	unset( $threshold );

	if ( $product instanceof WC_Product && $product->is_type( 'variable' ) ) {
		return 2;
	}

	return 3;
}
add_filter( 'arwc_low_stock_threshold', 'arwc_example_low_stock_threshold', 10, 2 );
