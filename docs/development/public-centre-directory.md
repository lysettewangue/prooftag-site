# Public centre directory

| | |
|--|--|
| **Step** | 138 — Implement the centre directory |
| **Parent** | [137 — Equipment](public-equipment.md) · [centre-entities.md](../database/centre-entities.md) · W05 |
| **Next** | 139 — Centre detail → [public-centre-detail.md](public-centre-detail.md) (COMPLETED) · 140 → [public-resource-library.md](public-resource-library.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Search, region filter, pagination; list primary; Leaflet map enhancement |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/centres-de-controle` | `fr.centres.index` |
| EN | `/en/inspection-centres` | `en.centres.index` |

Query string: `?q=` (search) · `?region=` (region id) · `page=` (pagination).

## 2. Behaviour

| Rule | Detail |
|------|--------|
| Visibility | `verification_status = active` only (`CentreDirectory::paginatePublic`) |
| Search | Name translation, `operator_name`, `city` |
| Region | Optional FK filter; invalid id ignored |
| Empty filtered | 200 + empty state + reset (W05c) — **not** 404 |
| List | Primary; links to detail (step **139** done) |
| Map | Leaflet + OSM; pins only when lat/lng present on the **current page**; hidden / empty copy otherwise |
| JS off | Form + list + pagination remain usable |

## 3. Assets

- npm `leaflet`
- `resources/js/public/centres-map.js` booted from `public/boot.js`
- Run `npm run build` (or `npm run dev`) after pull
