# Cart and Session Safety

WooCommerce cart state is customer-specific.

## Be careful with

- full-page caching
- session cookies
- cart fragments
- AJAX add-to-cart
- geolocation
- currency switchers
- coupons
- shipping calculations
- tax display
- custom cart item data

## Minimum-order rules

A minimum-order rule must define what amount it means.

Possible bases include:

- merchandise subtotal before discounts
- merchandise subtotal after discounts
- total before shipping
- final total
- taxable subtotal

The example in this repository intentionally documents its basis: merchandise subtotal before shipping and taxes.

Do not copy a threshold rule without matching the client's actual business policy.

## Regression tests

After changing cart logic, verify:

- simple products
- variable products
- AJAX and non-AJAX add to cart
- quantity updates
- remove item
- coupon add/remove
- shipping recalculation
- taxes
- guest session
- logged-in session
- cart persistence
- mini-cart/header cart if present
