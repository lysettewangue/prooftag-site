# Model review against real data

| | |
|--|--|
| **Step** | 112 — Review the model against real data |
| **Parent** | [verification-register.md](../content/verification-register.md) · [local-locale-management.md](../development/local-locale-management.md) |
| **Next** | 113 — Freeze the V1 data model |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Gaps recorded. No schema change required before freeze |

Representative CMS rows (107–110) plus the 014 register. Empty published sets remain valid. VER-01–13 stay **Open**.

## 1. Schema that held

| Catalogue | Seeded | Fit |
|-----------|--------|-----|
| Settings singleton | 1 | Contact master record. Social/geo empty (VER-10). |
| Pages | 11 codes | Empty `blocks` until copy is written. CT hub is a Page, not a product. |
| Products | 5 Draft | Themes 015. No sixth / DITROS-CT row. CT-VERIF and DOSER `external_url` null (VER-04, VER-05, VER-11). |
| Expertises | 5 | Titles do not copy product names. |
| Security points | 12 Draft | Rewrite-required bodies (AUD-001). |
| Equipment | 4 Draft | Current + one legacy. Staff may still create. |
| Regions | 10 | Frozen. No region admin. |
| Centres | 4 | 3 Active + 1 Unverified. No invented phones/emails/coordinates. |
| Posts / categories | 2 / 2 Draft | Sample only (AUD-027: legacy blog empty). Career is not a Post. |
| Resources | 1 Draft historical | No PDF → cannot publish (VER-08). |
| Team / partners / careers | 0 | Valid empty. VER-02 / VER-09 / P-11. |
| Redirects / contact inbox | 0 | Valid until migration 164+ / public form. |

## 2. Gaps (content, not columns)

| Gap | Why it is not a new table |
|-----|---------------------------|
| VER-01 homepage exclusive-operator claim | Page `blocks` already store the hero. Do not publish until Legal closes VER-01. |
| VER-02 / VER-13 team vs organigramme | `team_members` already exists. Empty until RH confirms. |
| VER-03 ~35 centres, duplicates, phones | `inspection_centres` already exists. Import stays Unverified. |
| VER-04–07 / VER-11 product public copy and URLs | `products.external_url` and translations already exist. Stay Draft. |
| VER-08 2020 reform current vs historical | `resources.regulatory_status` already has current / historical / archive. |
| VER-09 partners | `partners` already exists. |
| VER-10 social URLs | Settings columns already exist and are empty. |
| VER-12 About / Prooftag SAS wording | Page About `blocks`. |
| Media library empty of production assets | Spatie `media` + `alt.fr` / `alt.en`. No orphan create. |
| Post body is `{html}` while pages use approved blocks | Intentional V1 split. Posts do not need a second block schema before freeze. |
| `posts.author_id` unused | Optional; not a public byline until verified. Keep the column. |
| No missing-translation dashboard widget | `missingLocales()` is enough for V1 freeze. Widget is later polish. |

## 3. Verification (2026-10-22)

- Compared frozen catalogues (055/069) and representative seeds (107–110) with VER-01–13.
- No migration. No extra product, role, page, security point, or region.

## 4. Decision

Do **not** add tables, columns, or product codes before gate **113**. Remaining work is editorial (VER rows, 151+) or public templates (123+).

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Model reviewed against representative data; gaps recorded (step 112 executed) | 2026-10-22 |
