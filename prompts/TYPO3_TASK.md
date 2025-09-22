# Cursor Task: TYPO3 Connector (skeleton)

## Goal
Provide a barebones TYPO3 extension that posts webhooks to the API on record/page changes.

## Steps
1. `ext_emconf.php`, `ext_localconf.php`, `composer.json` for extension.
2. PSR-4 namespace `PixelCoda\HeadlessSearchConnector`.
3. Hook into DataHandler to detect create/update/delete.
4. Send HMAC-signed POST to `/v1/index/:project/:collection` with mapped fields.
5. CLI `bin/console pc:search:index --project=UUID` for full reindex (stub ok).

## Done When
- Installing the extension triggers a webhook on page save (log success).

> Note: Provide `.env` for API URL + KEY + HMAC secret.
