# Corporate contact record

| | |
|--|--|
| **Step** | 004 — Freeze corporate contact information |
| **Parent** | [project-charter.md](project-charter.md) v1.0 |
| **Next** | 005 — Document brand constraints |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

This is the step 004 deliverable. It is the **single** source for footer, contact page, JSON-LD `Organization`, and Filament global settings. Do not copy these values into templates or product pages. Social URLs and map coordinates are **not** frozen; they remain verification-required (VER-10).

## 1. Frozen fields

| Field | Value | Publish |
|-------|--------|---------|
| Legal name | PROOFTAG-CATIS S.A. | Yes |
| Trading / brand | PROOFTAG-CATIS · CATIS | Yes |
| Tagline (EN) | Cameroon Technology Information Services | Yes, where an English line is needed |
| Building | Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso | Yes |
| City | Douala | Yes |
| Country | Cameroun | Yes (EN: Cameroon) |
| Postal box | BP 4601 | Yes |
| Telephone | +237 699 19 29 19 | Yes |
| Email (primary) | info@prooftagcatis.com | Yes |
| Website (canonical) | https://www.prooftagcatis.com | Yes |

There is no secondary public email, fax, or opening-hours line in V1 unless this record is revised.

## 2. Display lines (use as written)

**French**

PROOFTAG-CATIS S.A.  
Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso  
BP 4601 Douala, Cameroun  
Tél. : +237 699 19 29 19  
info@prooftagcatis.com

**English**

PROOFTAG-CATIS S.A.  
Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso  
P.O. Box 4601 Douala, Cameroon  
Tel.: +237 699 19 29 19  
info@prooftagcatis.com

Telephone stays in international form in both locales. Do not localise the building name.

## 3. Do not publish

| Value | Reason |
|-------|--------|
| info@net.prooftagcatis.com | Legacy mirror only |
| Yelp or other leftover www social links | Not institutional |
| Unverified LinkedIn, Facebook, X, YouTube, Instagram URLs | VER-10 |
| Map coordinates inferred from legacy embeds | Verify before use |
| Live user / region / centre counters | Out of scope unless leadership supplies a process |

## 4. Social and geo (not frozen)

These may be stored in CMS as empty until VER-10 (social) and a coordinate check close. Empty means **no icon and no map pin** — not a placeholder URL.

| Network | Status |
|---------|--------|
| LinkedIn | TBD |
| Facebook | TBD |
| X | Candidate from site metadata: `https://x.com/ProoftagCatis` — verify |
| YouTube | TBD |
| Instagram | TBD |

Optional `geo.latitude` / `geo.longitude` for the contact page: set only after verification. The postal address above does not require a map.

## 5. CMS and structured data

- Persist as **global settings**, not per-page fields.
- JSON-LD `Organization`: `name`, `url`, `email`, `telephone`, `address` from this record only.
- Contact form mail **from/to** for public enquiries uses `info@prooftagcatis.com` unless a later settings change is recorded.
- Product outbound URLs (CT-VERIF, DOSER) are **not** contact fields; they live on product records ([products.md](products.md)).

## 6. Change control

- Changing a frozen field in section 1 requires a new version of this record.
- Filling social URLs or coordinates after verification is an update to section 4 only; it does not bump the frozen identity.
- Brand colours, logo asset, and navigation are step 005 / 006, not this file.

## 7. Completion of step 004

Step 004 is complete when this record exists at version 1.0 with the frozen identity fields, display lines, and publish rules, and the tracker marks 004 **COMPLETED**.

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 corporate contact record (step 004 executed) | 2026-10-20 |
| Technical lead | | |
| Communications | | |
