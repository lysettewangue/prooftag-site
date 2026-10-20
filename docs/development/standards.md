# Development standards

Naming and Git rules for steps 027–028. Quality tools are installed at step 038.

## PHP and Laravel

- PHP 8.4 strict types where practical  
- Laravel Pint for formatting (`./vendor/bin/pint`)  
- Larastan level 5 minimum, increase over time  
- Business logic in application actions — not in controllers or Filament resources  
- Translation keys: `lang/fr`, `lang/en`; content identifiers stable across locales  
- Media filenames: lowercase, hyphenated, no spaces  
- Branches: `main`, `develop`, `feature/*`, `fix/*`  
- Commits: `feat:`, `fix:`, `docs:`, `chore:`

## Documentation

- Update `docs/` when schema or workflow changes  
- ADRs in [architecture/adr/](architecture/adr/README.md)  
