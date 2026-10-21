# Administrative role register

| | |
|--|--|
| **Step** | 035 — Define administrative roles |
| **Parent** | [functional-spec.md](../requirements/functional-spec.md) A-15 · [scope-and-exclusions.md](../charter/scope-and-exclusions.md) §3 |
| **Next** | 041 — Define solution entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

This is the step 035 deliverable. V1 has **exactly eight** roles. Names and codes below are immutable without a new version of this register. Server enforcement (Spatie + Filament Shield) is step **036**. Policies on Filament modules are steps **088–106**.

No public self-registration. Panel access is staff-only at `/admin`.

## 1. Separation of verbs

| Verb | Meaning |
|------|---------|
| **update** | Create or edit a draft / record |
| **translate** | Create or edit a locale other than the source (EN from FR in V1) |
| **publish** | Move content to a public state (including scheduled publish) |

These three are **not** the same permission. A role that can update must not automatically publish or translate.

## 2. The eight roles

Codes are Shield / Spatie names (snake_case). Display names are English (canonical) and French (admin UI).

| # | Code | EN | FR | Purpose |
|---|------|----|----|---------|
| 1 | `super_admin` | Super Administrator | Super administrateur | Break-glass. All abilities. User and role administration. Not for daily editorial work. |
| 2 | `admin` | Administrator | Administrateur | Platform operations: settings, SEO, redirects, media policy, non-super users. |
| 3 | `content_manager` | Content Manager | Responsable contenu | Owns the editorial calendar and content model. Accountable for what goes live ([content-governance.md](../governance/content-governance.md)). |
| 4 | `technical_editor` | Technical Editor | Rédacteur technique | Products, Contrôle technique, 12 points, equipment, expertise, centre technical data. |
| 5 | `communications_editor` | Communications Editor | Rédacteur communication | News, about, careers, pages, public-facing copy. |
| 6 | `translator` | Translator | Traducteur | English (and later locales) from an existing source. Must not change the source meaning. |
| 7 | `reviewer_publisher` | Reviewer/Publisher | Relecteur / Publieur | Four-eyes: approve and **publish**. Distinct from the drafter. |
| 8 | `support_agent` | Support Agent | Agent support | Contact inbox and centre operational follow-up. No marketing publish. |

## 3. Intended abilities (not yet enforced)

Draft matrix in [services-catalog.md](../backend/services-catalog.md). Confirmed per role at step **106**. Summary for this register:

| Role | update (own domain) | translate | publish | verify_centre | manage_redirects | contact_inbox | manage_users |
|------|---------------------|-----------|---------|---------------|------------------|---------------|--------------|
| Super Administrator | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Administrator | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ (not Super Admin) |
| Content Manager | ✓ | ✓ | | | | | |
| Technical Editor | ✓ | | | ✓ | | | |
| Communications Editor | ✓ | | ✓ | | | ✓ | |
| Translator | | ✓ | | | | | |
| Reviewer/Publisher | | | ✓ | | | | |
| Support Agent | | | | | | ✓ | |

Empty cell = denied. Content Manager is **accountable** for publication (RACI) but does not hold the publish permission — Reviewer/Publisher (or Comms for communications pages) presses publish.

Last remaining `super_admin` cannot be deleted or demoted.

## 4. Assignment rules

- Each staff user has **exactly one** of these eight roles in V1.
- Combining roles on one user is out of scope until this register is revised.
- Local bootstrap user (`admin@prooftag-catis.test`) receives `super_admin` at step 036.
- Super Administrator creates the other users; there is no public register.

## 5. Out of this step

| Item | Step |
|------|------|
| `spatie/laravel-permission` + Filament Shield | 036 (done — [local-permissions.md](../development/local-permissions.md)) |
| Permission seeder and panel gates | 036 (done) |
| Activity log | 037 (done — [local-audit.md](../development/local-audit.md)) |
| Filament module CRUD | 088+ |
| Per-role smoke test | 106 |

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Eight administrative roles recorded (step 035 executed) | 2026-10-20 |
