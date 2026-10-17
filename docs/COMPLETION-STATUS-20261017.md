# Completion status — 17 October 2026

## Verified during this session

- GitHub repository access works. Staging contains the custom theme, plugin, photography and tests. Main contained operations workflows but no custom source.
- Main's operations history is integrated into staging without replacing the storefront source. Approved business decisions are copied from the existing feature branch and represented in `config/approved-store.json`.
- WPVibe reports no registered sites. Connecting https://maqwiorganics.gt.tc failed: REST API returned non-JSON even after retry. No live WooCommerce configuration or authenticated database inspection was possible through this integration.
- Prior FTP connectivity and reversible-probe runs succeeded (39021324705 and 39021428474). These historical runs do not prove current deployment parity, database backup freshness, or checkout correctness.
- This session has not modified hosting, production data, existing backups, visibility, payment settings or the live ordering guard.

## Deployment corrections

The previous guarded workflow recorded a file only after upload and read-back. A partial upload or verification failure could therefore exclude that file from rollback. The new implementation records every attempted file before upload and verifies restoration. Tests simulate both failure cases.

Backups must upload successfully as an artifact BEFORE any remote write. FTPS certificate verification and private data-channel encryption remain required. Only existing allowlisted plugin files can change. No root/config/uploads/database files are included. Missing files and all FTP permission errors fail closed. Remote files must match a reviewed hash baseline both before backup and immediately before deployment.

`config/remote-plugin-sha256.json` is deliberately absent until an authenticated snapshot of the complete deployed plugin has been reviewed. The deploy workflow cannot run without it. Do not generate hashes from staging and describe them as live evidence. File-by-file FTPS replacement is not an atomic release; use an authenticated maintenance window for a multi-file runtime change. A source backup is not a database backup.

Historical mutation workflows are manual-only, so a staging push no longer triggers those installations or theme replacements. Deployment stays manual on main. Do not use the historical installers as a release path.

## WooCommerce application checklist (not yet applied)

1. Obtain authenticated WordPress access and take a fresh database backup plus custom-source snapshot. Preserve existing recovery archives and keep the recovery key private. Verify restoration in an isolated environment.
2. Verify Maqwi order guard is enabled and all payment gateways remain unavailable publicly. Confirm product identities by slug and release metadata before editing; do not create duplicate products or discard old variations/orders.
3. Set approved prices and opening inventory from `config/approved-store.json`. Opening stock is a one-time initialization, never a recurring sync. Inspect orders and existing stock before initialization; subsequent runs must not reset sold stock.
4. Achaar is one variable parent managing quantity 5, backorders disabled. Both 500 g (1500) and 1 kg (3000) variations inherit parent stock (variation manage-stock disabled, no independent stock counter). Preserve legacy variations as private. Verify both resolve their stock-managed ID to the same parent. Test mixed-size baskets, simultaneous checkout reservations, cancellation and restoration; never assume two counters of 5 provide a shared pool.
5. Honey 1 kg: 4500/20. Ghee 1 kg: 3500/15. Itar 12 ml: 200/10. Preserve native WooCommerce CRUD and inventory reservation/reduction hooks.
6. Currency PKR; selling and shipping country Pakistan. Inspect all shipping zones first. Use one country-wide Pakistan zone and one flat-rate method costing 300 per order; no per-item formula. Preserve the existing tax setup pending its verification. Remove the sample shipping adapter from active checkout only after the real method is verified. Test one fee for multi-item baskets and reject non-Pakistan destinations. If multiple shipping packages are created, verify the fee is not duplicated.
7. Publish approved contact details: Ali Town, Lahore, Punjab, Pakistan; 03171935828; info@maqwiorganics.com. No invented street number or support hours. Verify the mailbox and transactional delivery. Keep the drafted returns policy for owner review.
8. Confirm photographs match listed net contents and remove stale claims that Achaar prices/shipping/contact are unconfirmed. Do not invent ingredients, allergens, scent, courier timing or payment approvals.

## Remaining launch evidence

Authenticated order tests must cover classic checkout and Store API guard, total calculation, stock reservation/reduction/restoration, duplicate submission, order emails, mobile layout and recovery. With public ordering still closed, use an isolated test environment for order creation. Expected totals before any separately applicable tax: honey 4800, ghee 3800, Itar 500, Achaar 500 g 1800, Achaar 1 kg 3300; both Achaar sizes in one order 4800 with shared stock consumption 2.

Public launch and payment activation require the owner's separate decision after tests pass. This document is not launch approval or evidence that live configuration was applied.
