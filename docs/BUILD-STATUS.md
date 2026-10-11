# Maqwi build status

## Storefront v0.2.2 — commerce phase

Source: latest supplied master PRD v3.1 and technical requirements v2.0. Visual brief informs styling; its older Shopify references are superseded by WordPress/WooCommerce.

- Kadence child theme at `wp-content/themes/maqwi-organics/`.
- Native homepage pattern available as **Maqwi home** in the block inserter. Published Home page ID17 uses it and is assigned as the static homepage. All native blocks validate cleanly.
- Homepage fallback renders that pattern if the site uses latest posts or an empty front page. A populated static front page retains its own content until intentionally replaced.
- Homepage-specific header/footer avoid overriding unverified parent commerce markup.
- Native keyboard-operable menu/FAQs, visible focus and skip link; responsive category grid.
- Supplied original logo retained, locally optimized to WebP. It has an opaque white background; transparent master selection remains to be verified against the supplied PDF assets.
- Four supplied packaging concepts optimized to 42–56 KB each. They are visibly labelled concepts and must be replaced by verified product photography before launch. Claims printed within these images are unverified and must not become launch claims.
- Featured section queries WooCommerce public featured products and excludes hidden, unavailable and non-purchasable items. Prices come from WooCommerce. No invented catalogue, prices, reviews or support details.
- No page builder, externally requested font, tracking script or newsletter form dependency. A 32 KB self-hosted, licensed EB Garamond Latin font supplies the heading style; the UI uses system sans.
- Approved developer-guide colours: Forest #2F5D3A, Cream #F7F2E7, Gold #D9A441, Charcoal #2D2924 and Sage #A8B59A. Gold is decorative; small story text uses cream for 6.85:1 contrast on green.

## Current installation status

2026-10-11: The initial browser attempt received 502; a later attempt succeeded. UpdraftPlus full backup completed and an independent encrypted copy was downloaded, decrypted and hash-verified. Official Kadence parent installed. Maqwi Organics child theme activated after Live Preview; homepage content and collection scroll verified without fatal errors. Subsequent refinements link the four category names and improve small-text contrast.

Store title Maqwi Organics retained; timezone Asia/Karachi and d/m/Y saved; indexing discouraged and Coming soon retained. WooCommerce currency PKR/zero decimals; selling/shipping Pakistan only; kg/cm units and guest checkout confirmed. Categories Honey, Desi Ghee, Achaar and Itar created and verified. Store address remains unfilled; it must be supplied before launch. Real domain and DNS untouched.

FTP image readback differed in bytes but decoded with identical dimensions and maximum per-channel RMS differences of 2.2–4.1/255; image checks accepted the small recompression differences. PHP/CSS/JSON source files passed exact hashes. No corrupted code was activated.

## Next development and validation sequence

Desktop runtime checks passed for collection scrolling, Story/Questions anchors, FAQ expansion and collapse, all four category archives, Shop and the empty cart. The initial Image block validation issue was fixed by moving sizing/loading attributes into the native render filter; the replacement Home page was validated and published.

1. Complete approved catalogue content, real photography, delivery rates/policies and transactional-email integration.
2. QA 320/390/768/1440px, keyboard navigation, focus and layout. Browser viewport controls were not exposed, so mobile runtime testing remains unverified.
3. Load only approved catalogue data; then test checkout, notifications, delivery/payment handling and recovery before launch.

## Unresolved launch data

Final SKU variants, prices, real stock, ingredients, source, batch/storage information and photography; business address/support contact; delivery rates/courier; approved policies; bank details; authenticated transactional email and analytics IDs. These do not block theme development but block accepting customer orders.

## Hosting findings

