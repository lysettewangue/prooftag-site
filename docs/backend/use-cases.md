# Application use cases

| | |
|--|--|
| **Step** | 073 — Define application use cases |
| **Parent** | [domain-design.md](domain-design.md) · [services-catalog.md](services-catalog.md) |
| **Next** | 074 — Implement solution actions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Named operations frozen |

This is the step 073 deliverable. Each row is **one application action class** (one use case). Domain rules already live in `App\Domain` (070–072). This step does **not** implement the classes. Authorization is a policy (079), not a hidden button. Validation is 080. Filament stays thin (088+).

V1 still has exactly five products and eight staff roles. Publish, translate, and update stay separate. DITROS-CT is not an entity.

## 1. Naming

`App\Actions\{Domain}\{Verb}{Entity}` — imperative, English. One class, one `handle()` / `__invoke()`.

Public reads are **queries** (`App\Queries\…`, step 081), not these write use cases.

## 2. Catalogue

### Corporate

| Use case | Step | Notes |
|----------|-----:|-------|
| `UpdateSiteSettings` | 088 | Frozen identity fields require a new contact-record version. |
| `UpdatePage` | 088 | Draft/update only. |
| `TranslatePage` | 088 | EN from existing FR. |
| `PublishPage` / `SchedulePage` / `ArchivePage` | 088 | `publish` permission. |
| `UpdateTeamMember` / `TranslateTeamMember` / `PublishTeamMember` | 088 | Do not publish until VER-02. |
| `UpdatePartner` / `TranslatePartner` / `PublishPartner` | 088 | Do not publish until VER-09. |
| `UpdateCareer` / `TranslateCareer` / `PublishCareer` | 088 | Empty published set is valid. |

Pages cannot be created beyond the eleven frozen `code`s without a sitemap revision.

### Products

| Use case | Step | Notes |
|----------|-----:|-------|
| `UpdateProduct` | **074** | Cannot create a sixth row or delete one of the five. `code` immutable. |
| `TranslateProduct` | **074** | Slug must equal `slug_fragment` in both locales. |
| `PublishProduct` | **074** | CT-VERIF / DOSER require `external_url`. |
| `ScheduleProduct` / `ArchiveProduct` | **074** | Same publication matrix as A-09. |

### Expertise and technical inspection

| Use case | Step | Notes |
|----------|-----:|-------|
| `UpdateExpertise` / `TranslateExpertise` / `PublishExpertise` | 090 | Five rows only. Titles must not copy product names. |
| `UpdateSecurityPoint` / `TranslateSecurityPoint` / `PublishSecurityPoint` | 092 | Twelve rows; identity is `sort_order`. |
| `UpdateEquipment` / `TranslateEquipment` / `PublishEquipment` | 092 | `legacy` is not hard-delete. |

### Centres

| Use case | Step | Notes |
|----------|-----:|-------|
| `ImportCentre` | **076** | Always `unverified`. |
| `UpdateCentre` / `TranslateCentre` | **076** | Region required. |
| `VerifyCentre` | **076** | `verify_centre` only; sets Active + `last_verified_at`. |
| `MarkCentreTemporarilyUnavailable` | **076** | Admin only; not public. |
| `ArchiveCentre` | **076** | Public 404. |

### Editorial

| Use case | Step | Notes |
|----------|-----:|-------|
| `CreatePost` / `UpdatePost` | **075** | Drafts never public. |
| `TranslatePost` | **075** | Independent FR/EN slugs allowed. |
| `SubmitPostForReview` / `ApprovePost` | **075** | A-09. |
| `PublishPost` / `SchedulePost` / `ArchivePost` | **075** | `publish` permission. |
| `UpdateCategory` / `TranslateCategory` / `PublishCategory` | **075** | |

Career is not a Post.

### Resources, contact, SEO, media

| Use case | Step | Notes |
|----------|-----:|-------|
| `UpdateResource` / `TranslateResource` / `PublishResource` | **077** | `archive` is a label, not soft-delete. |
| `DownloadResource` | **077** | Controlled route, `documents` disk. |
| `SubmitContactRequest` | **078** | Honeypot + throttle; spam sends no mail. |
| `UpdateContactRequestStatus` | **078** | Inbox pipeline. |
| `UpsertRedirect` | 087 / 101 | Unique `(from_host, from_path)`. |
| `AttachMedia` / `SafeDeleteMedia` | **082** | MIME whitelist; block delete while referenced. |

### Scheduler (not staff)

| Use case | Step | Notes |
|----------|-----:|-------|
| `PublishScheduledContent` | **086** | `scheduled` → `published` when `scheduled_for` ≤ now. Short cron only. |

## 3. Shared publication verbs

For every A-09 type, the same three staff verbs apply:

| Verb | Permission | From → to |
|------|------------|-----------|
| update | domain update | `draft` ↔ `under_review` → `approved` |
| translate | `translate` | write a locale row; never publish |
| publish | `publish` | `approved` → `scheduled` \| `published`; `published` → `archived` |

Scheduled rows are **not** public. They become public only after `PublishScheduledContent` (or `Publish*`) sets `publication_status = published` and `published_at` ≤ now.

## 4. Out of this step

| Item | Step |
|------|------|
| `UpdateProduct` / `PublishProduct` implementation | 074 |
| Editorial actions | 075 |
| Centre actions | 076 |
| Resource actions | 077 |
| Contact processing | 078 |
| Policies | 079 |
| Form requests | 080 |
| Public query services | 081 |

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Application use cases named (step 073 executed) | 2026-10-21 |
