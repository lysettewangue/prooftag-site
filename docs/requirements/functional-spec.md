# Functional and non-functional requirements

| | |
|--|--|
| **Steps** | 017 public · 018 admin · 019 non-functional |
| **Audiences** | [audience-matrix.md](audience-matrix.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

## 017 — Public requirements

| ID | Capability | Rule |
|----|------------|------|
| P-01 | Company profile | About, organisation, history, team, partners — unpublished if VER open |
| P-02 | Products | Index + five detail routes; hide unpublished |
| P-03 | Expertise | Index + five domains |
| P-04 | Technical inspection | Hub, workflow, 12 points, equipment |
| P-05 | Centre directory | Region filter, search, pagination, detail; Active only |
| P-06 | Resources | Browse/download; current vs historical |
| P-07 | News / articles | Listing, categories, article; drafts never public |
| P-08 | Contact | Validated form, honeypot/rate-limit, mail to frozen email |
| P-09 | Language switch | FR ↔ EN equivalent URL; no EN URL without EN record |
| P-10 | External solutions | CMS `external_url` only |
| P-11 | Careers | Index + jobs; empty state allowed |
| P-12 | Legal | Mentions légales, privacy |
| P-13 | Search | MySQL over published content |
| P-14 | Logo home | Active locale homepage; no Accueil item |
| P-15 | System pages | Branded 404 / 500 / maintenance |

## 018 — Administration requirements

`/admin`. No public registration.

| ID | Module | Rule |
|----|--------|------|
| A-01 | Settings | Contact, logos, social, default SEO — contact fields from the frozen record |
| A-02 | Pages + blocks | Approved blocks only; brand/nav not editable |
| A-03 | Products | Exactly five codes; cannot add a sixth without a register change |
| A-04 | Expertise | Five domains |
| A-05 | CT + 12 points | Ordered 1–12 |
| A-06 | Equipment | Current vs legacy status |
| A-07 | Centres | Unverified → Active / Unavailable / Archived; `verify` permission |
| A-08 | Team / partners | |
| A-09 | Editorial | Draft → Under Review → Approved → Scheduled → Published → Archived |
| A-10 | Resources | File + regulatory_status |
| A-11 | Careers | |
| A-12 | Media | MIME whitelist; bilingual alt |
| A-13 | Contact inbox | New → In Progress → Responded → Closed → Spam |
| A-14 | SEO / redirects | Canonical, meta, legacy map |
| A-15 | Users / roles / audit | Eight roles at step 035; publish ≠ translate ≠ update |

Scheduled publish: Laravel scheduler + LWS cron, short job.

## 019 — Non-functional requirements

| Area | Requirement |
|------|-------------|
| Performance | Server-rendered; optimised images; pagination on centres/news/resources |
| Accessibility | WCAG 2.2 AA target |
| Security | Least privilege; CSRF; upload whitelist; no public register; MFA recommended; no operational DB links |
| SEO | Canonical www; hreflang; sitemap; robots; 301 register |
| i18n | `fr` default; `en` secondary; UI strings may fall back; marketing body may not |
| Maintainability | Content changes without deploy |
| Privacy | Cookie banner only if non-essential tracking is added |
| Availability | Best-effort on LWS Starter; branded maintenance page |
| Hosting | PHP 8.4, docroot `public/`, sync queue, file/database cache & sessions |

## Workflows

**Editorial:** Draft → Under Review → Approved → Scheduled → Published → Archived.  
**Contact:** New → In Progress → Responded → Closed → Spam.  
**Centre:** Unverified (import default) → Active | Temporarily unavailable | Archived.

## Gate

Requirements frozen → final sitemap (020).
