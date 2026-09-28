# WooCommerce Customizations

Production-oriented WooCommerce customization patterns covering products, cart, checkout, customer accounts, orders, and conditional frontend assets.

[![WooCommerce Code Quality](https://github.com/alirazaeng/woocommerce-customizations/actions/workflows/quality.yml/badge.svg)](https://github.com/alirazaeng/woocommerce-customizations/actions/workflows/quality.yml)

This repository is designed as a maintainable WooCommerce engineering reference—not a collection of snippets that should be pasted blindly into production.

## What this project demonstrates

- modular WooCommerce plugin architecture
- public WooCommerce hooks and filters
- product and catalog customizations
- cart validation and notices
- classic checkout field lifecycle
- sanitized order metadata persistence
- HPOS-aware WooCommerce order CRUD
- customer/admin order metadata display
- My Account endpoint integration
- conditional frontend assets
- safe-by-default feature flags
- WordPress Coding Standards and CI
- compatibility and regression-testing discipline

## Safe by default

Activating the demonstration plugin does **not** enable the behavior-changing examples automatically.

Individual features are enabled through filters:

```php
add_filter( 'arwc_enable_low_stock_message', '__return_true' );
add_filter( 'arwc_enable_catalog_badge', '__return_true' );
add_filter( 'arwc_enable_cart_message', '__return_true' );
```

See [examples/enable-features.php](examples/enable-features.php).

That design prevents a portfolio/demo plugin from unexpectedly changing a production storefront just because it was activated.

## Architecture

```text
woocommerce-customizations/
├── .github/
│   ├── workflows/quality.yml
│   ├── ISSUE_TEMPLATE/
│   └── pull_request_template.md
├── docs/
│   ├── cart-and-session-safety.md
│   ├── checkout-customization.md
│   ├── compatibility.md
│   ├── hooks-vs-template-overrides.md
│   ├── security.md
│   └── testing-checklist.md
├── examples/
│   ├── enable-features.php
│   ├── low-stock-threshold.php
│   └── minimum-order.php
├── plugin/
│   └── ali-woocommerce-customizations/
│       ├── ali-woocommerce-customizations.php
│       ├── includes/
│       │   ├── class-account.php
│       │   ├── class-assets.php
│       │   ├── class-cart.php
│       │   ├── class-checkout.php
│       │   ├── class-order.php
│       │   ├── class-plugin.php
│       │   └── class-product.php
│       └── assets/
│           ├── css/frontend.css
│           └── js/frontend.js
└── tests/
    └── README.md
```

## Customization areas

| Area | Demonstrated patterns |
| --- | --- |
| Products | Low-stock messaging, sale/catalog badge |
| Cart | Minimum-order validation, contextual notices |
| Checkout | Custom field, validation, sanitization, persistence |
| Orders | Admin/customer metadata display through `WC_Order` |
| My Account | Custom endpoint and navigation item |
| Assets | Conditional WooCommerce-only loading |

## Product example

The low-stock module reads stock through the `WC_Product` API rather than querying database tables directly.

The threshold is configurable:

```php
add_filter( 'arwc_enable_low_stock_message', '__return_true' );

function arwc_my_threshold( $threshold, $product ) {
    return 3;
}
add_filter( 'arwc_low_stock_threshold', 'arwc_my_threshold', 10, 2 );
```

## Cart example

The minimum-order example intentionally defines what it measures: **merchandise subtotal before shipping and taxes**.

```php
add_filter( 'arwc_enable_minimum_order', '__return_true' );

function arwc_store_minimum() {
    return 75.0;
}
add_filter( 'arwc_minimum_order_amount', 'arwc_store_minimum' );
```

A real client implementation should map this rule to the store's actual published policy rather than copying a number from an example.

## Checkout → order data lifecycle

The delivery-note example demonstrates the full lifecycle:

```text
render field
→ validate
→ sanitize
→ save with WC_Order CRUD
→ retrieve with WC_Order CRUD
→ escape for admin/customer output
```

The implementation uses:

- `woocommerce_checkout_fields`
- `woocommerce_after_checkout_validation`
- `woocommerce_checkout_create_order`

These are **classic checkout** patterns. Checkout Blocks require their own extensibility approach.

See [Checkout customization](docs/checkout-customization.md).

## HPOS

The plugin declares High-Performance Order Storage compatibility because order metadata is handled through WooCommerce CRUD methods instead of direct post/postmeta assumptions.

That does not mean every possible future contribution is automatically HPOS-safe. Order-related additions should continue to use supported WooCommerce APIs.

## Hooks vs template overrides

Prefer hooks and filters when they can solve the requirement cleanly.

Template overrides are valid when markup structure genuinely needs to change, but they create an upgrade-maintenance responsibility.

See [Hooks vs template overrides](docs/hooks-vs-template-overrides.md).

## Security

The project emphasizes:

- input sanitization
- output escaping
- capability and ownership checks
- nonces for custom state-changing requests
- WooCommerce CRUD for order data
- no payment-data handling
- no secrets or customer data in source control

See [Security practices](docs/security.md) and [SECURITY.md](SECURITY.md).

## Regression testing

A WooCommerce customization is not finished when the PHP has no syntax errors.

Depending on the feature, validate:

- simple and variable products
- add to cart
- cart quantity/removal
- coupons
- shipping and tax
- guest checkout
- account checkout
- payment handoff
- order creation
- order confirmation
- My Account
- mobile and desktop
- PHP/browser logs

Use the full [WooCommerce regression checklist](docs/testing-checklist.md).

## Code quality

GitHub Actions runs:

1. Composer validation
2. dependency installation
3. WordPress Coding Standards / PHPCS
4. PHP syntax checks across plugin and examples

Run locally:

```bash
composer install
composer lint
find plugin examples -name "*.php" -print0 | xargs -0 -n1 php -l
```

## Important compatibility notes

WooCommerce stores vary significantly by:

- classic checkout vs Checkout Blocks
- theme
- gateways
- shipping plugins
- multilingual/currency tools
- subscriptions/memberships
- product add-ons
- caching and CDN setup

Always validate against the actual store stack.

See [Compatibility guidance](docs/compatibility.md).

## What is intentionally not included

This repository does not contain:

- client production code
- payment gateway bypasses
- API keys or credentials
- customer/order exports
- premium extension source
- database dumps
- hard-coded private client domains
- destructive SQL cleanup scripts

## Author

**Engineer Ali Raza**  
WordPress & WooCommerce Developer · Web Performance Specialist · Frontend Developer

- Portfolio: https://engineeraliraza.site
- Upwork: https://www.upwork.com/freelancers/engineeraliraza
- GitHub: https://github.com/alirazaeng

## License

MIT. See [LICENSE](LICENSE).
