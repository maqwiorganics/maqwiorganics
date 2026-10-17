# Hosting audit — 16 October 2026

## Scope and evidence

Read-only review of the authenticated InfinityFree dashboard/control panel and an independent FTPS/TLS audit. Temporary hostname only: `maqwiorganics.gt.tc`. No production domain, DNS, database, account credentials or hosting configuration was changed.

Hosting audit workflow **38884774437** passed. Independent public browser QA **38874056924**, rerun job **116912822791**, passed all 72 page/width checks after the owner's 502 report. This proves availability from that test location during that run, not universal availability or the cause of the reported 502.

## Verified hosting state

| Check | Result |
| --- | --- |
| Account | Active, Free plan; no recorded deactivation events |
| Disk usage | 345 MB of 5 GB |
| Inodes | 19,642 of 80,000 |
| Daily hits | 1,754 of 50,000 at inspection |
| Bandwidth | 407 MB, displayed allocation unlimited |
| Resource charts | CPU below the displayed limit; no plotted entry-process failures; I/O and RAM charts at baseline |
| Provider domain health | No issues found |
| PHP error display | Off |
| PHP directive timezone | America/New_York; not changed. WordPress previously verified Asia/Karachi; current application setting not reverified |
| TLS | Certificate verification passed, TLS 1.3; `*.gt.tc` covers the hostname; expires 29 November 2026 at 23:59:59 UTC |
| Certificate dashboard | No individually issued certificates; the verified provider wildcard supplies HTTPS |
| Homepage routing | WordPress bootstrap present, no `index.html`, `index.htm` or `index2.html` placeholders; HTTPS condition and WordPress rewrite rules present |
| Directory listing | No explicit `Options -Indexes` in root `.htaccess`; listing exposure not established, so no speculative root change made |
| Deployed Maqwi source | Six audited theme/plugin source files exactly match reviewed staging bytes |
| WordPress source version | 7.1.3 from installed `wp-includes/version.php`; database schema state not inspected |
| Plugin source versions | WooCommerce 11.2.0, UpdraftPlus 1.26.8, W3 Total Cache 2.10.7; these are source-file versions, not a complete active-plugin inventory or vulnerability assessment |
| Backup files | Components labelled database, plugins, themes, uploads and others present dated 11 October; newer backups not observed |
| Backup protection | Updraft directory has a deny-access rule |

Daily charts are not real-time resource telemetry. File metadata does not prove backup completeness, successful restoration or off-site retention. No managed host backup service is provided by the control panel; the owner must maintain independent backups.

## Access limitations

The Cloud Browser still receives a generic Site Unavailable page for WordPress admin, while independent Chrome QA reaches the public store. No bot-detection cause is asserted for that generic error. Softaculous initially reported one installation and zero outdated installations, but its installation-management link then returned The login is invalid; no retries or credential substitution were attempted.

Automatic approval review rejected opening the control panel's linked `php-myadmin.net` service because it would access the private WordPress database through an external destination whose trust and scope were not established by the hosting-diagnostics authorization. No database query, indirect database access or workaround was attempted. Owner approval of this particular endpoint is needed before another attempt.

Consequently current scheduled-action counts, database schema/health, active plugin/update status, current Site Health, fresh native backups, outbound email delivery and a restore rehearsal remain unverified. No fabricated repair or resolution of the intermittent 502 is claimed.

## Launch requirements

1. Establish approved admin/database access; inspect pending/failed actions and schema status without running unknown jobs.
2. Take and independently retain a fresh full backup, then verify restoration on a separate environment before accepting orders. The retained encrypted 11 October baseline predates the current native catalogue.
3. Supply real stock, Achaar recipe/size/price, final label/ingredient/allergen information, business/support details, delivery tariff and approved policies.
4. Verify authenticated transactional email and a controlled end-to-end order/payment/delivery test before releasing the existing sales guard.

The 502's cause is unresolved. Account suspension and displayed storage/hit exhaustion were not supported by the observed evidence. No server restart, PHP-version change, security weakening or paid upgrade was justified by these checks.
