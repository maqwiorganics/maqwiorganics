# Maqwi build status

Current release: **theme 0.3.5 / Store Core 0.1.5**, public brand introduction on the temporary hostname, ordering closed. Read the final frontend release section for current QA evidence; older sections below retain installation history.

## Earlier storefront foundation and commerce phase

Source: latest supplied master PRD v3.1 and technical requirements v2.0. Visual brief informs styling; its older Shopify references are superseded by WordPress/WooCommerce.

- Kadence child theme at `wp-content/themes/maqwi-organics/`.
- Native homepage pattern available as **Maqwi home** in the block inserter. Published Home page ID17 uses it and is assigned as the static homepage. All native blocks validate cleanly.
- Homepage fallback renders that pattern if the site uses latest posts or an empty front page. A populated static front page retains its own content until intentionally replaced.
- Homepage-specific header/footer avoid overriding unverified parent commerce markup.
- Native keyboard-operable menu/FAQs, visible focus and skip link; responsive category grid.
- Supplied original logo retained, locally optimized to WebP. The initial version had an opaque white background; the current frontend uses original transparent colour/white variants extracted from the supplied logo PDF, as recorded below.
- Four supplied packaging concepts optimized to 42–56 KB each. They are visibly labelled concepts and must be replaced by verified product photography before ordering opens. Claims printed within these images are unverified and must not become launch claims.
- Featured section queries WooCommerce public featured products and excludes hidden, unavailable and non-purchasable items. Prices come from WooCommerce. No invented catalogue, prices, reviews or support details.
- No page builder, externally requested font, tracking script or newsletter form dependency. A 32 KB self-hosted, licensed EB Garamond Latin font supplies the heading style; the UI uses system sans.
- Approved developer-guide colours: Forest #2F5D3A, Cream #F7F2E7, Gold #D9A441, Charcoal #2D2924 and Sage #A8B59A. Gold is decorative; small story text uses cream for 6.85:1 contrast on green.

## Initial installation history

2026-10-11: The initial browser attempt received 502; a later attempt succeeded. UpdraftPlus full backup completed and an independent encrypted copy was downloaded, decrypted and hash-verified. Official Kadence parent installed. Maqwi Organics child theme activated after Live Preview; homepage content and collection scroll verified without fatal errors. Subsequent refinements link the four category names and improve small-text contrast.

Store title Maqwi Organics retained; timezone Asia/Karachi and d/m/Y saved; indexing discouraged and Coming soon retained. WooCommerce currency PKR/zero decimals; selling/shipping Pakistan only; kg/cm units and guest checkout confirmed. Categories Honey, Desi Ghee, Achaar and Itar created and verified. Store address remains unfilled; it must be supplied before launch. Real domain and DNS untouched.

FTP image readback differed in bytes but decoded with identical dimensions and maximum per-channel RMS differences of 2.2–4.1/255; image checks accepted the small recompression differences. PHP/CSS/JSON source files passed exact hashes. No corrupted code was activated.

## Earlier development and validation sequence

Desktop runtime checks passed for collection scrolling, Story/Questions anchors, FAQ expansion and collapse, all four category archives, Shop and the empty cart. The initial Image block validation issue was fixed by moving sizing/loading attributes into the native render filter; the replacement Home page was validated and published.

1. Complete approved catalogue content, real photography, delivery rates/policies and transactional-email integration.
2. QA 320/390/768/1440px, keyboard navigation, focus and layout. At that earlier stage browser viewport controls were not exposed. The later public frontend acceptance results below cover these widths.
3. Load only approved catalogue data; then test checkout, notifications, delivery/payment handling and recovery before launch.

## Unresolved open-order store data

Final SKU variants, prices, real stock, ingredients, source, batch/storage information and photography; business address/support contact; delivery rates/courier; approved policies; bank details; authenticated transactional email and analytics IDs. These do not block theme development but block accepting customer orders.

## Hosting findings

Prior audit: REST/loopback 403 in Site Health; external REST request returned a browser-challenge HTML document. A new database-only backup completed 11 October at admin-displayed 12:49:07. Native Run was attempted for action_scheduler/migration_hook ID12; completion was not verified. Six pending/past-due actions and zero failed actions remained, with the migration warning still present. No WooCommerce update callback, telemetry or order-related action was manually executed. At that stage the WooCommerce database schema version was unverified. normal admin access and successful backups do not prove background reliability. At that stage Coming soon was retained. The later approved public brand launch uses explicit ordering guards while checkout, notifications and recovery remain unproven for sales.

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

