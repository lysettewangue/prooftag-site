# Backend layers

| | |
|--|--|
| **Step** | 024 — Define backend layers |
| **Parent** | [system-architecture.md](system-architecture.md) v1.0 |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

Four layers. Dependencies point inward. Domain has no Laravel HTTP/Filament imports.

| Layer | Lives in | May do | Must not do |
|-------|----------|--------|-------------|
| Presentation | `Http\Controllers`, Filament resources, Blade, Livewire | HTTP, forms, rendering | Status transitions, queries that bypass policies |
| Application | `Actions`, `Queries`, ViewModels | One use case per class; call domain + persistence | HTML; ad-hoc SQL in controllers |
| Domain | `App\Domain\*` | Publication, visibility, centre verification, translation resolution rules | I/O, facades for HTTP |
| Infrastructure | Eloquent, filesystem, SMTP, cache, media conversions | Persist and send | Invent business rules |

## Domain modules (namespaces reserved)

| Module | Namespace |
|--------|-----------|
| Corporate | `App\Domain\Corporate` |
| Products | `App\Domain\Products` |
| Expertise | `App\Domain\Expertise` |
| TechnicalInspection | `App\Domain\TechnicalInspection` |
| Centres | `App\Domain\Centres` |
| Editorial | `App\Domain\Editorial` |
| Resources | `App\Domain\Resources` |
| Communication | `App\Domain\Communication` |
| Seo | `App\Domain\Seo` |
| Media | `App\Domain\Media` |

Application services are specified at steps 070–087. This step only locks the **layering**. There is **no public API** in V1.

## Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 layer specification (step 024 executed) | 2026-10-20 |
