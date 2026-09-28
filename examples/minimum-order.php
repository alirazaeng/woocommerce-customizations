<?php
/**
 * Enable and configure the minimum-order example.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'arwc_enable_minimum_order', '__return_true' );

/**
 * Change the minimum merchandise subtotal.
 *
 * @return float
 */
function arwc_example_minimum_order_amount() {
	return 75.0;
}
add_filter( 'arwc_minimum_order_amount', 'arwc_example_minimum_order_amount' );
