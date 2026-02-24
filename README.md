# DokuWiki Plugin: Approve Plus (Fork)

`approveplus` extends the
[approve plugin](https://www.dokuwiki.org/plugin:approve) with extra
approval workflow features.

This repository is a maintained fork of the original upstream plugin from
**2020-11-23** (author: Gero Gothe), modernized for current DokuWiki and
newer `approve` plugin internals.

## Fork status

- Fork maintainer: **Martin Malec**
- Base upstream snapshot: **2020-11-23**
- Current fork line: **2026-04-10**
- Main goal: keep original behavior while restoring compatibility with current systems

Current status: implementation is in place and **validation is in progress** (see `PLANS.md` test gates).
See also: `CHANGELOG.md` for alpha-by-alpha change history.

## Features

- Block page display when no approved revision exists
- Manually block/unblock page display independent of approval state
- Add replacement tags for `dw2pdf` templates:
  - `@APPROVER@`
  - `@APPROVE_DATE@`
  - `@REVISION@`
  - `@RFA@`
- Namespace batch-approval tool in admin area

## dw2pdf integration

This fork is designed to work with **stock `dw2pdf` plugin**.

- No custom/forked `dw2pdf` plugin is required
- You only need a PDF template that uses the replacement tags

### Replacement token guide

Available tokens from `approveplus` in `dw2pdf` templates:

- `@APPROVER@`: full name of approving user, or fallback text if not approved
- `@APPROVE_DATE@`: approval date and time formatted as
  `YYYY-MM-DD HH:MM:SS`, or fallback `-`
- `@REVISION@`: approval version from `approve` DB, or fallback `-`
- `@RFA@`: full name of user who marked page ready-for-approval, or fallback `-`

Simple dw2pdf footer example:

```html
<td style="text-align: center">@APPROVER@ (@UPDATE@)</td>
```

Extended footer example:

```html
<td style="text-align: center">@APPROVER@ / RFA: @RFA@ /
@APPROVE_DATE@ | Revision: @REVISION@ | Updated: @UPDATE@</td>
```

You can use the same line in both `footer_even.html` and `footer_odd.html`.

Token extension is inspired by upstream
PR #2:
<https://github.com/practical-solutions/dokuwiki-plugin-approveplus/pull/2>

## Compatibility

Tested/targeted compatibility:

- PHP: **7.4 to 8.x**
- DokuWiki: **2023-04-04a** and newer (targeting current releases)
- Approve plugin: **2026-02-19** version line
  (current helper API style)
- dw2pdf: **stock plugin**, template customization only

Compatibility note for recent systems:

- DokuWiki **2025 "Librarian"** with PHP **8.3** is supported by the
  current fork line, including the `totalblock` action path that now uses
  the namespaced core `PageChangeLog` API with a legacy fallback for older
  DokuWiki releases.

## Configuration

Default options from `conf/default.php`:

- `show_blockbutton` (default `1`): show the block/unblock page button in the
  page menu for editable pages.
- `block_unapproved` (default `1`): enable blocking pages that have no
  approved revision.

## Notes on modernization

The fork removes breakages caused by API drift between old `approveplus`
code and newer `approve` plugin versions
(for example removed legacy DB accessor methods).

The admin namespace batch-approval tool has also been migrated away from
legacy raw DB access to the current `approve` helper APIs.

The `2026-04-10` release also fixes the historical `totalblock`
implementation, which previously assumed the old global `PageChangeLog`
class and failed on newer DokuWiki core releases.

Further modernization is tracked in `PLANS.md` and is delivered in small,
testable iterations.
