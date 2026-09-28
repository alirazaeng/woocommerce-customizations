# Security Practices

## Sanitize input

Choose a sanitizer that matches the data.

Examples:

- `sanitize_text_field()` for short plain text
- `sanitize_email()` for email-like values
- `absint()` for positive integer IDs
- `wc_clean()` for recursively cleaning WooCommerce-style arrays when appropriate
- `wp_unslash()` before sanitizing raw WordPress request data when needed

## Escape output

Escape at output time for the destination context:

- `esc_html()`
- `esc_attr()`
- `esc_url()`
- `wp_kses_post()` only when a controlled subset of HTML is intentionally allowed

## Nonces

For custom actions that change state, use WordPress nonces:

- create with `wp_nonce_field()` or `wp_create_nonce()`
- verify with `wp_verify_nonce()` or the appropriate check helper

A nonce is not authorization. Pair it with capability or ownership checks.

## Capabilities and ownership

Administrative operations should check capabilities such as `manage_woocommerce` where appropriate.

Customer-facing order data must also verify that the current user owns the order, unless an authorized administrator is viewing it.

## Orders

Prefer `WC_Order` CRUD methods such as:

- `get_meta()`
- `update_meta_data()`
- `save()`

Do not write order metadata directly to WordPress tables when a WooCommerce CRUD API is available. This is especially important for High-Performance Order Storage compatibility.
