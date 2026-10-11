# Maqwi build status

## Foundation v0.1.3

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

1. Apply the brand consistently to inherited Kadence shop/product/cart/checkout templates after reviewing their markup.
2. QA 320/390/768/1440px, keyboard navigation, focus and layout. Browser viewport controls were not exposed, so mobile runtime testing remains unverified.
3. Load only approved catalogue data; then test checkout, notifications, delivery/payment handling and recovery before launch.

## Unresolved launch data

Final SKU variants, prices, real stock, ingredients, source, batch/storage information and photography; business address/support contact; delivery rates/courier; approved policies; bank details; authenticated transactional email and analytics IDs. These do not block theme development but block accepting customer orders.

## Hosting findings

Prior audit: REST/loopback 403 in Site Health; external REST request returned a browser-challenge HTML document. A new database-only backup completed 11 October at admin-displayed 12:49:07. Native Run was attempted for action_scheduler/migration_hook ID12; completion was not verified. Six pending/past-due actions and zero failed actions remained, with the migration warning still present. No WooCommerce update callback, telemetry or order-related action was manually executed. At that stage the WooCommerce database schema version was unverified. normal admin access and successful backups do not prove background reliability. Coming soon remains required while checkout, notifications and recovery are unproven.

Native visitor cron test: guarded configuration workflow run 38124486010 succeeded. A normal browser GET showed no fatal error or redirect loop. The six original blocked actions, including migration and WooCommerce update callback, were then Complete; migration-in-progress warning cleared. Counts at first check: 21 total, 6 complete, 15 pending, 2 past-due, no failed tab. A second read-only browser run returned no usable counts, so the two remaining overdue actions are not claimed resolved. Native visitor mode is retained based on measured progress; it is traffic-triggered and does not resolve host REST/loopback restrictions. The browser reported WooCommerce database version 11.2.0-1, but plugin version observations across runs conflict; exact code/schema compatibility needs reconciliation before launch.
