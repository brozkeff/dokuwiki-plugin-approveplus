# DokuWiki Plugin: Approve Plus (Fork)

`approveplus` extends the [approve plugin](https://www.dokuwiki.org/plugin:approve) with extra approval workflow features.

This repository is a maintained fork of the original upstream plugin from **2020-11-23** (author: Gero Gothe), modernized for current DokuWiki and newer `approve` plugin internals.

## Fork status

- Fork maintainer: **Martin Malec**
- Base upstream snapshot: **2020-11-23**
- Current fork line: **2026-02-24a1**
- Main goal: keep original behavior while restoring compatibility with current systems

## Features

- Block page display when no approved revision exists
- Manually block/unblock page display independent of approval state
- Add `@APPROVER@` replacement tag for `dw2pdf` templates
- Namespace batch-approval tool in admin area

## dw2pdf integration

This fork is designed to work with **stock `dw2pdf` plugin**.

- No custom/forked `dw2pdf` plugin is required
- You only need a PDF template that uses the replacement tags (for example `@APPROVER@`)

## Compatibility

Tested/targeted compatibility:

- PHP: **7.4 to 8.x**
- DokuWiki: **2023-04-04a** and newer (targeting current releases)
- Approve plugin: **2026-02-19** version line (current helper API style)
- dw2pdf: **stock plugin**, template customization only

## Notes on modernization

The fork removes breakages caused by API drift between old `approveplus` code and newer `approve` plugin versions (for example removed legacy DB accessor methods).

Further modernization is tracked in `PLANS.md` and is delivered in small, testable iterations.
