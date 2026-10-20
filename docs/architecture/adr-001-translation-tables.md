# ADR-001: Translation data model

**Status:** Proposed  
**Date:** 2026-03-29  
**Context:** Bilingual FR/EN with independent slugs and SEO per locale.

## Decision

Use **dedicated translation tables** per translatable entity (e.g. `products` + `product_translations`), not JSON columns on base tables.

## Rationale

- Independent FR/EN slugs and hreflang URLs  
- Clear missing-translation detection for admin dashboard  
- Strong indexing on `(locale, slug)` and `(locale, publication_status)`  
- Avoid mixing `spatie/laravel-translatable` with custom slug logic

## Consequences

- More migrations and Eloquent relationships  
- Filament must edit translations via relation managers or tabs  
- `TranslationService` centralises locale fallback rules (UI strings may fall back; marketing body requires explicit EN record to publish EN URL)

## Alternatives rejected

- Single table with `_en` suffix columns — poor scalability and SEO  
- JSON `translations` column — harder to query and index

## Gate

Confirmed at step 050 and approved with the schema at step 055.
