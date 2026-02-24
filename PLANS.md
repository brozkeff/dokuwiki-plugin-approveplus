# approveplus Modernization Plan

## Context

`approveplus` targeted old `approve` plugin internals and older DokuWiki
core APIs, which caused breakage on newer `approve` versions, DokuWiki core,
and PHP 8.x.

Primary reported failures (2026-02-24):

- Fatal: `Call to undefined method helper_plugin_approve_db::getDB()`
- Warning: `Undefined variable $false`

Additional reported failure (2026-04-10):

- Fatal: `Class "PageChangeLog" not found` in `action/totalblock.php`
  on DokuWiki 2025 "Librarian" with PHP 8.3

## Council Verdict (Developer/QA/DB, 3 rounds)

### Critical issues

1. Immediate breakage comes from legacy calls to removed method
   `helper_plugin_approve_db::getDB()`.
2. Confirmed runtime bug: `return $false;` in totalblock action.
3. Additional modernization debt:

- Legacy approve helper calls
  (`use_approve_here`, `client_can_see_drafts`, `find_last_approved`)
- Direct SQL against approve internals (`revision` table)

### PR #2 analysis (`142aefb4c063a4c5102b7f9aabc74efdf0e1127a`)

Useful idea:

- Add replacement tokens: `@APPROVE_DATE@`, `@REVISION@`, `@RFA@`
  (in addition to `@APPROVER@`)

Constraint:

- The patch is based on legacy DB access style and should be reimplemented
  using current approve helper APIs.

## Strategy

Use small atomic commits and test after each step.

### Phase 1: Runtime breakage fixes (first test gate)

- [x] Fix fatal `getDB()` usage in the code paths that currently crash runtime.
- [x] Fix `return $false` warning.
- [x] Keep behavior changes minimal.

Test gate:

- [ ] PDF generation using `@APPROVER@` no longer fatals.
- [ ] No `$false` warning from totalblock path.

### Phase 2: Core compatibility modernization

- [x] Migrate remaining legacy approve helper calls to current APIs
  (`approve_db`, `approve_acl`).
- [x] Remove direct dependence on raw approve DB handle where possible.
- [x] Add null-safe handling for missing user/revision metadata
  (PHP 8 strictness).
- [x] Restore `totalblock` compatibility with DokuWiki 2025/PHP 8.3 by
  supporting the namespaced core `PageChangeLog` API with legacy fallback.
- [x] Correct the `totalblock` ACL check and redirect handling for current
  DokuWiki/PHP behavior.

Test gate:

- [ ] Standard page view flow works with block/unapproved logic.
- [ ] No PHP warnings/notices in tested flows.
- [ ] Manual block/unblock flow is verified on both DokuWiki 2023 + PHP 7.4
  and DokuWiki 2025 + PHP 8.3.

### Phase 3: Feature modernization from PR #2 (reimplemented)

- [x] Implement `@APPROVE_DATE@`, `@REVISION@`, `@RFA@` tokens via modern
  helper API.
- [x] Add language defaults and fallback behavior when data is unavailable.

Test gate:

- [ ] All 4 tokens resolve deterministically in PDF templates.

### Phase 4: Admin tool modernization

- [x] Refactor admin batch approval to modern approve helper methods.
- [x] Preserve existing admin UX while reducing schema coupling.

Test gate:

- [ ] Batch approve works and writes expected approval status.

## Branch / Release Notes

- [x] Modernize branch contains the committed compatibility fix for
  `totalblock` (`5f26c25`).
- [ ] Decide whether to cherry-pick/copy the compatibility fix from
  `modernize` to local `master` before any broader branch reconciliation.
- [ ] Keep the pending upstream PR focused on changes that are suitable for
  upstream review; do not assume local branch-copy decisions should change the
  PR scope automatically.

## Commit Policy

- Atomic commits with clear scope.
- Start with breakage-only fixes so user can verify quickly.
- Update this file after each iteration.
