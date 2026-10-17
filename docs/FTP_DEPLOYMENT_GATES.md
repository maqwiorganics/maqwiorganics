# InfinityFree deployment gates

## Verified 2026-10-17
- Manual GitHub Actions FTP read-only connectivity workflow #1: successful.
- Manual GitHub Actions FTP reversible probe workflow #1: successful (temporary file upload, read-back, deletion).
- Existing encrypted repository secrets: INFINITYFREE_FTP_HOST, INFINITYFREE_FTP_USERNAME, INFINITYFREE_FTP_PASSWORD. Never disclose secret values.

## Not yet established
- Plugin source in GitHub must match live Maqwi Store Core 0.3.2 byte-for-byte.
- Predeployment backup of the exact remote plugin directory and successful restoration of a test file.
- Narrowly scoped deployer limited to /htdocs/wp-content/plugins/maqwi-store-core/; no recursive sync delete of unrelated data.
- Deployment must be manual, use fresh backup, detect unexpected remote contents and fail closed.
- WordPress admin/WooCommerce authenticated API remains unavailable via WPVibe under InfinityFree external request handling.

## Required release lifecycle
1. Import audited source and assets on development branch.
2. Run PHP lint and secrets/asset scan.
3. Preflight FTP read; create remote backup outside web-served path if host permits, and verify downloads.
4. Manually approve deployment of exact tested artifact to plugin directory; no database changes and never flip order guard.
5. Re-read deployed files and check hashes; verify public pages.
6. On failure, restore backup; report exact state, never silently continue.

## Launch gate
Public orders remain blocked until shipping pricing/coverage, actual stock, payments, verified address/policies, emails, and end-to-end order tests pass.
