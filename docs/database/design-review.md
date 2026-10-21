# Database design review

| | |
|--|--|
| **Step** | 055 — Review and approve the database design |
| **Parent** | [erd.md](erd.md) · [data-dictionary.md](data-dictionary.md) · [constraints.md](constraints.md) · [indexes.md](indexes.md) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | **Gate passed.** No CMS content migrations before this document. |

This is the step 055 deliverable. It approves the conceptual and logical model (039–054) for V1. **Migrations start at 056** (regions and controlled statuses first).

## 1. Package reviewed

| Step | Deliverable |
|-----:|-------------|
| 039 | [principal-entities.md](principal-entities.md) |
| 040 | [corporate-entities.md](corporate-entities.md) |
| 041 | [solution-entities.md](solution-entities.md) |
| 042 | [expertise-entities.md](expertise-entities.md) |
| 043 | [technical-inspection-entities.md](technical-inspection-entities.md) |
| 044 | [centre-entities.md](centre-entities.md) |
| 045 | [editorial-entities.md](editorial-entities.md) |
| 046 | [resource-entities.md](resource-entities.md) |
| 047 | [media-entities.md](media-entities.md) |
| 048 | [contact-entities.md](contact-entities.md) |
| 049 | [seo-entities.md](seo-entities.md) |
| 050 | [translation-strategy.md](translation-strategy.md) (ADR-001 accepted) |
| 051 | [erd.md](erd.md) |
| 052 | [data-dictionary.md](data-dictionary.md) |
| 053 | [constraints.md](constraints.md) |
| 054 | [indexes.md](indexes.md) |

## 2. Checks

| Check | Result |
|-------|--------|
| Exactly five products; no DITROS-CT entity | Pass |
| Exactly eight staff roles; publish ≠ translate ≠ update | Pass |
| Contact only in Settings | Pass |
| Publication on base; EN URL requires EN translation | Pass |
| Centres: Unverified default; public Active only | Pass |
| Resources: current / historical / archive; no blobs in MySQL | Pass |
| No FK to operational systems | Pass |
| Parent + FR/EN translation tables (Media alt exception) | Pass |
| LWS Starter: no Redis, no search engine | Pass |

## 3. Out of this gate

| Item | Step |
|------|------|
| Reference + CMS migrations | 056–069 |
| Eloquent models / actions | 070+ |
| Filament modules | 088+ |
| DITROS-CT URL | 166 |

Changing an approved table after this gate needs a new version of the affected spec **and** a recorded design change before migrate.

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Database design approved (step 055 executed). Migrations may begin at 056. | 2026-10-21 |
