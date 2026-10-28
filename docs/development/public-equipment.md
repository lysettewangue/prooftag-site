# Public equipment index and detail

| | |
|--|--|
| **Step** | 137 — Implement equipment |
| **Parent** | [136 — Security points](public-security-points.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) |
| **Next** | 138 — Centre directory → [public-centre-directory.md](public-centre-directory.md) (COMPLETED) · 139 centre detail |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Listing + detail under the CT section |

## 1. Routes

| Locale | Index | Detail |
|--------|-------|--------|
| FR | `/controle-technique/equipements` | `/controle-technique/equipements/{slug}` |
| EN | `/en/technical-inspection/equipment` | `/en/technical-inspection/equipment/{slug}` |

Names: `*.technical-inspection.equipment.index` / `*.technical-inspection.equipment.show`.

## 2. Behaviour

| Rule | Detail |
|------|--------|
| Index | Published equipment; **current** first, then **legacy**; cards with lifecycle eyebrow |
| Detail | Breadcrumb hub → index → title; body HTML; optional media; empty states when missing |
| Draft / missing locale | Detail **404**; index shows empty state |
| Paths | `cms.path_prefixes.equipment` + `PublicPath::for(Equipment)` |
| Hub teaser (135) | Preview cards now link to detail URLs |

Legacy items stay listable as historical — never framed as “available now”.
