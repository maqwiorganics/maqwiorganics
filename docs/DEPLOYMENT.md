# Deployment — Maqwi Organics

## Verified connections

GitHub staging and read-only FTPS diagnostics work. FTPS host `ftpupload.net`, port 21, document root `/htdocs`; password is held only in GitHub Actions secrets. WordPress access uses the owner's saved TinyFish browser profile; sessions can expire. There is no automatic website deployment.

## Scope

Deploy only reviewed files beneath `wp-content/themes/maqwi-organics/` into `/htdocs/wp-content/themes/maqwi-organics/`. Never mirror/delete or sync the WordPress root. Do not commit credentials, wp-config.php, database backups, customer information, private documents or purchased theme archives.

## Required before first installation

Confirm restorable database and site-file backups, independent private copy and rollback route. Install official Kadence parent and verify its version. Run theme validation, then manually deploy the reviewed staging commit. User has authorized development on the temporary site; real domain, DNS and public launch require separate authorization.

Keep store Coming soon and indexing disabled. No production orders while email, checkout, background tasks and restore tests remain unverified.

## Rollback

Re-activate Twenty Twenty-Five through Appearance > Themes. This child theme performs no database migrations or automatic settings changes. If admin is broken, rename only the child theme directory via FTPS and restore the prior active-theme option using the secure database recovery route. Restore backup for unrelated configuration/plugin problems. Keep the parent and previous theme until validation completes.

## Validation

Automated workflow validates PHP syntax, JSON and asset budgets. Runtime/editor validation still required on the temporary WordPress site. Host REST and loopback restrictions are known; do not attempt to bypass them or rely on inbound webhook integrations.
