# Changelog

## [Unreleased]

### Added

- disposable WordPress + WooCommerce runtime integration workflow using wp-env
- runtime coverage for safe default feature flags and real `WC_Product` behavior
- real WooCommerce cart/session subtotal and minimum-order validation coverage
- classic checkout field registration and validation coverage
- explicit HPOS runtime validation
- real `WC_Order` creation plus sanitized metadata persistence/readback through WooCommerce CRUD
- runtime integration status badge

### Changed

- GitHub Actions checkout dependency updated to the current maintained major version
- testing documentation expanded with reproducible local wp-env commands

## [1.0.0] - 2026-09-30

### Added

- modular WooCommerce plugin bootstrap
- HPOS-compatible order metadata pattern
- product low-stock and catalog badge examples
- cart notice and minimum-order examples
- classic checkout field lifecycle
- customer/admin order metadata rendering
- My Account endpoint example
- conditional frontend asset loading
- safe-by-default feature flags
- security and compatibility documentation
- regression-testing checklist
- WordPress Coding Standards configuration
- GitHub Actions quality workflow

### Changed

- plugin version promoted from `0.1.0` to `1.0.0` for the first stable tagged release