## Public frontend release — theme 0.3.5 / core 0.1.5

Public brand scope: polished homepage, sticky branded navigation, collection overview and four working expandable collection panels, story, principles, FAQ, footer/back-to-top, browser-sharing metadata and a branded missing-page screen that returns true HTTP 404. Ordering remains closed. Native block content stays editable; brand detail panels are rendered by the existing featured shortcode in brand mode. Store mode retains native WooCommerce featured-product data. No invented prices, catalogue, contact links, forms, reviews or policies were published. Header and footer use the original colour/white transparent logo variants from the supplied PDF, including its existing alpha masks; the brand mark was not redrawn.

Mobile menu supports Escape with focus returned to the trigger, outside clicks and closing on link selection. Collection links stay in the current document. Browser tracing showed the working hash handler was not invoked by existing smooth-anchor handling during the real click; capture-phase click handling now reveals the requested panel before the parent anchor handling calculates its scroll position. Legacy category URLs redirect to the matching collection, while cart/checkout/account/shop return to the brand overview. Original concept imagery is labelled throughout; current copy states that verified details and prices follow before orders open.

Guarded deployment 38135550000 succeeded on attempt 2 after an initial FTP-connect timeout made no writes. Subsequent guarded deployments and native cache purges installed source refinements and original logo assets. Initial acceptance checks found the collection event issue; a diagnostic run used synthetic events and is not accepted as interaction proof. The clean final pre-contrast-fix run 38137215215 passed real clicks at 320/390/768/1440px, no horizontal overflow, loaded images/font, one main H1, metadata, all anchor targets, menu Escape/focus/link close, FAQ, collection routes, skip link, closed ordering routes and real 404 navigation, with zero runtime or console errors. Visual inspection of retained phone/desktop/404 screenshots found the 404 button had inherited dark text. Theme 0.3.4 corrects that CSS specificity and is rechecked below.

The public frontend is a brand introduction. Approved contact details, real product photos/label information, stock/prices, policies, delivery rates, authenticated order email, completed-order QA and recovery tests remain requirements for the open-order store. Real domain and DNS remain unchanged, and the temporary site remains discouraged from indexing.

### Final deployed acceptance — 11 October 2026

Theme 0.3.4 acceptance run 38137571656 passed the real visitor interactions and verified the corrected 404 button contrast at 6.85:1. An additional footer test then found that the sticky header's top anchor did not return to the document start. Theme 0.3.5 moves the target to the body and handles the owned footer action explicitly. Source commit `7b7cbb43035e6d256eec4c6855d601c22a16a7d1` passed validation 38138196653 and guarded deployment 38138196681. Previous source snapshots are retained at `wp-content/themes/.maqwi-commerce-refine-38138196681`. Native W3 Total Cache purge completed after deployment.

