# ADR-001: Translation data model

**Status:** Accepted  
**Date:** 2026-10-21 (confirmed at step 050; approved at 055)  
**Context:** Bilingual FR/EN with independent slugs and SEO per locale (product slugs are the exception: same fragment in both locales).

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

Confirmed at step 050 ([translation-strategy.md](../database/translation-strategy.md)). Approved with the schema at step 055 ([design-review.md](../database/design-review.md)). Media alt text is the documented Spatie `custom_properties` exception.
