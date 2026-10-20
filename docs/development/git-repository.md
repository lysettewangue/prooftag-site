# Git repository

| | |
|--|--|
| **Step** | 027 — Create the private Git repository |
| **Next** | 029 — Create the Laravel 13 project |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Local `main` exists |

## 1. Local repository

| Item | Value |
|------|--------|
| Path | `d:\websiteprooftag` |
| Default branch | `main` |
| Visibility | Local only until a private remote is attached |
| Laravel app | Not in this repo yet (step 029) |

Ignore rules and commit-message conventions: [source-control.md](source-control.md) (step 028). Quality hooks are step **038**.

## 2. Private remote (not attached)

Cursor Origin (`origin.cursor.com`) is **not supported on native Windows**. This machine has no WSL distribution. `gh` is not installed.

Do **not** push this proprietary codebase to a public host. Attach a **private** remote on the organisation’s GitHub or GitLab (or Origin from WSL later). Until then, `main` stays local.

Suggested remote name: `prooftag-catis` (or `websiteprooftag`). Suggested extra branch after 028: `develop`.

## 3. Completion of step 027

Step 027 is complete when `git status` reports branch `main` in this workspace.

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Local repository on `main` (step 027 executed) | 2026-10-20 |
