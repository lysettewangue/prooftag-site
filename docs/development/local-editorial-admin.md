# News and articles admin

| | |
|--|--|
| **Step** | 097 — News and articles admin |
| **Parent** | [local-partners-admin.md](local-partners-admin.md) · [editorial-entities.md](../database/editorial-entities.md) |
| **Next** | 111 — Test French and English management |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Posts and categories editable as drafts; A-09 on both |

Filament modules under **Éditorial**: publications at `/admin/posts`, categories at `/admin/categories`. Staff **may create** a row. Staff **must not** hard-delete one: archive via A-09. Drafts are never public. Independent FR/EN slugs are allowed. Body is a single rich-text payload. Sample rows: [local-sample-news.md](local-sample-news.md).

The resources are thin: `CreatePost` / `UpdatePost` / `TranslatePost` / `AttachMedia` (optional hero) / `TransitionPublication`; `CreateCategory` / `UpdateCategory` / `TranslateCategory` / `TransitionPublication`. Career is not a Post.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `kind` | `news` or `article`. |
| Categories, products, expertises | Optional tagging. Synced by `UpdatePost`. |
| Hero | Optional. Images require `alt.fr` and `alt.en`. Collection `images`. |
| Title, slug, summary, body, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the title. Unique per `(locale, slug)`. |
| Category `name` / slug / sort_order | Same locale split. Unpublished categories hide from public filters. |
| Publication | A-09 header actions. Draft cannot jump to Published. |
| Hard-delete | Impossible. Filament `canDelete` stays false even for Super Admin. |
| `author_id` | Set to the creating staff user. Not a public byline in V1. |

## 2. Who may open it

| Role | List / edit | Create / update FR | Translate EN | Publish |
|------|-------------|--------------------|--------------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Communications editor | yes | yes | no | yes |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Technical editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=EditorialAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Resource library admin | 098 |
| Careers | 099 |
| Sample news records | 110 (done — [local-sample-news.md](local-sample-news.md)) |
| Approved content blocks | 105 |
| Public listing / article page | 141–142 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=EditorialAdminTest` — 13 passed
- `php artisan test` — 171 passed
- Browser: `/admin/posts` empty list; create draft “Nouvelle CT”; A-09 helper; edit shows Soumettre à relecture / Archiver; no delete; `/admin/categories` empty list

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | News and articles admin implemented (step 097 executed) | 2026-10-22 |
