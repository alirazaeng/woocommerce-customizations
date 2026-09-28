# Testing Strategy

This repository currently uses automated static checks plus a manual WooCommerce regression checklist.

Automated CI validates:

- Composer configuration
- WordPress Coding Standards
- PHP syntax

Runtime WooCommerce behavior still requires an actual WordPress + WooCommerce test environment.

Future additions can introduce integration tests around a disposable WooCommerce environment without storing production databases or customer data in this repository.