Prior audit: REST/loopback 403 in Site Health; external REST request returned a browser-challenge HTML document. A new database-only backup completed 11 October at admin-displayed 12:49:07. Native Run was attempted for action_scheduler/migration_hook ID12; completion was not verified. Six pending/past-due actions and zero failed actions remained, with the migration warning still present. No WooCommerce update callback, telemetry or order-related action was manually executed. At that stage the WooCommerce database schema version was unverified. normal admin access and successful backups do not prove background reliability. Coming soon remains required while checkout, notifications and recovery are unproven.

Native visitor cron test: guarded configuration workflow run 38124486010 succeeded. A normal browser GET showed no fatal error or redirect loop. The six original blocked actions, including migration and WooCommerce update callback, were then Complete; migration-in-progress warning cleared. Counts at first check: 21 total, 6 complete, 15 pending, 2 past-due, no failed tab. A second read-only browser run returned no usable counts, so the two remaining overdue actions are not claimed resolved. Native visitor mode is retained based on measured progress; it is traffic-triggered and does not resolve host REST/loopback restrictions. The browser reported WooCommerce database version 11.2.0-1; the later native setup screen confirmed WooCommerce11.2.0/database11.2.0-1. Earlier version summaries confused an available update with the installed version.

## Commerce implementation — 11 October

Theme v0.2.2 adds the Maqwi shell to WooCommerce catalogue/product templates and regular pages, including cart, checkout and accounts. Product search, real category navigation, native gallery support, live bag-count fragments, responsive product grids, form/table/account styling and product-detail styles are implemented. WooCommerce retains product data, notices, sorting, pagination, forms and processing.

Maqwi Store Core v0.1.3 adds server-side Pakistan/mobile validation, simpler classic checkout fields, optional landmarks stored with the HPOS-compatible order CRUD API, COD On hold confirmation, verified product metadata fields and an owner-facing store setup screen. The setup button requires WooCommerce management capability, page-edit capability, nonce and enabled page revisions; it preserves IDs, titles and assignments and is idempotent. No automatic activation-time database migration or external service call occurs.

PHP lint, checkout-rule checks and asset budgets passed for source commit 11d4bf3475efc66c288426b3074612ea90e3427e. A fresh database-only backup was verified at admin-displayed 14:04 before page conversion. Earlier browser editor attempts were incomplete, so their Cart/Checkout edits are not claimed successful. The core's guarded native setup screen provides a deterministic conversion route. Installation/runtime outcomes are recorded below.

Commerce installer run 38127601023 succeeded after PHP/rule checks. Core activation and native classic-page setup succeeded. COD was enabled with confirmation instructions; bank/online gateways stayed disabled. Taxes were actually enabled on inspection and were disabled pending the business decision, correcting the earlier uncertain audit state. W3 Total Cache was purged once. Runtime verification passed for the shared homepage/shop/cart/account shell, category tabs, Honey archive and product search (empty results), with no fatal/404/duplicate cart. Empty checkout redirected to cart as expected. Native admin showed WooCommerce 11.2.0 with 11.2.1 available; no WooCommerce update was performed.

Runtime QA run 14f6c3cd-8562-4af7-848c-4e6082830808 verified installed theme0.2.1/core0.1.2 and native WooCommerce11.2.0/database11.2.0-1. Temporary DRAFT-only product ID25 (never published) exercised metadata save/display, single-H1 product preview, add-to-cart, quantities1/2, corresponding subtotals and live bag counts. Checkout displayed the expected full-name/mobile/province/address/optional landmark/postcode fields and COD confirmation text, without last name/company/address2. No order was submitted. Shipping rates, actual stock decrement, order status, emails and final delivery were not tested. The fixture was removed from the cart and moved to Trash; active product count0/cart empty confirmed. Mobile viewport controls were unavailable and local Chromium was not installed, so no mobile runtime pass is claimed. The visible label of the hidden country field was identified and corrected in theme0.2.2/core0.1.3; validation38128711395 and guarded source refinement38128711384 passed. Runtime confirmation follows.

