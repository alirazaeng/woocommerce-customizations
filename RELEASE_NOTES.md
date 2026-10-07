# WooCommerce Customizations v1.1.0

Minor release focused on modern WooCommerce checkout compatibility and stronger runtime proof.

## Highlights

- Checkout Blocks delivery-note integration through WooCommerce's Additional Checkout Fields API
- separate classic checkout and Checkout Blocks implementations
- server-side sanitization and validation for the Blocks field
- explicit 180-character delivery-note limit
- real disposable WordPress + WooCommerce runtime integration testing
- real cart/session subtotal and minimum-order validation coverage
- explicit HPOS runtime validation
- real `WC_Order` creation plus metadata persistence/readback through WooCommerce CRUD
- maintained safe-by-default feature flags

## Checkout compatibility

The delivery-note example now supports both checkout architectures:

- **Classic checkout:** existing WooCommerce checkout hooks
- **Checkout Blocks:** Additional Checkout Fields API when available in the running WooCommerce version

The Blocks implementation feature-detects the API so older WooCommerce versions do not fatal.

## Validation

The release branch is validated by GitHub Actions for:

- Composer configuration
- WordPress Coding Standards / PHPCS
- PHP syntax
- disposable WordPress + WooCommerce runtime execution
- Checkout Blocks field registration against WooCommerce's real `CheckoutFields` service
- HPOS order creation and metadata persistence

## Compatibility

- WordPress 6.4+
- PHP 8.0+
- WooCommerce 8.0+
- Checkout Blocks field support when the Additional Checkout Fields API is available
- HPOS compatibility declared for the included order-data patterns

## Safety

Behavior-changing examples remain disabled until explicitly enabled through filters. Activating the plugin alone does not intentionally change a production store.

## License

MIT.
