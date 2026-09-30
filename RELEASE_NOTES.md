# WooCommerce Customizations v1.0.0

First stable release of the production-oriented WooCommerce customization reference plugin.

## Included patterns

- modular WooCommerce bootstrap
- product and catalog hooks
- minimum-order/cart validation examples
- classic checkout custom-field lifecycle
- sanitized order metadata persistence
- HPOS-aware WooCommerce CRUD usage
- customer/admin order metadata display
- My Account endpoint integration
- conditional frontend asset loading

## Safe by default

Behavior-changing examples remain disabled until explicitly enabled through filters. Activating the plugin alone should not unexpectedly alter a production store.

## Compatibility

- WordPress 6.4+
- PHP 8.0+
- WooCommerce 8.0+
- HPOS compatibility declared for the included order-data patterns

Classic checkout examples are documented as such; Checkout Blocks require their own extensibility APIs.

## Validation

The repository CI validates Composer configuration, WordPress Coding Standards, and PHP syntax.

## License

MIT.
