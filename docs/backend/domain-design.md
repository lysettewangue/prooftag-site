# Backend domain design

Steps 070–087. Rules live in the domain layer. Actions live in the application layer. Controllers and Filament stay thin.

## Domains

| Module | Namespace | Responsibilities |
|--------|-----------|------------------|
| Corporate | `App\Domain\Corporate` | Settings, pages, team, partners, careers ([corporate-entities.md](../database/corporate-entities.md)) |
| Products | `App\Domain\Products` | Five seeded solutions, slugs, external links ([solution-entities.md](../database/solution-entities.md)) |
| Expertise | `App\Domain\Expertise` | Five seeded domains ([expertise-entities.md](../database/expertise-entities.md)) |
| TechnicalInspection | `App\Domain\TechnicalInspection` | 12 points, equipment ([technical-inspection-entities.md](../database/technical-inspection-entities.md)) |
| Centres | `App\Domain\Centres` | Regions, verification ([centre-entities.md](../database/centre-entities.md)) |
| Editorial | `App\Domain\Editorial` | Posts, categories, A-09 states ([editorial-entities.md](../database/editorial-entities.md)) |
| Resources | `App\Domain\Resources` | Files, current/historical/archive ([resource-entities.md](../database/resource-entities.md)) |
| Communication | `App\Domain\Communication` | Contact pipeline ([contact-entities.md](../database/contact-entities.md)) |
| Seo | `App\Domain\Seo` | Meta, redirects ([seo-entities.md](../database/seo-entities.md)) |
| Media | `App\Domain\Media` | Path, MIME, alt ([media-entities.md](../database/media-entities.md)) |

## PublicationService

**Invariants:**

- Public queries always filter `publication_status = published` and `published_at <= now()` unless preview auth.  
- Transitions: only roles with `publish` permission move to Published.  
- Scheduled: visible only after scheduler runs `PublishScheduledContent`.  
- Archived: excluded from public indexes; direct URL returns 404 unless policy allows preview.

**Methods (draft):**

- `transition(Publishable $model, PublicationStatus $to, User $actor): void`  
- `schedule(Publishable $model, Carbon $at, User $actor): void`  
- `unpublish(Publishable $model, User $actor): void`  

## TranslationService

- Resolve slug for locale; 404 if missing EN and policy denies fallback for content body.  
- Build hreflang pairs for sitemap and `<link rel="alternate">`.  
- Flag missing translations for admin dashboard.

## CentreDirectoryService

- Import sets `verification_status = unverified`.  
- Only `verify` action sets Active + `last_verified_at`.  
- Public list excludes Archived unless admin preview.

## ContactSubmissionService

- Honeypot + rate limiting (IP/throttle).  
- Persist `contact_requests`; dispatch notification mail optionally.  
- Mark spam without sending mail.

## RedirectResolver

- Match `(host, path)` longest prefix; 301/302 from DB.  
- Log unresolved legacy hits for review at step 236.

## SeoMetaResolver

- Merge global defaults → translation meta → page overrides.  
- Canonical URL always `https://www.prooftagcatis.com` + localized path.

## MediaAttachmentService

- Allowed MIME whitelist (images, pdf).  
- Max size aligned with LWS upload limits.  
- Prevent delete when media referenced.

## Authorization

Policies: `ProductPolicy`, `PostPolicy`, `InspectionCentrePolicy`, `ResourcePolicy`, etc.

Permissions (Shield): `view_any`, `view`, `create`, `update`, `delete`, `publish`, `translate`, `verify_centre`.

## Events

| Event | Listeners |
|-------|-----------|
| ContentPublished | Activity log; optional email to comms |
| ContactReceived | Notify support role |
| CentreVerified | Activity log |

## Scheduled tasks

| Task | Schedule |
|------|----------|
| `PublishScheduledContent` | Every 5–15 minutes (keep cron job short) |
| `PruneOldActivityLogs` | Weekly |

## Public API

**None for V1.** Optional ViewModels:

- `HomePageViewModel`, `ProductShowViewModel`, `CentreIndexViewModel` — read-only DTOs for Blade.

## Error handling

- Public: log exception; show branded 404/500.  
- Admin: show validation + Filament danger notification.

## Gate

Domain rules are in place by step 072. Filament modules start at step 088.

See [services-catalog.md](services-catalog.md) for method-level detail.