Post-correction browser run 3f027bf6-556d-4678-93ab-0eff1fba4671 confirmed installed theme0.2.2/core0.1.3 and WooCommerce11.2.0/database11.2.0-1. It could not restore the draft fixture for a fresh checkout view before its time budget expired, so the final country-label visual check and hidden input DOM values remain unverified. It reported ID25 still in Trash. The previous full product/cart/checkout-field test remains the measured runtime evidence; no completed order, stock reduction or email delivery is claimed.

Final cleanup run d2accd69-0b1e-46d1-ab6c-a203e912383c found the fixture still Draft (contrary to the previous run summary), moved only ID25 to Trash and verified it in the Trash listing. Cart was confirmed empty and bag count0. No order, email or public QA product was created.

## Public brand launch — 11 October 2026

The owner selected a public brand launch today, with orders closed. Theme 0.2.3 and Store Core 0.1.4 add a fail-closed brand mode. Purchasing (including variations), gateways and classic checkout are guarded; public commerce/account routes redirect to the homepage collection. Header/footer expose Collection, Story and Questions instead of cart/account. The native owner setup screen has a nonce/capability protected action to remove Coming soon only after enabling the order guard. Real-domain DNS stays unchanged.

Opening paid orders is a separate release requiring catalogue, prices, stock, delivery, contacts, policies, authenticated email and end-to-end testing. COD customer email wording remains a store-release task.

### Public release verification

Brand release commit: `dabecfbd316f8bc8799172f0992bd6fe085c411e`. PHP/rule/asset validation run 38131394558 and guarded source deployment run 38131394564 both succeeded. Existing source snapshots are retained in `.maqwi-commerce-refine-38131394564` on the temporary host.

Authenticated native-admin run `4ae77434-df6e-4489-8946-dbb8b53613ed` verified Core 0.1.4 / theme 0.2.3, applied the explicit brand-launch action, confirmed visibility Live and purged W3 Total Cache. Fresh signed-out run `d849ecf1-f7c0-41a3-80db-8622159fa8db` confirmed public homepage access, welcome announcement, all five image assets, collection/story anchors, FAQ and no Bag/Account links. Direct `/checkout/` and `/shop/` visits redirected to homepage collection with no purchase forms. No visible errors or broken images were found. No orders, email, DNS or indexing changes were made. Narrow viewport controls were unavailable, so mobile runtime remains unverified.

Today’s approved deliverable is the public brand introduction at https://maqwiorganics.gt.tc/ — not an open-order store. Full store implementation and launch gates above remain outstanding. Brand mode defaults closed if the option is absent; disabling it is a separate reviewed store release and has no UI switch in this release.

## Visitor default-page report — 11 October 2026, 16:06 PKT

User screenshot showed InfinityFree’s default page at the temporary hostname. Fresh HTTP, HTTPS and direct index.php requests already showed Maqwi before intervention, so the screenshot’s precise cause (cached page versus a different route) is unproven. Read-only FTP inspection run 38134427413 confirmed the 405-byte WordPress index.php, no index.html/index.htm, and a leftover verified InfinityFree index2.html (10,160 bytes). Existing HTTPS conditions and WordPress rewrite rules were present; no DirectoryIndex override was found.

Guarded workflow run 38134487284 succeeded: verified exact hashes of index.php and the provider placeholder, backed up index2.html under wp-content/themes/.maqwi-default-page-38134487284, verified the backup, removed only the obsolete root placeholder and verified WordPress index.php unchanged. It does not modify .htaccess, wp-config.php, DNS or content. Recovery: restore the retained index2.html only if the provider placeholder is intentionally needed; it is not the Maqwi homepage.

Post-change fresh HTTP, HTTPS and ?view=brand-20261011 requests all showed the Maqwi hero and no provider placeholder. TinyFish native-admin run 60411697-5f5b-4f7c-a655-35ce2eb62b07 purged W3 Total Cache and rechecked the correct homepage. This cannot clear a previously stored copy in the user’s browser; a fresh query link was provided. Ordering remains closed.
