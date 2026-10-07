# Checkout Customization

WooCommerce checkout customization must start by identifying which checkout implementation the store uses.

## Classic checkout

The plugin's delivery-note example uses classic checkout hooks:

- `woocommerce_checkout_fields`
- `woocommerce_after_checkout_validation`
- `woocommerce_checkout_create_order`

The lifecycle is:

```text
render field
→ validate input
→ sanitize input
→ persist with WC_Order CRUD
→ display escaped value
```

## Checkout Blocks

The plugin registers the same opt-in delivery-note concept through WooCommerce's **Additional Checkout Fields API** when that API is available (WooCommerce 8.9+).

The Blocks implementation:

- registers on `woocommerce_init`
- feature-detects `woocommerce_register_additional_checkout_field()`
- uses an `order`-location text field
- limits input to 180 characters
- sanitizes with `sanitize_text_field()`
- validates server-side before checkout completes
- lets WooCommerce own Store API persistence and order/confirmation rendering

Enable the delivery note once for either checkout implementation:

```php
add_filter( 'arwc_enable_delivery_note', '__return_true' );
```

Classic checkout uses the existing PHP checkout hooks. Checkout Blocks use the Additional Checkout Fields API; the two implementations are intentionally separate because their extensibility models differ.

See the [WooCommerce Additional Checkout Fields documentation](https://developer.woocommerce.com/docs/block-development/extensible-blocks/cart-and-checkout-blocks/additional-checkout-fields/).

## Security rules

Never:

- trust raw request data
- store payment card details
- bypass WooCommerce checkout validation
- disable nonce/authentication checks for custom state-changing requests
- save arbitrary unsanitized metadata

## Business rules

Before adding a required checkout field, document:

- who needs the value
- whether it is truly required
- where it is stored
- whether it appears in emails/admin/account pages
- retention/privacy requirements
- behavior for guest checkout
- compatibility with payment and fulfillment integrations
