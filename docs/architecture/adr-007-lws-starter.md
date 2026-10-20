# ADR-007: LWS Starter until a documented scale trigger

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

V1 production is LWS Starter shared hosting. VPS only after step 248 with a real trigger (quota, security, or unavailable runtime).

## Reason

Fits PHP/MySQL, cron, SMTP, and the expected traffic of a corporate site.

## Alternatives

- VPS from day one — extra cost without a trigger.
- Docker production — not available as the Starter runtime.

## Consequences

Docroot `public/`, no SSH, web terminal deploy, prebuilt assets. Design must stay within memory and connection quotas.
