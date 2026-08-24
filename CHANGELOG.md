<!-- markdownlint-configure-file {"MD024": false} -->
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [2026-08-24]

### Fixed

- Resolved `@RFA@` for ready-for-approval revisions instead of limiting the
  replacement to revisions that were already approved.
- Confirmed the `dw2pdf` replacement flow on DokuWiki 2026-07-14b "Mort"
  with PHP 8.3 and PHP 8.4.24 under `fpm-fcgi`, including the
  ready-for-approval user output.
- Retained the previously confirmed compatibility with DokuWiki 2025-05-14
  "Librarian" and PHP 8.3.

### Changed

- Documented that the additional `dw2pdf` replacements from the surviving
  `ooleanderoo` fork were reimplemented with the current `approve` helper API.

## [2026-04-10]

### Fixed

- Migrated the admin namespace batch-approval flow to current `approve`
  helper APIs, removing legacy raw DB access that no longer works with
  newer `approve` plugin versions.
- Restored `totalblock` compatibility with DokuWiki 2025 "Librarian" and
  PHP 8.3 by supporting the namespaced core `PageChangeLog` class while
  keeping legacy fallback for older DokuWiki releases.
- Corrected the total block ACL check so block and unblock actions require
  delete permission reliably on modern PHP.
- Switched the total block action redirect to `send_redirect()` after
  saving, avoiding continued request processing on newer DokuWiki releases.

## [2026-02-24]

### Fixed

- Restored runtime compatibility with the current `approve` helper API and
  removed the known `$false` warning path from the plugin.
- Improved `dw2pdf` replacement output by restoring the `@APPROVER@` prefix
  text and formatting `@APPROVE_DATE@` as a full datetime value.

### Added

- Added `dw2pdf` template replacements for `@APPROVE_DATE@`, `@REVISION@`,
  and `@RFA@` using the modernized helper-based implementation.

### Changed

- Rewrote the fork metadata and README to document maintained-fork status,
  current compatibility targets, and stock `dw2pdf` usage.
- Added and updated the modernization plan to track the branch work in small,
  testable steps.
