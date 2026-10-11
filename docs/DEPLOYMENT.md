# Deployment — Maqwi Organics

## Verified connections

GitHub staging, FTPS diagnostics and guarded source deployment work. FTPS host `ftpupload.net`, port 21, document root `/htdocs`; password is held only in GitHub Actions secrets. WordPress access uses the owner's saved TinyFish browser profile; sessions can expire. Ordinary source changes do not deploy automatically. Installation/refinement maintenance workflows run when their own reviewed workflow files change on staging. Each checks its target and expected existing hashes.

## Scope

The normal deployment scope is reviewed files beneath `wp-content/themes/maqwi-organics/` into `/htdocs/wp-content/themes/maqwi-organics/`. Never mirror/delete or sync the WordPress root. Do not commit credentials, wp-config.php, database backups, customer information, private documents or purchased theme archives.

The commerce phase additionally installs/refines only the site-owned `wp-content/plugins/maqwi-store-core/maqwi-store-core.php`. Activation occurs through native admin after backup and validation. Source refinements compare the current file hashes against the reviewed prior commit and preserve verified snapshots. No automatic gateway or page changes happen merely from activation.

## Guarded configuration exception

On 11 October, the native visitor cron workflow enabled only ALTERNATE_WP_CRON on the temporary site. It verifies the original config against the independently backed-up hash, syntax checks privately, verifies upload and atomically renames. Configuration content is never logged or committed. No external cron, host challenge bypass or root synchronization is used. Six previously blocked actions completed after normal browser visits; the migration notice cleared. Keep this supported native visitor mode enabled while monitoring reliability. It depends on traffic and is not proof of reliable unattended scheduling.

To roll back this exact flag, change enable = True to enable = False in the reviewed native-visitor-cron workflow and commit that workflow alone to staging. The workflow removes only its exact inserted line and verifies the original hash; it aborts on unrelated config differences. Verify homepage/admin and the workflow result afterward.

## Installation baseline

The full UpdraftPlus baseline and independent encrypted copy were created and integrity-verified before installation. Official Kadence and the custom child theme are installed; theme validation and upload verification passed. Full application restore on a separate environment is still required before launch. User authorized development and the public brand launch on the temporary site. Real domain/DNS remain untouched; accepting orders is a separate release.

The approved public brand launch has Coming soon off, brand mode on and indexing disabled for the temporary hostname. Keep ordering closed while catalogue, delivery, authenticated email, completed-order QA and restore tests remain unverified.

## Rollback

Re-activate Twenty Twenty-Five through Appearance > Themes. This child theme performs no database migrations or automatic settings changes. If admin is broken, rename only the child theme directory via FTPS and restore the prior active-theme option using the secure database recovery route. Restore backup for unrelated configuration/plugin problems. Keep the parent and previous theme until validation completes.

## Validation

Automated workflow validates PHP syntax, JSON and asset budgets. Runtime/editor validation still required on the temporary WordPress site. Host REST and loopback restrictions are known; do not attempt to bypass them or rely on inbound webhook integrations.

## Public frontend release

The reviewed refine-commerce-phase workflow can include explicitly listed new owned theme files. It first verifies all existing source hashes against the reviewed prior staging commit and verifies the absence of each new file. Originals are saved in a hidden source snapshot. New assets upload before their referencing templates. All text source must match exact bytes. Native PNG logo masters must retain dimensions and alpha exactly; any host colour recompression is bounded by maximum channel RMS 12/255. Exceptions restore modified originals and remove only newly created files. It never mirrors the root.

Current workflow is a one-release guard: after successful deployment, rerunning it against the prior baseline will intentionally fail. For the next reviewed release update the expected commit and explicit source list; do not disable guards. Browser QA is a separate public-only workflow using normal installed Chrome, four viewport widths and retained screenshots. It has no FTP secret or admin authentication and creates no products, orders, messages or accounts.


## Full storefront preview deployment

The owner explicitly authorised a multi-page frontend and editable sample catalogue on the temporary InfinityFree site. Core0.2.5/theme0.4.3 retains the closed-order brand flag while permitting native browsing and sample-only bag interactions. Guarded FTPS deployments snapshot existing source, verify old hashes, upload/verify temporary files and rename into place, with rollback on failure. New preview module was uploaded before the core required it. Native admin seeding uses capability checks and a nonce, and never overwrites matching page content or existing sample products. Frontend code revision purges W3 Total Cache once using its native function; no external cron, root/DNS edits or host challenge bypass is used.

Current workflow is a one-release allowlist with an expected previous source commit. After successful deployment it intentionally cannot be rerun against the updated source; prepare a new baseline for future work rather than removing hash guards. Source snapshots and Git history provide code rollback. Generated sample products/pages remain normal WordPress records and should be reviewed individually if rolling back the catalogue. Never remove all products or pages as a rollback shortcut.
