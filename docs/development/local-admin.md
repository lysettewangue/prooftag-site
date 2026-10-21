# Global settings admin

| | |
|--|--|
| **Step** | 088 — Global settings admin |
| **Parent** | [local-filament.md](local-filament.md) · [contact-master-record.md](../charter/contact-master-record.md) |
| **Next** | 089 — Solutions admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Singleton settings form live |

Filament module at `/admin/settings/1/edit`. The resource is thin: it calls `UpdateSiteSettings`, `TranslateSiteSettings`, and `AttachMedia`. Identity fields from the contact master record are **shown and disabled**. Changing them requires a new version of that record, not a CMS edit.

## 1. What staff can edit

| Section | Fields | Rule |
|---------|--------|------|
| Identité | legal name, brand, building, city, country, BP, telephone, email, canonical URL | Frozen. Disabled in the form. Ignored by `UpdateSiteSettings` if posted. |
| Logo | image + alt FR/EN | `AttachMedia` on collection `images`. Images require both alts. |
| Réseaux / geo | LinkedIn, Facebook, X, YouTube, Instagram, latitude, longitude | Editable. Empty until VER-10. Empty means no icon / no pin. |
| FR / EN | tagline, meta title, meta description | `TranslateSiteSettings`. EN tagline seed: *Cameroon Technology Information Services*. |

There is exactly one row (`id = 1`). Filament `canCreate()` / `canDelete()` are false even for Super Admin.

## 2. Who may open it

| Role | View form | Change social/geo | Translate |
|------|-----------|-------------------|-----------|
| Super Admin / Admin | yes | yes | yes (break-glass / admin) |
| Translator | yes | no | yes |
| Other staff | no | no | no |

Support still reaches `/admin` (dashboard). Settings return **403**.

## 3. PHPUnit

```powershell
php artisan test --filter=SettingsAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Solutions (five products) | 089 |
| Expertise | 090 |
| Technical inspection cluster | 091–093 |
| Centres | 094 |
| Team | 095 |
| Partners | 096 |
| News and articles | 097 |
| Resource library | 098 |
| Pages as their own CMS module | with later corporate/content-block steps |
| Public Blade footer reading these values | 126 |

## 5. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=SettingsAdminTest` — passed
- Browser: `/admin/settings/1/edit` as admin shows frozen identity and editable social/geo

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Global settings admin implemented (step 088 executed) | 2026-10-21 |
