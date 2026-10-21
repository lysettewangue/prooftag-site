# Database logical model

| | |
|--|--|
| **Steps** | 039–055 (this file is filled at **051**) |
| **Principal entities** | [principal-entities.md](principal-entities.md) (039) |
| **Data dictionary** | [data-dictionary.md](data-dictionary.md) (052) |
| **Translation** | [ADR-001](../architecture/adr-001-translation-tables.md) (confirmed at 050) |

No migrations until step **055** is COMPLETED.

## Status

| Step | Work | Where |
|-----:|------|-------|
| 039 | Principal entities listed | [principal-entities.md](principal-entities.md) |
| 040 | Corporate attributes | [corporate-entities.md](corporate-entities.md) |
| 041–049 | Remaining per-area attributes | Not started |
| 050 | Parent + FR/EN translation tables | ADR-001 |
| 051 | ERD with cardinalities | This file |
| 052–054 | Dictionary, constraints, indexes | [data-dictionary.md](data-dictionary.md) |
| 055 | **Gate** | Approval, then 056 migrations |

The 022 relationship sketch stays in [sitemap-and-content-types.md](../ia/sitemap-and-content-types.md) until 051 replaces it here.
