# Testing Strategy

This repository uses two complementary layers of automated validation.

## Static quality checks

The existing quality workflow validates:

- Composer configuration
- WordPress Coding Standards
- PHP syntax

## Runtime integration smoke test

A separate GitHub Actions workflow boots a disposable WordPress environment with:

- the current WordPress production release
- PHP 8.2
- WooCommerce 11.1.2
- this repository's plugin

The runtime test verifies that:

- WooCommerce is active
- the plugin boots successfully
- optional behavior remains disabled by default
- a real WooCommerce product can be created
- the low-stock customization remains silent by default
- the low-stock message renders correctly when explicitly enabled
- a real WooCommerce cart/session calculates the expected subtotal
- minimum-order validation writes a WooCommerce error notice
- classic checkout field registration and validation work
- the suite explicitly enables HPOS before order-persistence assertions
- a real order is created under HPOS
- sanitized checkout metadata persists and reads back through `WC_Order` CRUD

No production database, customer records, payment credentials, or private client data are used.

The environment is destroyed after every CI run.

## Local execution

Docker is required.

```bash
npm install --global @wordpress/env@11.16.0
wp-env start --update
wp-env run cli wp arwc-test
wp-env destroy
```

Future integration coverage can add Checkout Blocks extensibility and selected gateway-free end-to-end flows without introducing private store data.
