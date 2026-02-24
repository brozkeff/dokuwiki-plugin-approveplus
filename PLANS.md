# approveplus Modernization Plan

## Context

`approveplus` targets old `approve` plugin internals and now breaks on newer `approve` versions and PHP 8.x.

Primary reported failures (2026-02-24):
- Fatal: `Call to undefined method helper_plugin_approve_db::getDB()`
- Warning: `Undefined variable $false`

## Council Verdict (Developer/QA/DB, 3 rounds)

### Critical issues
1. Immediate breakage comes from legacy calls to removed method `helper_plugin_approve_db::getDB()`.
2. Confirmed runtime bug: `return $false;` in totalblock action.
3. Additional modernization debt:
- Legacy approve helper calls (`use_approve_here`, `client_can_see_drafts`, `find_last_approved`)
- Direct SQL against approve internals (`revision` table)

### PR #2 analysis (`142aefb4c063a4c5102b7f9aabc74efdf0e1127a`)
Useful idea:
- Add replacement tokens: `@APPROVE_DATE@`, `@REVISION@`, `@RFA@` (in addition to `@APPROVER@`)

Constraint:
- The patch is based on legacy DB access style and should be reimplemented using current approve helper APIs.

## Strategy

Use small atomic commits and test after each step.

### Phase 1: Runtime breakage fixes (first test gate)
- [x] Fix fatal `getDB()` usage in the code paths that currently crash runtime.
- [x] Fix `return $false` warning.
- [ ] Keep behavior changes minimal.

Test gate:
- [ ] PDF generation using `@APPROVER@` no longer fatals.
- [ ] No `$false` warning from totalblock path.

### Phase 2: Core compatibility modernization
- [ ] Migrate remaining legacy approve helper calls to current APIs (`approve_db`, `approve_acl`).
- [ ] Remove direct dependence on raw approve DB handle where possible.
- [ ] Add null-safe handling for missing user/revision metadata (PHP 8 strictness).

Test gate:
- [ ] Standard page view flow works with block/unapproved logic.
- [ ] No PHP warnings/notices in tested flows.

### Phase 3: Feature modernization from PR #2 (reimplemented)
- [ ] Implement `@APPROVE_DATE@`, `@REVISION@`, `@RFA@` tokens via modern helper API.
- [ ] Add language defaults and fallback behavior when data is unavailable.

Test gate:
- [ ] All 4 tokens resolve deterministically in PDF templates.

### Phase 4: Admin tool modernization
- [ ] Refactor admin batch approval to modern approve helper methods.
- [ ] Preserve existing admin UX while reducing schema coupling.

Test gate:
- [ ] Batch approve works and writes expected approval status.

## Commit Policy

- Atomic commits with clear scope.
- Start with breakage-only fixes so user can verify quickly.
- Update this file after each iteration.
