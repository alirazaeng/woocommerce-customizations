# Security Policy

## Scope

This repository contains WordPress and WooCommerce customization examples. Some examples affect cart, checkout, customer accounts, and order data, so changes should be treated as production-sensitive.

## Never commit

- WordPress database credentials
- salts or authentication keys
- API tokens
- payment gateway credentials
- private keys
- customer exports
- production database dumps
- real order/customer IDs
- paid extension source code
- private client URLs when they reveal sensitive infrastructure

## Secure implementation rules

- sanitize incoming values
- escape output for the destination context
- use nonces for custom state-changing requests
- check capabilities for privileged operations
- use WooCommerce CRUD APIs for order data
- do not manipulate payment credentials or raw card data
- do not bypass WooCommerce validation or authentication
- avoid direct SQL when public APIs exist

## Reporting issues

Do not publish credentials, customer data, or sensitive exploitation details in a public issue. Use the maintainer's professional contact information for sensitive reports.
