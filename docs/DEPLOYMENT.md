# Deployment — Maqwi Organics

## Verified connections

GitHub staging and read-only FTPS diagnostics work. FTPS host `ftpupload.net`, port 21, document root `/htdocs`; password is held only in GitHub Actions secrets. WordPress access uses the owner's saved TinyFish browser profile; sessions can expire. Ordinary source changes do not deploy automatically. Installation/refinement maintenance workflows run when their own reviewed workflow files change on staging. Each checks its target and expected existing hashes.

## Scope

The normal deployment scope is reviewed files beneath `wp-content/themes/maqwi-organics/` into `/htdocs/wp-content/themes/maqwi-organics/`. Never mirror/delete or sync the WordPress root. Do not commit credentials, wp-config.php, database backups, customer information, private documents or purchased theme archives.

## Guarded configuration exception

On 11 October, the native visitor cron workflow enabled only ALTERNATE_WP_CRON on the temporary site. It verifies the original config against the independently backed-up hash, syntax checks privately, verifies upload and atomically renames. Configuration content is never logged or committed. No external cron, host challenge bypass or root synchronization is used. Six previously blocked actions completed after normal browser visits; the migration notice cleared. Keep this supported native visitor mode enabled while monitoring reliability. It depends on traffic and is not proof of reliable unattended scheduling.

To roll back this exact flag, change enable = True to enable = False in the reviewed native-visitor-cron workflow and commit that workflow alone to staging. The workflow removes only its exact inserted line and verifies the original hash; it aborts on unrelated config differences. Verify homepage/admin and the workflow result afterward.

## Installation baseline

The full UpdraftPlus baseline and independent encrypted copy were created and integrity-verified before installation. Official Kadence and the custom child theme are installed; theme validation and upload verification passed. Full application restore on a separate environment is still required before launch. User has authorized development on the temporary site; real domain, DNS and public launch require separate authorization.

Keep store Coming soon and indexing disabled. No production orders while email, checkout, background tasks and restore tests remain unverified.

## Rollback

Re-activate Twenty Twenty-Five through Appearance > Themes. This child theme performs no database migrations or automatic settings changes. If admin is broken, rename only the child theme directory via FTPS and restore the prior active-theme option using the secure database recovery route. Restore backup for unrelated configuration/plugin problems. Keep the parent and previous theme until validation completes.

## Validation

Automated workflow validates PHP syntax, JSON and asset budgets. Runtime/editor validation still required on the temporary WordPress site. Host REST and loopback restrictions are known; do not attempt to bypass them or rely on inbound webhook integrations.
