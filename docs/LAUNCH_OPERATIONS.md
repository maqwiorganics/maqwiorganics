# Maqwi Organics — operational baseline (2026-10-17)

## Invariants
- Keep InfinityFree and maqwiorganics.gt.tc; no hosting or DNS changes.
- Public ordering remains closed until real checkout, stock, shipping, email and recovery tests pass.
- Never commit wp-config.php, passwords, orders/customer data, database backups, or tokens.
- Do not upload all of wp-content or WordPress core.
- Never deploy on GitHub push or merge by default; releases must be manually approved and verified.

## Verified current state
- Maqwi Store Core 0.3.2 installed via WordPress and dashboard confirmed active by administrator screenshot.
- WooCommerce v11.2.0 and WPVibe v1.20.5 observed.
- UpdraftPlus full files+DB backup completed 2026-10-17 13:36 local time; restore test not yet performed.
- WordPress REST and WPVibe health endpoints accessible through browser-compatible external request service.
- WPVibe's own connect_site receives HTTP 200 non-JSON before authorization; no stored WPVibe credentials.
- TinyFish paid browser automation blocked by insufficient wallet balance; no recorded authenticated session.
- GitHub is connected, but no direct FTP deployment credential is available through the connector.

## Access blockers and acceptance tests
1. **Read/write WordPress management:** WPVibe must successfully authorize, list the site, read products and run a safe authenticated GET before classifying as connected. InfinityFree's browser challenge may make this unsupported on free hosting.
2. **FTP deployment:** Use encrypted GitHub Actions secrets (not repo files). Verify connectivity, exact /htdocs path, write and read back a harmless namespaced probe, then remove the probe. Never run untested broad mirror/delete.
3. **Rollback:** Keep a verified v0.3.1 archive and current v0.3.2 archive; test restore in non-production where possible.
4. **Ecommerce:** approve actual inventory, Achaar package/price, delivery zones and tariffs, COD operations, address and support contact, policies.
5. **Acceptance:** test variation -> cart -> checkout -> shipping/COD -> order -> stock decrement -> admin/customer notification -> cancel/refund path, with tracked evidence.

## Pending business decisions
- Achaar pack, price, stock.
- Confirmed quantity of every sellable variant.
- Actual courier service/fees and serviceable areas.
- Business address and customer support details.
- Email sender and mailbox/service.
- Refund/return and product labeling details.

## Deployment gates
1. Staging branch and checks.
2. Fresh verified backup.
3. No plugin activation or catalog/ordering guard modifications outside reviewed scope.
4. Manual deployment only once authenticated FTP connectivity and rollback are verified.
5. Post-deployment homepage, shop, REST and dashboard checks.
6. Explicit approval before unlocking live ordering.

## Source of truth
The live site currently contains v0.3.2 custom plugin. The ZIP archive from the conversation must be imported to GitHub as source after reviewing for secrets; this branch is documentation-only until that import is completed.
