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

Checkout Blocks use a different extensibility model. Do not assume a PHP field filter designed for the classic shortcode checkout will automatically appear or validate in block checkout.

A production project should choose the appropriate Store API / Blocks extensibility approach for its target WooCommerce version and requirement.

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
