# Media entities

| | |
|--|--|
| **Step** | 047 — Define media entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [system-architecture.md](../architecture/system-architecture.md) §5 |
| **Next** | 070 — Implement domain models |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Path, MIME, rights, and bilingual alt specified |

This is the step 047 deliverable. Domain: `App\Domain\Media`. Implementation: **Spatie Media Library** (planned package). Binaries are files; the database stores metadata. **No migrations before gate 055.**

## 1. Required attributes

| Attribute | Rule |
|-----------|------|
| `disk` | `public` (images) or `documents` (PDFs / downloads). `local`/`private` for admin-only if needed. |
| `path` / filename | Stored by Spatie. Lowercase, hyphenated, no spaces (standards). |
| `mime_type` | Whitelist only: `image/jpeg`, `image/png`, `image/webp`, `image/svg+xml` (logos), `application/pdf`. Reject anything else. |
| `size` | Bytes. Respect LWS upload limits. |
| `rights` | Short provenance / licence note (who may publish this file). Empty = do not use on the public site. |
| `alt` per locale | Required for images before public use. FR and EN may differ. **Exception to ADR-001:** stored in Spatie `custom_properties` as `alt.fr` / `alt.en`, not a separate translation table. |

Collections (logical, not extra entities): Settings logos, TeamMember photo, Partner logo, Product card/hero, Equipment photo, Post hero, Resource file, Page/block attachments.

## 2. Rules

- Do not store blobs in MySQL.
- Prevent delete while a published record still references the file.
- Product marks are allowed on product pages; they are not Settings corporate logos (040).
- DITROS / operational screenshots are not corporate identity.

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Media entities specified (step 047 executed) | 2026-10-21 |
