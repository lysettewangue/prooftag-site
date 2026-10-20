# Data dictionary

Filled at step 052, before the design review at step 055.

For each table record: column, type, nullability, purpose, validation, relationships.

Parent design: [erd.md](erd.md). Translation pattern: [ADR-001](../architecture/adr-001-translation-tables.md).

## Rules already fixed

- Corporate contact lives in **settings**, not copied per page.
- Translated slugs are unique per `(locale, slug)`.
- Business content uses an **archive/status** field. Soft deletes are for recovery, not for “hidden but published”.
- Media rows store metadata and path, not the binary.
- Equipment keeps a current/legacy status instead of hard-deleting replaced models.
