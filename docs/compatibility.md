# Compatibility Guidance

WooCommerce stores are integration-heavy. A customization that works on a clean test site can conflict with a production extension stack.

## Check before deployment

- WooCommerce version
- WordPress version
- PHP version
- theme
- classic checkout vs Checkout Blocks
- payment gateways
- shipping plugins
- multilingual plugins
- currency switchers
- subscriptions/memberships
- product add-ons
- checkout field editors
- caching/CDN layer
- analytics and consent tools

## HPOS

This example plugin declares compatibility with High-Performance Order Storage because its order metadata example uses WooCommerce order CRUD objects.

That declaration is not a guarantee that every future contribution will be HPOS-safe. New order-related code should continue to avoid assumptions about WordPress post tables.

## Safe defaults

All behavior-changing features in the example plugin are disabled by default.

Integrators opt into individual features with filters such as:

```php
add_filter( 'arwc_enable_low_stock_message', '__return_true' );
```

This prevents activating the demonstration plugin from unexpectedly changing a live storefront.
