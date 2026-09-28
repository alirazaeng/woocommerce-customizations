# WooCommerce Regression Testing Checklist

Use this after any customization that touches products, cart, checkout, accounts, sessions, or orders.

## Product

- [ ] simple product
- [ ] variable product
- [ ] variation selection
- [ ] stock display
- [ ] sale state
- [ ] add to cart
- [ ] out-of-stock behavior

## Cart

- [ ] quantity update
- [ ] remove item
- [ ] AJAX cart behavior where used
- [ ] mini-cart/header cart
- [ ] coupon apply/remove
- [ ] subtotal
- [ ] shipping calculation
- [ ] tax calculation

## Checkout

- [ ] guest checkout
- [ ] logged-in checkout
- [ ] required-field validation
- [ ] optional custom fields
- [ ] shipping methods
- [ ] tax
- [ ] coupon state
- [ ] payment gateway handoff
- [ ] failed payment return
- [ ] successful order creation
- [ ] order confirmation

## Account and orders

- [ ] login/logout
- [ ] password reset
- [ ] account navigation
- [ ] custom endpoint
- [ ] order list
- [ ] order details
- [ ] ownership/privacy checks

## Technical

- [ ] mobile
- [ ] desktop
- [ ] browser console
- [ ] PHP error log
- [ ] WooCommerce logs where relevant
- [ ] page-cache exclusions
- [ ] object cache
- [ ] REST/AJAX requests
- [ ] analytics/consent behavior
