# Maqwi live verification — 18 October 2026

Status: NOT READY TO ACCEPT SALES. This report is not launch approval.

## Evidence obtained in this session

InfinityFree login completed using the owner's Maqwi Google account. Account if0_43106867 is Active. File Manager, Softaculous and native phpMyAdmin are accessible. WordPress administration and the homepage return "Site Unavailable" in the Cloud Browser; host-provided WordPress sign-on reaches the same result. This does NOT establish a public outage.

Independent GitHub Actions run:
https://github.com/maqwiorganics/maqwiorganics/actions/runs/39125862701
Commit: 4b95c2c8893a53074af8a697212b3f6a9c98d7ce

Both read-only jobs passed. The public check used normal installed Chrome at 390 × 844, made no orders, and verified:
- Homepage, shop and Honey/Ghee/Itar/Achaar product routes: HTTP 200.
- Four products in the shop; approved Honey/Ghee/Itar displayed prices.
- No horizontal overflow on these routes; one main H1; no JavaScript page errors.
- Store API checkout GET: HTTP 403, code maqwi_orders_closed.
This is a limited public availability check, not a complete checkout, responsive, security or email acceptance test.

FTPS authentication and encrypted data channel succeeded using existing GitHub secrets. TLS for the site verified using TLS 1.3; certificate expires 29 November 2026. Source inspection identified WordPress 7.1.3, WooCommerce 11.2.0, UpdraftPlus 1.26.8, W3 Total Cache 2.10.7. Softaculous's installation inventory showed 7.0.2 and is stale relative to the source.

## Authenticated database observations (SELECT only)

Native database: if0_43106867_wp977, prefix wps9_.

- maqwi_brand_launch=yes; maqwi_storefront_preview=yes; woocommerce_coming_soon=no.
- Currency PKR; default country PK:PB; selling and shipping countries explicitly restricted to PK.
- Global stock management enabled.
- Active child theme maqwi-organics; parent kadence; search indexing disabled.
- Email From address stored as maqwiorganics@gmail.com. Authenticated mail transport and delivery remain unverified.
- Stored COD and bank-transfer settings include enabled=yes. Public gateways remain suppressed by the order guard. Do not remove that guard or describe the underlying gateway options as disabled.
- HPOS orders: 0. Legacy shop_order posts: 0. No customer order was created or removed.
- Shipping zones: 0; shipping methods: 0. PKR 300 nationwide shipping is approved but is NOT configured natively.
- Action Scheduler: 123 complete, 1 failed, 14 pending. Due dates and the failed hook were not inspected; this does not prove all pending jobs are overdue.
- Tables inspected are MyISAM. Inventory reservation/concurrency and recovery must be verified against the actual storage engine before opening orders.

### Actual versus approved catalog

| Product | Actual live database | Approved target |
| --- | --- | --- |
| Honey | 1 kg variation ID 62; PKR 4,500; managed stock 0; out of stock | Stock 20 |
| Desi Ghee | 1 kg variation ID 63; PKR 3,500; managed stock 0; out of stock | Stock 15 |
| Itar | 12 ml variation ID 64; PKR 200; managed stock 0; out of stock | Stock 10 |
| Achaar | Parent ID 32; old private variations only; no active approved sizes | 500 g PKR 1,500 and 1 kg PKR 3,000; shared parent stock 5 |

Existing products, old private variations, the trashed QA product and all other records were preserved. Opening stock must be initialized once, through WooCommerce CRUD, after rechecking orders and current stock. Never reset sold stock on subsequent deploys.

## Backup and source state

Complete Updraft backup components dated 17 October 2026 are present: database, plugins, themes, uploads, others. Earlier 11 October archives are also present and untouched. The backup directory contains a deny rule. These observations prove presence only; archive integrity, fresh export and isolated restore were not tested.

All six inspected owned source files differ from the current staging checkout: functions.php, style.css and four maqwi-store-core PHP files. Main has later production design history than some staging files. Do not blindly deploy staging, replace live with an older snapshot, or assume a successful earlier deployment establishes present parity. Obtain a complete current source snapshot and reconcile each difference before deploying.

No hosting files, production database records, payment options, existing backups, launch visibility, DNS or mail settings were changed in this session. Only a read-only verification workflow and this report were added to staging.

## Remaining release work

1. Restore usable authenticated WordPress administration through an available integration. WPVibe still has no stored connection and its connect attempt returned non-JSON REST output. The available Cloud Browser cannot currently render the WordPress site, although independent public checks pass.
2. Preserve a fresh full backup, verify archive integrity and demonstrate an isolated restore.
3. Reconcile live/main/staging source and retain guarded, scoped deployment with rollback; preserve current premium UI.
4. Apply the approved real inventory, Achaar variations and one PKR 300 country-wide shipping rate through native WooCommerce APIs. Keep existing records.
5. Verify authenticated sender/domain and customer/admin email delivery.
6. Complete classic and Store API order guards, totals, shared stock, reservation/concurrency, cancellation/restoration, duplicate submission, account flow, and full mobile/desktop acceptance.
7. Obtain the owner's final launch confirmation. No payment activation or public ordering is authorized by this report.
