<?php
/**
 * Example feature opt-ins.
 *
 * Copy only the filters you need into a custom plugin or child theme.
 * Do not enable every feature automatically on a production store.
 *
 * @package AliWooCommerceCustomizations
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'arwc_enable_low_stock_message', '__return_true' );
add_filter( 'arwc_enable_catalog_badge', '__return_true' );
add_filter( 'arwc_enable_cart_message', '__return_true' );

/*
 * Classic checkout only:
 *
 * add_filter( 'arwc_enable_delivery_note', '__return_true' );
 *
 * My Account endpoint:
 *
 * add_filter( 'arwc_enable_account_endpoint', '__return_true' );
 */
