# Maqwi verification — 18 October 2026

**Status: preparation advanced; NOT READY TO ACCEPT SALES. Public ordering remains blocked. This is not launch approval.**

## Installed and verified

InfinityFree account `if0_43106867` is active. Existing FTPS credentials in GitHub Actions work. No additional hosting login is needed.

The existing premium storefront was preserved. A read-only snapshot of 16 owned source files reconciled live source with Git history before any replacement. Live theme files matched the newer main history; the hardened live plugin was retained as the baseline rather than overwritten by older staging content.

Plugin 0.3.3 source was installed using a four-file allowlist, verified hashes, encrypted FTP transport, backups retained before writes, temporary upload/readback/rename, and verified final readback. No database setup request was included. Main branch was not merged or deployed.

- Exact tested source commit: `e8c4398fb1721acfc908282b24c2e4295178cb5f`.
- Release marker commit: `3534aae98c6ed46e5b5d5459c1f4aa4a51bd03a6`.
- [Successful source installation and live acceptance](https://github.com/maqwiorganics/maqwiorganics/actions/runs/39129991670).
- Original-source artifact: `maqwi-commerce-source-originals-39129991670`, artifact ID 12137234386, 30-day retention. The reconciled baseline also remains in repository history.
- Public acceptance artifact: `maqwi-commerce-source-health-39129991670`, artifact ID 12137252907, 7-day retention.

At 13:05–13:06 UTC the live homepage, shop, Honey, Ghee, Itar and Achaar routes returned HTTP 200. Normal Chrome at 390 × 844 checked horizontal overflow, a single main heading and JavaScript page errors. Store API checkout GET returned HTTP 403 with the closed-order guard. All checks passed. No live order was submitted. This is limited mobile and availability evidence, not complete desktop/mobile checkout acceptance.

## Native commerce and recovery tests

[Successful isolated native test run](https://github.com/maqwiorganics/maqwiorganics/actions/runs/39129917887): **81 native commerce assertions and four source deployment/recovery tests passed.**

The runner installed official WordPress 7.1.3 and WooCommerce 11.2.0, matching inspected live source, with MySQL 8.4 and MyISAM tables matching the production storage engine. Its fixture contains only sanitized public product/page content and approved catalog metadata. It contains no production database dump, customer records, user credentials or real mail key.

Covered:
- Refusal if original stock or catalog preconditions have changed; one-time configuration and repeat-run stock preservation.
- Approved product sizes/prices, managed opening stock, preserved legacy private variations, and Achaar shared parent inventory.
- Native Pakistan-only PKR 300 flat shipping per order, including a multi-item cart and exclusion outside Pakistan.
- Native order totals; shared stock reduction; repeated reduction protection; cancellation stock restoration.
- Two concurrent reservations of three Achaar units against five available units: only one succeeds, and released reservations return held stock to zero.
- Closed-order enforcement in classic checkout, Store API and native order creation; stored payment methods disabled by the tested setup.
- Approved content revisions, branded WooCommerce email rendering and optional authenticated SMTP adapter configuration.
- Successful allowlisted source install, failures during each new-module upload, and rollback after post-install failure.

SMTP tests use a fake CI credential and intercept mail. **No real customer/admin email delivery is established by these tests.** Source rollback tests do not establish production database restoration.

## Production configuration: prepared, not applied

The installed configuration module remains dormant. Its explicit one-time request option has not been set. It checks the existing catalog, stock, page hashes, no-order condition and closed-order flags before applying native WooCommerce changes. It records originals and uses a lock; a failed setup keeps ordering closed rather than automatically replaying a partial change.

| Product | Last authenticated live observation | Approved setup, passed in isolation |
| --- | --- | --- |
| Honey | 1 kg, PKR 4,500, variation 62, stock 0 | Stock 20 |
| Desi Ghee | 1 kg, PKR 3,500, variation 63, stock 0 | Stock 15 |
| Itar | 12 ml, PKR 200, variation 64, stock 0 | Stock 10 |
| Achaar | Parent 32; old private variations, no active approved sizes | 500 g PKR 1,500; 1 kg PKR 3,000; shared stock 5 |
| Shipping | No native zones or methods | Pakistan zone; PKR 300 per order |

Those live observations precede the source-only deployment. The deployment did not request these database changes.

Other last authenticated observations: PKR currency; Pakistan selling/shipping restrictions; taxes disabled; zero HPOS and legacy orders; `maqwi_brand_launch=yes`; `maqwi_storefront_preview=yes`; search indexing off. Stored COD/BACS settings were enabled, but the public guard suppresses gateways. The tested setup explicitly disables stored COD, BACS and cheque as well. Do not remove the guard or call stored payment configuration disabled before that setup is applied and verified.

Prepared content uses only approved facts: Ali Town, Lahore, Punjab, Pakistan; 03171935828; info@maqwiorganics.com; approved prices and PKR 300 shipping. No invented ingredients, fragrance, courier timings, tax rules or return windows were added.

## Backups and restore gate

Complete existing Updraft sets dated 11 and 17 October remain untouched, including database, plugins, themes, uploads and others. A deny rule is present in the backup directory. Presence does not establish complete archive integrity or recoverability.

A fresh production database export was obtained through native phpMyAdmin:
- Gzip size: 287,755 bytes; decompressed SQL: 2,044,736 bytes.
- 55 CREATE TABLE statements; terminal COMMIT present; gzip integrity checked.
- Gzip SHA-256: `1591d1f8271a7e4afb49cff9d39d741cdf652200cb6c98b140359e4727311d5b`.
- Encrypted with the existing recovery recipient certificate using CMS/AES-256 and saved as `maqwi-precompletion-db-20261018.sql.gz.cms`.
- Requires the existing `Maqwi-Recovery-Key.pem`; no recovery private key or raw export was committed to GitHub.

A separate empty native database, `if0_43106867_maqwiqa`, was created for restoration testing. **The browser approval gate declined the backup upload to php-myadmin.net. Import and restore verification have not run.** No alternate upload or execution route was used to evade that decision. Production database configuration remains paused pending an authorized restore test.

The fresh export is a database backup, not a newly verified full file backup. Existing complete Updraft sets are preserved; full file archive integrity and a demonstrated restore remain release work.

## Transactional email account decision

The accessible Resend session contains only the `abdulrehman.ai.dev` workspace, with no domains configured. Ownership/DNS control of `maqwiorganics.com` is not verified. No domain, DNS records, sender credential or API key was created.

An optional server-side Resend SMTP adapter is installed: smtp.resend.com, port 587, STARTTLS, certificate verification defaults, debug off, fixed configured sender. It remains inactive without a server-side API key and verified From address. No credential is exposed to storefront code.

Needed: confirmation that this is the intended Resend workspace (or access to the Maqwi workspace), plus an owned sender domain whose DNS can be verified. Real customer/admin delivery and failure handling must then be tested. The stored Gmail From address alone does not establish deliverability.

## Security and operating limits

- HTTPS verified in the earlier independent read-only run; certificate expiry observed as 29 November 2026. Certificate renewal and recovery must remain operational responsibilities.
- Source transport uses certificate-verified FTPS; secrets remain in existing GitHub secret storage; deployment changes only owned files and does not mirror/delete unrelated content.
- Existing guards remain active, and no payments, public launch, domain attachment or DNS change was performed.
- Cloud Browser WordPress administration still shows Site Unavailable; independent public Chrome checks pass. This does not prove a public outage or a particular cause. Native hosting tools work; WPVibe connection did not establish usable REST authentication.
- Action Scheduler showed 123 complete, one failed and 14 pending. The failed hook and due dates still need inspection; pending does not necessarily mean overdue.
- Complete security review, full file-restore drill, account/password workflows, all mobile/desktop layouts, customer/admin email delivery, hosted checkout totals/stock lifecycle and fulfillment operations remain unverified.

## Completion sequence after the blocked decisions

1. With fresh explicit authorization, upload the fresh export only to the isolated `if0_43106867_maqwiqa` database; verify restore counts and representative records without touching production.
2. Verify preserved file archives and a recoverable full-site baseline. Resolve any scheduler or security findings.
3. Recheck production catalog/orders and apply the already tested native setup once, keeping public ordering and all payment methods off.
4. Connect an approved verified Resend sender with server-side credentials; verify actual customer/admin messages and failure behavior.
5. Complete hosted storefront, cart, checkout, shared stock, cancellation, account, mobile/desktop and fulfillment acceptance using controlled tests while the public remains blocked.
6. Produce a sales-readiness report with pass/fail evidence and rollback instructions. Obtain the owner's separate final confirmation before enabling any payment method or public ordering.

No statement in this report authorizes public launch.
