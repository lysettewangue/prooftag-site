# Static analysis (Larastan)

| | |
|--|--|
| **Step** | 174 — Run static analysis |
| **Parent** | [test-suite-168-173.md](test-suite-168-173.md) · [local-quality.md](local-quality.md) |
| **Next** | 175 — Run format checks |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **Clean** — level 5 on `app/`, 0 errors |

## 1. Command

```powershell
composer analyse
# or
vendor/bin/phpstan analyse --memory-limit=1G
```

Config: [phpstan.neon](../../phpstan.neon) — Larastan + Carbon extensions, paths `app/`, level **5**.

## 2. Issues resolved this step

| Area | Fix |
|------|-----|
| `Career::$closes_at` | `@property Carbon\|null` so `lte()` / `translatedFormat()` type-check |
| `CentreIndexQuery` | Markers from `$paginator->items()` (contract has no `getCollection()`) |
| `SitemapQuery::pushModel` | `lastmod` as `string\|null` via `DateTimeInterface` |
| `ContentCache` | Drop redundant `array_values` on `list<int>`; PHPDoc `callable(): (?int)` |
| `LucideIcon::names` | Return curated list without redundant `array_values` |
| `OpenGraph` / `SeoMetaResolver` | Fallback image URL is always `string` |

No baselines, no `@phpstan-ignore`.

## 3. Verification (2026-10-29)

- `composer analyse` — **[OK] No errors** (344 files)
