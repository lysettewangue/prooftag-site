# Source control rules

| | |
|--|--|
| **Step** | 028 — Define source control rules |
| **Parent** | [git-repository.md](git-repository.md) |
| **Next** | 042 — Define expertise entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

Files: [`.gitignore`](../../.gitignore), [`.gitattributes`](../../.gitattributes). PHP naming remains in [standards.md](standards.md).

## 1. What never enters Git

- `.env` and any file with live secrets (`auth.json`, keys, PEM, credential JSON)
- `vendor/`, `node_modules/`
- Laravel compiled views, caches, logs, and `public/build` (built on CI or locally at release)
- Local SQL dumps and editor/OS junk

`.env.example` is committed. It must contain **names** of variables, not production values.

## 2. Branches

| Branch | Role |
|--------|------|
| `main` | Default. Receives only reviewed, sequential work. |
| `develop` | Integration line (created at this step). |
| `feature/<short-name>` | One step or one feature |
| `fix/<short-name>` | Defect against current `main`/`develop` |

Do not force-push `main`. Do not rewrite published history once a private remote exists.

Work on `main` is allowed until a remote and review process exist; then land via `develop` or short-lived feature branches.

## 3. Commit messages

Conventional Commits, imperative, English:

| Prefix | Use |
|--------|-----|
| `feat:` | New behaviour |
| `fix:` | Defect |
| `docs:` | Documentation only |
| `chore:` | Tooling, ignore rules, deps with no product change |
| `refactor:` | Same behaviour, different structure |
| `test:` | Tests only |
| `ci:` | Pipeline |

Examples: `docs: freeze source-control rules`, `feat: add product catalogue`. Body optional; say **why** when the subject is not enough.

One logical change per commit when practical. Do not skip hooks (`--no-verify`) unless the user explicitly asks.

## 4. Line endings

Repository stores **LF**. `.gitattributes` sets `eol=lf` except `.bat`/`.cmd` (CRLF).

## 5. Completion of step 028

`.gitignore`, `.gitattributes`, this file, and branch `develop` exist.

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen source-control rules (step 028 executed) | 2026-10-20 |
