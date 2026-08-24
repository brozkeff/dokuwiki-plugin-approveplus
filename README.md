# DokuWiki-Plugin: Approve Plus

Additional features for the [approve-Plugin](https://www.dokuwiki.org/plugin:approve)

## Features

- Block showing content of pages which have no approved version
- Option to completely block pages independently from the approve system
- Add `@APPROVER@`, `@APPROVE_DATE@`, `@REVISION@`, and `@RFA@` template tags
  for the stock [dw2pdf plugin](https://www.dokuwiki.org/plugin:dw2pdf)
- Style modifications of the original

### Batch approve documents in a namespace

Plugin to quickly approve all documents in a namespace, for instance after
using the [move](https://www.dokuwiki.org/plugin:move) or
[batch edit](https://www.dokuwiki.org/plugin:batchedit) plugin. Navigate to the
admin section to choose the option.

This function is generally to be implemented in coming versions of the approve
plugin (see [issue #25](https://github.com/gkrid/dokuwiki-plugin-approve/issues/25)).
This function will then be removed.

## Compatibility

Tested with

- PHP **8.3** and **8.4.24** under `fpm-fcgi`
- DokuWiki **2025-05-14 "Librarian"** and **2026-07-14b "Mort"**
- Current [approve plugin](https://www.dokuwiki.org/plugin:approve) with the
  safe user-group handling from still-unmerged
  [approve pull request #59](https://github.com/gkrid/dokuwiki-plugin-approve/pull/59)
  applied
- Stock [dw2pdf plugin](https://www.dokuwiki.org/plugin:dw2pdf)
