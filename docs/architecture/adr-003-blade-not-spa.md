# ADR-003: Blade, not a public SPA

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

Public UI is server-rendered Blade + Tailwind 4. Alpine.js only where it helps (nav, filters). No Inertia, Vue, React, or Next public app.

## Reason

SEO, LWS (no Node server), and simpler caching.

## Alternatives

- Headless Next on LWS — no native Node on Starter.
- Inertia/Vue — extra JS, weaker default SEO, unnecessary.

## Consequences

Interactivity is progressive. Centre map uses Leaflet on the page, not an SPA route.
