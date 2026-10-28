# Public contact

| | |
|--|--|
| **Step** | 144 — Implement contact |
| **Parent** | [143 — Careers](public-careers.md) · [contact-entities.md](../database/contact-entities.md) · [contact-master-record.md](../charter/contact-master-record.md) · W08 / W08b |
| **Next** | 145 — Legal pages → [public-legal.md](public-legal.md) (COMPLETED) · 146 → [public-search.md](public-search.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Page + form; Settings identity; honeypot + throttle |

## 1. Routes

| Locale | Method | Path | Name |
|--------|--------|------|------|
| FR | GET | `/contact` | `fr.contact` |
| FR | POST | `/contact` | `fr.contact.store` |
| EN | GET | `/en/contact` | `en.contact` |
| EN | POST | `/en/contact` | `en.contact.store` |

## 2. Behaviour

| Zone | Rule |
|------|------|
| Page | Published `Page` `contact` required; else **404** |
| Identity | Frozen Settings lines (legal name, building, postal, phone, email) — not per-page fields |
| Intro | Optional page blocks |
| Form | name, email, subject, message required; phone optional; visible labels |
| Honeypot | Field `website` (config) — hidden, not labelled for users; filled → store as Spam, no mail, still success redirect |
| Throttle | 5 / 60 s per IP (`cms.contact_*`) |
| Success | Session flash; form replaced by success alert (W08b) |
| Errors | Summary above form + messages beside fields |
| Mail | `SubmitContactRequest` → `ContactReceived` (non-spam only) |

## 3. Notes

- Address display reuses `PublicFooter` formatting (FR/EN postal / country).
- No secondary email, fax, or hours in V1.
- Social / map pins stay VER-10 (not on this page).
