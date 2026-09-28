# Hooks vs Template Overrides

WooCommerce offers several extension points. Choosing the least invasive one reduces update risk.

## Prefer hooks and filters when possible

Hooks and filters are usually the first choice when you need to:

- add content before or after existing UI
- alter labels or values
- validate checkout input
- save order metadata
- modify account navigation
- add notices
- conditionally enqueue assets

Advantages:

- less duplicated WooCommerce markup
- fewer template-version maintenance problems
- smaller upgrade surface
- clearer intent

## Use template overrides deliberately

A template override can be appropriate when the required markup structure cannot be achieved through public hooks.

If you override a WooCommerce template:

1. copy only the exact template required
2. record the source WooCommerce version
3. keep the override minimal
4. watch WooCommerce template status after upgrades
5. compare upstream changes during maintenance

## Avoid theme edits for business logic

Business rules such as validation, order metadata, and account behavior belong in a plugin or another maintainable application layer rather than a parent theme.

Themes control presentation. Store behavior should survive a theme change whenever practical.
