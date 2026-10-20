# ADR-006: Synchronous queue; LWS cron for light work

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

`QUEUE_CONNECTION=sync`. Scheduled work is `php artisan schedule:run` via PHP cron, jobs under ~4 minutes.

## Reason

No persistent workers on Starter. HTTP cron may fail behind Cloudflare.

## Alternatives

- Horizon / Redis workers — out of V1.
- HTTP cron hitting a route — fragile with CDN.

## Consequences

No long media transcodes. Scheduled publish is a short Eloquent update.
