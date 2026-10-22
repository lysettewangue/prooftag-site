# Application services catalog

Actions for steps 073–081. Each action is one class. Authorization is a policy, not a hidden button.

## PublicationService

| Method | Description |
|--------|-------------|
| `transition($entity, PublicationStatus $status, User $user)` | Validates allowed transition matrix |
| `publishNow($entity, User $user)` | Sets published_at to now |
| `schedule($entity, Carbon $when, User $user)` | Requires future datetime |
| `archive($entity, User $user)` | Removes from public indexes |

## TranslationService

| Method | Description |
|--------|-------------|
| `translationFor($entity, string $locale): ?Translation` | Fetch row |
| `missingLocales($entity): array` | For admin widget |
| `hreflangUrls($entity): array` | For meta tags |
| `resolveSlug(string $type, string $locale, string $slug): ?Model` | Public resolver |

## ProductCatalogService

| Method | Description |
|--------|-------------|
| `publishedForLocale(string $locale): Collection` | Ordered products |
| `findBySlug(string $locale, string $slug): Product` | Throws 404 |

## CentreDirectoryService

| Method | Description |
|--------|-------------|
| `paginatePublic(CentreFilter $filter)` | Region, search, status=active |
| `verify(Centre $centre, User $user)` | Sets active + timestamp |
| `importRow(array $data): Centre` | Always unverified |

## ResourceLibraryService

| Method | Description |
|--------|-------------|
| `paginatePublic(ResourceFilter $filter)` | Type, regulatory_status |
| `download(Resource $resource): StreamedResponse` | Authorised files only |

## ContactSubmissionService

| Method | Description |
|--------|-------------|
| `submit(ContactData $data, Request $request): ContactRequest` | Spam checks |
| `updateStatus(ContactRequest $cr, ContactStatus $status, User $user)` | Admin inbox |

## RedirectResolver

| Method | Description |
|--------|-------------|
| `resolve(string $host, string $path): ?Redirect` | DB lookup |
| `logMiss(string $host, string $path): void` | Optional table |

## SeoMetaResolver

| Method | Description |
|--------|-------------|
| `forPage(string $locale, string $path): SeoMeta` | Defaults + overrides |
| `forModel(Translatable $model, string $locale): SeoMeta` | Entity pages |

## MediaAttachmentService

| Method | Description |
|--------|-------------|
| `attach(HasMedia $model, UploadedFile $file, string $collection)` | Validates |
| `safeDelete(Media $media): void` | Blocks if in use |

## Policy matrix (confirmed at 106)

| Permission | Super Admin | Admin | Content Mgr | Tech Editor | Comms | Translator | Reviewer | Support |
|------------|-------------|-------|-------------|-------------|-------|------------|----------|---------|
| publish | ✓ | ✓ | | | ✓ | | ✓ | |
| translate | ✓ | ✓ | ✓ | | | ✓ | | |
| verify_centre | ✓ | ✓ | | ✓ | | | | |
| manage_redirects | ✓ | ✓ | | | | | | |
| contact_inbox | ✓ | ✓ | | | ✓ | | | ✓ |

Module GET 200/403 matrix: [local-admin-permissions-gate.md](../development/local-admin-permissions-gate.md). Super Admin `Gate::before` still cannot create extra frozen catalogue rows or hard-delete no-delete entities.
