# Contributing

## Development workflow

1. Branch from `main`.
2. Make one focused customization change.
3. Run `composer install`.
4. Run `composer lint`.
5. Update documentation and compatibility notes.
6. Open a pull request.
7. Merge only after CI passes.

## Contribution requirements

Runtime customizations should:

- use public WordPress/WooCommerce APIs where practical
- be safe by default or clearly opt-in
- sanitize input and escape output
- document classic checkout vs Checkout Blocks applicability
- use WooCommerce CRUD objects for orders
- avoid hard-coded client IDs, domains, prices, or credentials
- include regression risks and testing notes

## Pull requests

Explain:

- business problem
- hook/API chosen
- why the chosen integration point is appropriate
- checkout/cart/account risks
- compatibility assumptions
- how the change was tested
