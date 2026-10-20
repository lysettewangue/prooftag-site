# ADR-005: Filament 5 is admin presentation only

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

Filament 5 (+ Livewire 4) is the `/admin` UI. It must call application actions. It must not embed publication, translation, or redirect logic in resource classes.

## Reason

Same rules for public and admin; testable without the panel.

## Alternatives

- Filament as the only “backend” — rejected.
- Custom admin from scratch — slower, no gain for V1.

## Consequences

Policies and actions exist before Filament modules (steps 070–087 then 088).