Final public acceptance run [38138420029](https://github.com/maqwiorganics/maqwiorganics/actions/runs/38138420029) succeeded against deployed theme 0.3.5 with normal Chrome and Playwright, without admin credentials, synthetic events, orders or messages. Test commit: `55d381e88588461ac56aba5f24ed256a82a22a77`.

| Chrome viewport width | Horizontal overflow | Broken visible images | Collection panel | Navigation / FAQ |
| --- | --- | --- | --- | --- |
| 320px | None | 0 | Fits | Mobile menu Escape, focus and selection; FAQ passed |
| 390px | None | 0 | Fits | Mobile menu Escape, focus and selection; FAQ passed |
| 768px | None | 0 | Fits | Desktop navigation; FAQ passed |
| 1440px | None | 0 | Fits | Desktop navigation; FAQ passed |

All four widths loaded the brand font and exact deployed public script. The suite also passed one main H1, sharing metadata, every local anchor target, collection deep links, keyboard skip link, closed commerce routes, branded true HTTP 404 and its home button, and the footer return to the document start. Runtime and console errors: zero. Computed 404 button contrast: 6.8456:1. Retained phone, desktop and 404 screenshots were visually inspected during refinement. These are Chrome viewport checks, not a claim of testing physical devices or every browser.

Public brand frontend acceptance is complete. Orders stay closed and packaging images remain explicitly labelled concepts. This does not complete the separate open-order store requirements recorded above.


## Expanded full storefront preview — 11 October 2026

The owner clarified that the requested frontend includes separate shop/product/cart/checkout/account and information pages, with editable sample products, not only the brand homepage. This supersedes the prior homepage-only frontend scope. Ordering remains closed on the temporary InfinityFree site; DNS and the real domain are untouched.

Core0.2.0/theme0.4.0 source commit c0d5e1fa6389075263aed7ff71595575f508e8b4 passed validation38139996961 and guarded deployment38140025791. Authenticated native-admin run9481c6e4-e9eb-42c3-8dfa-af1fd65085e1 clicked the owner-authorised setup action once and confirmed preview=ready, four published variable products, zero drafts and orders closed. Six information pages were created. Native cache was purged by setup. No orders, emails or DNS changes occurred.

The editable sample catalogue has Honey, Desi Ghee, Achaar and Itar, two sizes per product, descriptions, Product details tabs and original concept images. Prices and sizes are explicitly examples. Unconfirmed ingredients, allergens, sourcing and storage information are clearly marked. Native WooCommerce owns product editing, variations, sorting/search, category navigation, bag sessions, quantities and classic checkout fields. Non-sample products cannot be purchased while the sales guard is on. Gateways, classic order validation, pre-save order creation and Store API checkout writes remain blocked. Account login is visible, registration is disabled.

Separate pages: Our Story, Contact, FAQ, Delivery, Returns and Privacy Information. The Contact page deliberately has no invented address/phone/email or non-working enquiry submission. Delivery/returns/privacy drafts state what remains unapproved. A labelled sample delivery rate is available to test checkout totals, without changing real shipping zones or courier arrangements. Owner instructions and sample editing details are in STORE-CORE.md.

Initial visitor checks passed the complete 320px shopping journey. A later new browser context stopped at the provider challenge before loading390px; it is not a390px application failure or pass. Subsequent checks reuse the normal browser session and its naturally established visitor cookies. Native single-result search goes directly to the matching product and is accepted as correct WooCommerce behaviour. Quantity testing uses a normal focus change after entry, rather than synthetic DOM events. Final acceptance results follow after refinement.


### Final source refinements

Theme0.4.1/Core0.2.1 polish source e71d296a5597d17e11134f9dbfd79d09f4cb1188 passed validation38140694857. Guarded deployment38140752701 succeeded on attempt2; its initial connect timeout made no writes. Final refinements remove duplicate product breadcrumbs, add the footer Privacy link and replace stock checkout payment/privacy messaging with accurate preview copy. Source snapshots remain at wp-content/themes/.maqwi-commerce-refine-38140752701.

Core0.2.2 source9595db1591c22030f2a6615a4ca725049eb6c138 additionally blocks Store API checkout GET requests, which could otherwise allocate draft orders even before payment. Validation38140895307 and guarded deployment38140895317 both passed. Source snapshots remain at wp-content/themes/.maqwi-commerce-refine-38140895317. A revision-scoped native cache purge runs once on the first uncached preview request; no catalogue is created by public requests.


Core0.2.3 source6bf0b4389242b864ea4ea5daba73c21add854fe8 passed validation38141285930. Deployment38141285978 succeeded on attempt3 after two FTP-connect timeouts made no writes. Source snapshots: wp-content/themes/.maqwi-commerce-refine-38141285978. Preview-only shipping enablement and calculation filters allow the labelled sample rate even while real shipping is unconfigured; the original real-store enablement/address requirements are retained outside preview. Pure checks cover that isolation. Final browser checks verify delivery totals, not only rendered fields.


The browser diagnostic established needsShipping=false despite PK/PB/Lahore input and active checkout JavaScript. WooCommerce skips delivery before package-rate filters when its configured-method count is zero. Core0.2.4 source129cecc8981081659af448f8b407a775483e7d93 registers a preview-only native legacy delivery method (no shipping-zone records created), clears the native shipping count/package cache once, and preserves all real shipping configuration outside preview. Validation38141863718 and guarded deployment38141863746 succeeded. Previous source snapshot: wp-content/themes/.maqwi-commerce-refine-38141863746. This replaces the incomplete package-filter-only approach; it is not a claim that an actual courier service is configured.


Functional acceptance38142023166 passed the full shopping journey at320/390/768/1440px, all eight size choices and six information pages. Its eight403 console messages correspond to deliberately rejected POST/GET checkout requests (two per width); runtime errors were zero. Final screenshot inspection still showed duplicate product breadcrumbs because the parent theme emitted an additional breadcrumb during the explicit Woo breadcrumb call; the earlier CSS-only correction did not resolve that output. Theme0.4.2 now generates and renders native Woo crumbs once without the parent output action. Core0.2.5 uses core/theme revisions together for native cache invalidation. Source19438b3b5ad1d55c5c8e0adc64c97ffde9e0518f passed validation38142354240 and guarded deployment38142354244. Source snapshot: wp-content/themes/.maqwi-commerce-refine-38142354244. The final browser suite checks one breadcrumb and waits for native checkout loading overlays to settle before screenshots.


DOM inspection in38142735226 established that Kadence adds a separate nav#kadence-breadcrumbs (aria-label Breadcrumbs), alongside the child nav (aria-label Breadcrumb). Theme0.4.3 suppresses that exact parent duplicate in the custom commerce shell; the acceptance assertion now counts visible breadcrumb navigation, including both labels. Source3a01d47946f5f9c5ba555fc0db978a8ebc9a5d5e passed validation38142863190 and guarded deployment38142863185. Previous CSS snapshot: wp-content/themes/.maqwi-commerce-refine-38142863185. No further catalogue or settings changes were made.


### Accepted storefront frontend — Theme0.4.3 / Store Core0.2.5

Final acceptance [38142970995](https://github.com/maqwiorganics/maqwiorganics/actions/runs/38142970995) succeeded against the deployed temporary site. Test commit8ecb9a2913daba75cdca113e22a7d1ab6b5c8f3d. All320/390/768/1440px Chrome viewport journeys completed: homepage, shop, real price-desc sort order, search (including native single-result redirect), Honey category, variable product, bag quantity update, checkout, account login screen, About/Contact/FAQ/Delivery/Returns/Privacy information and empty bag. Fifty-six page/width checks had no horizontal overflow, no broken main images and one main H1. Additional checks selected both sizes for all four products (eight size options). The final product screen has one visible breadcrumb.

Sample Honey250g ×2 = PKR1700; labelled sample deliveryPKR250; checkout totalPKR1950. Native fields and disabled order submission were verified. Both POST and GET Store API checkout requests were specifically rejected with maqwi_orders_closed403 at all four widths, preventing submitted and draft orders. Eight expected403 console messages came from those deliberately blocked requests; JavaScript runtime errors were zero. No completed orders, stock decrement, real customer information, email or messages were created by QA. Sample bags were emptied at the end of each width journey. Guest account login presentation was checked; authenticated customer dashboard operations and account creation were not tested.

Phone/desktop product, bag and checkout screenshots plus tablet shop were visually inspected during final refinements. Checkout screenshots wait for native loading overlays to clear. Final DOM visibility assertion verifies the targeted parent breadcrumb suppression. These are Chrome viewport checks, not physical-device or all-browser certification.

Frontend preview acceptance is complete. All sample catalogue records and six information pages are ordinary editable WordPress/WooCommerce content. Packaging imagery is concept artwork; ingredient/sourcing/allergen specifications, actual prices/sizes/stock, contact channels, approved delivery/returns/privacy terms, authenticated email and a reviewed order release still need confirmation before paid orders can open. The Contact page has no active enquiry submission. Real domain/DNS and main baseline remain unchanged; current source and release notes are in staging.


## Catalogue review release — 16 October 2026

Theme0.5.0 / Store Core0.3.0 now uses the owner's Products.zip image set: four native gallery images per product and a collection banner. Product descriptions, neutral SKU codes and exact approved offers are installed: Honey1kg PKR4500, Ghee1kg PKR3500, Itar12ml PKR200. Achaar remains unpriced with pack size pending. Unapproved smaller variants are retired; previous sample variations are private/unpriced, and original records/meta are retained in the revision snapshot. Stock is unavailable with backorders disabled until real counts are supplied. Photo labels showing500g/500ml are explicitly distinguished from the1kg offers. No sourcing, ingredient, formulation, certification or physical photography claim is invented.

Catalogue acceptance38869235803 passed52 page/width checks at320/390/768/1440, four galleries per product, unavailable native variations, zero runtime errors/broken visible images/horizontal overflow and eight deliberately blocked checkout requests. Phone and desktop screenshots were inspected. Details and deployment evidence are in CATALOGUE-RELEASE-20261016.md. Sales remain closed pending business/label/stock/delivery/support/policy information, authenticated email, real order tests and restoration checks. Main and real DNS remain unchanged.
