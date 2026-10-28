# French and English management

| | |
|--|--|
| **Step** | 111 — Test French and English management |
| **Parent** | [translation-strategy.md](../database/translation-strategy.md) · [local-sample-news.md](local-sample-news.md) |
| **Next** | 112 — Review the model against real data |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Slugs, publication, and missing translations confirmed |

French remains the default (no `/fr` prefix). English lives under `/en`. Marketing body does **not** fall back. `missingLocales()` on translatable models lists absent `fr` / `en` rows.

## 1. Rules confirmed

| Rule | Result |
|------|--------|
| Independent FR/EN slugs | Posts, equipment, careers, centres, resources, team, partners, categories |
| Frozen slugs | Pages (sitemap), products (fragment, both locales), expertises |
| Unique `(locale, slug)` | Enforced in the matching `Translate*` action |
| Draft + both locales | Still not public |
| Published + missing EN | FR URL may exist; EN URL, `isPubliclyVisibleIn('en')`, and hreflang `en` do not |
| Publish ≠ translate ≠ update | Communications writes FR; Translator writes EN |

## 2. PHPUnit

```powershell
php artisan test --filter=LocaleManagementTest
php artisan test
```

## 3. Verification (2026-10-22)

- `vendor/bin/pint --dirty` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=LocaleManagementTest` — 6 passed
- Browser: `/admin/posts/2/edit` — tab **Français** slug `reconstruction-du-site-corporate`; tab **English** slug `corporate-website-rebuild`; status `draft`

## 4. Out of this step

| Item | Step |
|------|------|
| Model vs representative data | 112 |
| Public language switch on CMS templates | 147 → [public-language-switch.md](public-language-switch.md) |
| Missing-translation dashboard widget | later admin polish |

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | French and English management tested (step 111 executed) | 2026-10-22 |
