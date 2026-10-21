# Local domain layer

| | |
|--|--|
| **Steps** | 070–072 — Domain models, enums, visibility rules |
| **Parent** | [domain-design.md](../backend/domain-design.md) · [layers.md](../architecture/layers.md) (024) |
| **Next** | 074 — Implement solution actions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Eloquent models, enums, and public-visibility rules in place |

Eloquent stays in `App\Models` (infrastructure). Rules live in `App\Domain\*`. Named write operations are listed at [use-cases.md](../backend/use-cases.md) (073) and are **not** implemented here.

## 1. Models (070)

Translatable parents implement `App\Domain\Contracts\Translatable` via `HasTranslations` (`translationFor`, `hasLocale`, `forLocale`, `withLocaleSlug`).

A-09 types also implement `App\Domain\Contracts\Publishable` via `Publishable` (`isPubliclyVisible`, `isPubliclyVisibleIn`, `canTransitionTo`, `publiclyVisible` scope).

Centres use verification, not A-09: `InspectionCentre::publiclyListed()` / `isPubliclyListed()`.

## 2. Enums (071)

| Enum | Added behaviour |
|------|-----------------|
| `PublicationStatus` | `isPublicState()` (Published only), transition matrix, `requiresPublishPermission()` |
| `CentreVerificationStatus` | `isPubliclyListed()` (Active only), transition matrix |
| `ContactRequestStatus` | Inbox transitions; `dispatchesInboundMail()` is false for spam |
| `ProductCode` | `requiresExternalUrlBeforePublish()` for CT-VERIF and DOSER |
| `Locale` | `fr` / `en` |

Scheduled is not a public state. The scheduler (086) must move the row to Published.

## 3. Rules (072)

| Class | Rule |
|-------|------|
| `App\Domain\Publication\Visibility` | Public iff Published and `published_at` ≤ now |
| `App\Domain\Translation\LocaleAvailability` | No marketing fallback; missing translation = no URL |
| `App\Domain\Products\ProductPublishRules` | CT-VERIF / DOSER need `external_url` before Published |
| `App\Domain\Centres\CentreVisibility` | Public list = Active; map pin only with coordinates |
| `App\Domain\Communication\ContactPipeline` | Spam does not send inbound mail |

## 4. PHPUnit

```powershell
php artisan test --filter=PublicationRulesTest
php artisan test --filter=VisibilityRulesTest
```

## 5. Out of this band

| Item | Step |
|------|------|
| Named use cases | 073 (done — [use-cases.md](../backend/use-cases.md)) |
| Product actions | 074 |
| Policies | 079 |
| Filament | 088+ |

## 6. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test` — **46 passed** (PublicationRulesTest 6, VisibilityRulesTest 6, plus existing)

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Domain models, enums, and visibility rules implemented (steps 070–072 executed) | 2026-10-21 |
