# Public footer

| Item | Value |
| --- | --- |
| **Step** | 126 — Implement the footer |
| **Parent** | [125 — Implement navigation](public-navigation.md) |
| **Next** | [127 — Implement shared components](public-components.md) |
| **Status** | COMPLETED |

## Implementation

The shared footer is rendered by `resources/views/components/public/site-footer.blade.php`. Its display model is assembled by `App\Support\PublicFooter` and injected through the view composer registered in `AppServiceProvider`.

The footer reads the singleton `SiteSetting` record instead of duplicating corporate data in the view. It exposes:

- the legal name, brand, translated tagline, building, postal address, telephone, and e-mail;
- an Explore column derived from the frozen public navigation registry;
- an Information column with About, Contact, Legal notice, and Privacy links;
- social links only when a non-empty, valid HTTP(S) URL exists in global settings;
- the current legal name and year in the copyright line.

## Localisation

French keeps the canonical address values stored in global settings. English translates the frozen country and postal-box display (`P.O. Box 4601 Douala, Cameroon`) while keeping the same underlying organisation record. Labels and legal slugs come from the active locale.

## Resilience and accessibility

- The application still renders before `site_settings` exists or before its singleton row is seeded.
- Contact information uses semantic `address`, `tel:`, and `mailto:` links.
- Footer navigation groups have accessible names and links preserve the global keyboard focus treatment.
- The layout changes from one column to two and then three columns without horizontal overflow.
- No placeholder social network is displayed.

## Verification

- `PublicFooterTest` verifies database binding, bilingual address display, conditional social links, and the unseeded fallback.
- The public layout, navigation, localisation, and design-token regression tests remain green.
- The production Vite build completes successfully.

## Acceptance

| Role | Decision | Date |
| --- | --- | --- |
| Product owner | Shared bilingual footer implemented from global settings; shared-components step 127 unlocked | 2026-10-27 |
