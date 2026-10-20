# Environment architecture

| | |
|--|--|
| **Step** | 025 — Define environment architecture |
| **Parent** | [system-architecture.md](system-architecture.md) v1.0 |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

Three environments. Separate `.env` and databases. Never point local at production.

| Env | URL | Purpose | Indexing | Mail |
|-----|-----|---------|----------|------|
| Local | http://localhost (or Herd/Valet equivalent) | Development | n/a | Mailpit / Mailhog only |
| Staging | https://staging.prooftagcatis.com | LWS-like acceptance | `noindex` + basic auth | Staging SMTP or trap |
| Production | https://www.prooftagcatis.com | Live | Indexed after launch | LWS SMTP to real recipients |

## Promotion

Local (steps 026–186) → staging (187–205) → production (206–233). **No LWS work before step 186.** Staging acceptance (205) before production.

## Per-environment rules

| Concern | Local | Staging | Production |
|---------|-------|---------|------------|
| `APP_DEBUG` | true | false | false |
| `APP_ENV` | local | staging | production |
| Queue | sync | sync | sync |
| Cache / session | file or database | file or database | file or database |
| Scheduler | optional | LWS cron | LWS cron |
| Docroot | `public/` | `public/` | `public/` |
| Secrets | `.env` gitignored | panel env | panel env |

## Data

Seeds on local may be fake. Staging uses representative migrated content. Production uses the approved content set (step 208). Media disks are not shared across environments.

## Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 environment architecture (step 025 executed) | 2026-10-20 |
