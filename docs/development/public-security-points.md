# Public security points (12)

| | |
|--|--|
| **Step** | 136 — Implement the 12 security points |
| **Parent** | [135 — CT hub](public-technical-inspection.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) |
| **Next** | 137 — Equipment → [public-equipment.md](public-equipment.md) (COMPLETED) · 138 centre directory |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Listing query (no per-point slug / detail URL) |

Dedicated presentation of the twelve `SecurityPoint` rows. Hub accordion (135) stays; this page is the reserved sitemap index.

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/controle-technique/points-de-securisation` | `fr.technical-inspection.security-points` |
| EN | `/en/technical-inspection/security-points` | `en.technical-inspection.security-points` |

## 2. Behaviour

| Rule | Detail |
|------|--------|
| Data | `SecurityPointCatalog::publishedForLocale` ordered 1–12 |
| UI | Numbered accordion; first panel open; titles wrap (H-02); bodies only when expanded |
| Empty | Empty state + link back to CT hub |
| SEO | `ui.security_points.meta_*` + canonical/hreflang for the index path |
| Nav | `technical_inspection` stays active (path prefix) |

No DITROS-CT. No inventing MINT/GIEGLAN claims beyond CMS copy.
