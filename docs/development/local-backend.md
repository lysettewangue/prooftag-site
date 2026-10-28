# Local backend application layer

| | |
|--|--|
| **Steps** | 074–087 — Actions, policies, queries, media, mail, search, cache, scheduler, SEO |
| **Parent** | [use-cases.md](../backend/use-cases.md) · [services-catalog.md](../backend/services-catalog.md) · [local-domain.md](local-domain.md) |
| **Next** | 090 — Expertise admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Named operations implemented; Filament still waits |

This band implements the 073 catalogue in `App\Actions`, `App\Policies`, `App\Http\Requests`, and `App\Queries`. There is **no** Filament module and **no** public Blade CMS page. V1 still has five products and eight staff roles.

## 1. Write actions (074–078)

| Domain | Classes |
|--------|---------|
| Products | `UpdateProduct`, `TranslateProduct`, `SubmitProductForReview`, `ApproveProduct`, `PublishProduct`, `ScheduleProduct`, `ArchiveProduct` |
| Editorial | `CreatePost` / `UpdatePost` / `TranslatePost` / submit / approve / publish / schedule / archive; `CreateCategory` / `UpdateCategory` / `TranslateCategory` / submit / approve / `PublishCategory` |
| Centres | `ImportCentre` (always unverified), `UpdateCentre`, `TranslateCentre`, `VerifyCentre`, `MarkCentreTemporarilyUnavailable` (admin), `ArchiveCentre` (admin) |
| Resources | `CreateResource` / `UpdateResource` / `TranslateResource` / submit / approve / publish / schedule / archive; `DownloadResource` (published + `documents` disk) |
| Contact | `SubmitContactRequest` (honeypot `website`, 5 / 60 s per IP; spam sends no mail), `UpdateContactRequestStatus` |

Shared A-09 transitions go through `App\Actions\Publication\TransitionPublication`. Draft cannot jump to Published. Product slugs must equal `slug_fragment`. CT-VERIF and DOSER still need `external_url` before Published.

## 2. Policies and validation (079–080)

Policies live in `App\Policies\*`. Technical update vs communications update vs `StaffPermission` verbs (`publish`, `translate`, `verify_centre`, `contact_inbox`, `manage_redirects`) are checked server-side. Product `create` / `delete` are always denied.

Each write use case has a FormRequest under `App\Http\Requests\{Domain}`.

## 3. Public reads (081, 084, 085)

`App\Queries\ProductCatalog`, `CentreDirectory`, `ResourceLibrary`, `PostIndex`, `RelatedContent`, `SiteSearch`, `SitemapQuery`.

Search is MySQL `LIKE` on published products/posts/resources and Active centres. Cache keys `catalog.{products|centres|resources|posts}.{locale}` and `seo.sitemap` (file driver). Writes and publish/verify events flush via `ContentCache::flush()`.

## 4. Media, mail, scheduler, SEO (082–087)

- `AttachMedia` / `SafeDeleteMedia`: MIME whitelist jpeg/png/webp/svg/pdf, 8 MB, `alt.fr` / `alt.en` on images, block delete while a public owner still references the file. `HasCmsMedia` on SiteSetting, TeamMember, Partner, Product, Equipment, Post, Resource, Page.
- `ContactReceivedMail` markdown to Settings.email; skipped for spam; sets `notified_at`.
- `content:publish-scheduled` every 10 minutes; `activitylog:clean --days=365 --force` weekly.
- `SeoMetaResolver` (defaults → translation → override; canonical host `https://www.prooftagcatis.com`); `RedirectResolver` exact then longest prefix; `UpsertRedirect`. Sitemap = Published URLs + Active centres.

## 5. PHPUnit

```powershell
php artisan test --filter=BackendActionsTest
php artisan test
```

## 6. Out of this band

| Item | Step |
|------|------|
| Filament resources and settings admin | 088+ |
| Public Blade templates | 123+ |
| HTTP download / search / sitemap routes | Search → [public-search.md](public-search.md) (146) · Sitemap/robots → [public-sitemap-robots.md](public-sitemap-robots.md) (149) |

## 7. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test` — **64 passed** (BackendActionsTest 18, plus existing)

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Backend application services through SEO implemented (steps 074–087 executed) | 2026-10-21 |
