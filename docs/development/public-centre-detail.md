# Public centre detail

| | |
|--|--|
| **Step** | 139 — Implement the centre detail page |
| **Parent** | [138 — Centre directory](public-centre-directory.md) · W05b |
| **Next** | 140 — Resource library → [public-resource-library.md](public-resource-library.md) (COMPLETED) · 141 → [public-news-listing.md](public-news-listing.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Active centres only; missing contact fields explained once |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/centres-de-controle/{slug}` | `fr.centres.show` |
| EN | `/en/inspection-centres/{slug}` | `en.centres.show` |

## 2. Behaviour

| Zone | Rule |
|------|------|
| Visibility | `Active` + locale translation; else **404** (Unverified / unavailable / archived never public) |
| Chrome | Breadcrumb directory → name; H1; city · region |
| Contact | Show phone / email only when filled; one muted line lists missing phone / email / map |
| Address / body | Optional translation fields |
| Map | Leaflet pin when lat/lng present; otherwise empty dashed panel (no fake pin) |
| SEO | `SeoMetaResolver::forModel` |
