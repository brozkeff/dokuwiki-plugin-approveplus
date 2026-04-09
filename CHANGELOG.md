<!-- markdownlint-configure-file {"MD024": false} -->
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [2026-04-10]

### Fixed

- Restored `totalblock` compatibility with DokuWiki 2025 "Librarian" and
  PHP 8.3 by supporting the namespaced core `PageChangeLog` class while
  keeping legacy fallback for older DokuWiki releases.
- Corrected the total block ACL check so block and unblock actions require
  delete permission reliably on modern PHP.
- Switched the total block action redirect to `send_redirect()` after
  saving, avoiding continued request processing on newer DokuWiki releases.
