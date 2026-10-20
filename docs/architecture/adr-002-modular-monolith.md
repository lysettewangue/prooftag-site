# ADR-002: Modular monolith

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

One Laravel 13 application and one MySQL database. Not WordPress, not microservices.

## Reason

V1 is a corporate CMS plus public site on LWS Starter. One deploy unit matches shared hosting and the team size.

## Alternatives

- Keep WordPress — rejected (split sites, plugin debt).
- Microservices — rejected (ops cost, no SSH, no workers).

## Consequences

Domain modules are namespaces, not separate repos. Scale-out (VPS, Redis) waits on step 248.
