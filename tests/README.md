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

Future integration coverage can extend this environment to cart/session behavior, checkout persistence, and HPOS-specific order flows.
