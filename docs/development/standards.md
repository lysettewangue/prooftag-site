# Development standards

PHP and Laravel naming. Git rules: [source-control.md](source-control.md) (step 028). Quality tools: step 038.

## PHP and Laravel

- PHP 8.4, strict types where practical
- Laravel Pint (`./vendor/bin/pint`)
- Larastan level 5 minimum, increase over time
- Business logic in application actions — not in controllers or Filament resources
- UI strings: `lang/fr`, `lang/en`; content identifiers stable across locales
- Media filenames: lowercase, hyphenated, no spaces

## Documentation

- Update `docs/` when schema or workflow changes
- ADRs in [architecture/adr/](../architecture/adr/README.md)
