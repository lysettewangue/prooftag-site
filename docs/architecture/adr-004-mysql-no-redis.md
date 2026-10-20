# ADR-004: MySQL; no Redis or specialist search in V1

**Status:** Accepted  
**Date:** 2026-10-20  
**Step:** 023

## Decision

MySQL 8 is the only data store. Cache and sessions are `file` or `database`. Public search is MySQL. No Redis, Horizon, Meilisearch, or Elasticsearch.

## Reason

LWS Starter has no managed Redis. Centre and article volumes fit SQL with indexes.

## Alternatives

- Redis — rejected until a documented scale trigger.
- Hosted search — extra cost and ops.

## Consequences

Index for real queries at step 054. Revisit at step 248.
