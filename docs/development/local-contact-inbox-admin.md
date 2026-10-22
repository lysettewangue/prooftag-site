# Contact request admin

| | |
|--|--|
| **Step** | 101 — Contact request admin |
| **Parent** | [local-media-library-admin.md](local-media-library-admin.md) · [contact-entities.md](../database/contact-entities.md) |
| **Next** | 102 — SEO and redirect admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Inbox pipeline live; empty inbox valid |

Filament module at `/admin/contact-requests`, under **Support**. Staff **must not** create a row here: the public form does. Staff **must not** hard-delete: close or mark spam (A-13). An empty inbox is valid. There is no publish verb on this type.

The resource is thin: `UpdateContactRequestStatus`. Public intake stays `SubmitContactRequest`.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| Identity (name, email, phone, subject, message, locale, IP, user-agent) | Read-only. |
| Status | Header actions along the approved matrix: `new` → `in_progress` → `responded` → `closed`, or `spam`. Closed cannot reopen. |
| Create / hard-delete | Impossible, even for Super Admin. |

## 2. Who may open it

| Role | List / edit | Transition status |
|------|-------------|-------------------|
| Super Admin / Admin | yes | yes |
| Communications editor | yes | yes |
| Support agent | yes | yes |
| Content manager / Technical / Translator / Reviewer | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=ContactInboxAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| SEO and redirect admin | 102 |
| Public contact form | 126 / 148 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=ContactInboxAdminTest` — 11 passed
- Browser: `/admin/contact-requests` empty list « Aucun(e) demandes »; Support / Demandes; no create action

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |
