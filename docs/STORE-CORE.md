# Store behaviour — commerce phase

Maqwi Store Core is a small site-owned plugin. Checkout and catalogue rules stay active when the visual theme changes. It depends on WooCommerce, declares HPOS compatibility and makes no activation-time migrations or external requests.

## Classic checkout

Use native Shortcode blocks containing `[woocommerce_cart]` and `[woocommerce_checkout]` on the existing assigned WooCommerce pages. The plugin's field customisations intentionally target shortcode checkout, not the Checkout block.

Full name replaces separate name inputs; account address edits retain an optional last-name field for compatibility. company and address line two are removed. Country is hidden with Pakistan as default, and server-side validation rejects another billing or delivery country. Province and city remain required; postcode and nearby landmark are optional. Native WooCommerce account, order, nonce and payment processing remain in use.

Pakistani mobile numbers accept `03XXXXXXXXX` or `+923XXXXXXXXX`, including display spaces, parentheses and hyphens. Valid numbers are stored as `+923XXXXXXXXX`. Landline, foreign, malformed and missing numbers are rejected. The plugin saves billing/shipping landmarks through the order CRUD API and displays them in the corresponding admin address panel.

Cash-on-delivery orders enter **On hold** for confirmation. This does not enable a gateway or create an order. Staff confirm the customer and address, then move the order to Processing. Native WooCommerce on-hold email remains in use; email delivery must be tested before launch.

## Verified product information

Products > Edit > Product data > General contains fields for net contents, ingredients, allergens, origin, storage, usage, batch, packing date and best-before. Enter only verified label/supplier facts. Saved text is sanitised; storefront output is escaped. Empty values are hidden. A Product details tab appears only when data exists. Prices, stock, variants, physical shipping weight and images remain in native WooCommerce fields.

Batch fields describe the current product information; this is not per-lot inventory management. Variable products need variation-specific data in native attributes/descriptions until a dedicated batch system is justified.

## Checks and limits

CI exercises mobile acceptance/rejection, cross-country delivery rejection and COD confirmation status without writing orders or sending messages. PHP lint covers the theme, core and tests. These checks do not prove checkout, shipping, stock, email, bot protection, payments or recovery end to end.

Approved prices/stock, real product photos, business address, courier rates, bank details, contact/policies and transactional-email authentication are still needed for launch. No price, stock, courier promise, medical claim, review or bank information is fabricated to make a demonstration purchasable.

## Disable / rollback

Deactivate Maqwi Store Core through Plugins to remove its checkout/product display rules. Metadata remains in the database. Switch back to the prior cart/checkout revisions if returning to Blocks. Native WooCommerce records are not deleted. Keep Coming soon enabled while changing checkout.

The commerce installer snapshots the four modified theme files before replacement. Its source and target allowlists exclude WordPress root/config, other themes/plugins and customer data. On upload failure it restores modified source and removes only newly created files. Runtime failure requires a reviewed FTPS rollback to that source snapshot and removal of the two new commerce templates; deactivating the core separately does not roll back the theme.

The WooCommerce > Maqwi store setup screen shows product counts, visibility, classic-page/COD setup and installed code/schema versions. Its setup action preserves native page revisions and requires capabilities and nonce verification. Its delivery/email guidance is a checklist, not an automatic integration health test. Native on-hold email wording must be checked for COD confirmation and delivery authenticated before launch.

## Public brand mode — core 0.1.5

The owner approved a public brand introduction with ordering closed. `maqwi_brand_launch` defaults to `yes` when absent. The core disables simple/variation purchasability, removes available gateways and rejects classic checkout validation while brand mode is enabled. Public shop/cart/checkout/account/product routes return to the collection; known category routes return to the corresponding expandable brand section. Native admin remains available.

The nonce/capability protected owner action enables the ordering guard before removing WooCommerce Coming soon. It makes no DNS changes and does not enable sales. There is no one-click sales toggle in this release. Opening orders requires a separate reviewed release, real approved catalogue/shipping/support/policy data, revised public content, authenticated email, completed-order tests and recovery checks.


## Full storefront preview — Core 0.2.5

Owner-authorised admin action: WooCommerce → Maqwi store setup → Build sample catalogue and enable storefront preview. Requires manage_woocommerce, publish_products, publish_pages and a valid native nonce. It enables the sales guard first, creates four native variable sample products with two variations each and six editable information pages, then enables storefront preview. It never overwrites existing page content or existing matching sample products. Conflicting non-sample product slugs cause an error. Ordinary public visits cannot seed data.

Products: Maqwi Honey (250g/500g; sample PKR850/1500), Desi Ghee (250g/500g;1100/2000), Achaar (250g/500g;450/800), Itar (6ml/12ml;650/1150). These are example prices and sizes, not approved offers. Edit native products in Products → All Products; prices live in Variations. Descriptions and Maqwi product metadata are editable in the product editor. Change the featured image to replace the theme's concept-image fallback. No sample product uses managed stock; no real inventory count is claimed.

Preview allows normal catalogue/search/category/product routes and cart interaction only for records marked _maqwi_sample=yes, including their variations. It retains native product restrictions. Gateways remain empty; classic checkout validation rejects orders, an additional pre-save order creation guard throws before persistence, and Store API checkout reads and writes return403 to prevent draft-order creation. Public account registration is disabled. Native account login is displayed but no account credentials are created by setup or QA.

A labelled sample Pakistan delivery rate (PKR250) makes cart and checkout totals testable. No real shipping zone or courier contract is changed. Before selling, replace sample records/claims/photos, configure actual stock/delivery, approve legal/support information and verify email/order flows. The order guard must remain enabled until that separately reviewed release. Do not treat the frontend preview as an approved store launch.

Information pages: /about/, /contact/, /faq/, /delivery/, /returns/, /privacy-preview/. They contain working brand/FAQ content and clearly marked pending business details, not invented contact numbers or approved policy terms. Existing privacy configuration is unchanged.


The sample delivery adapter is a native non-zone WooCommerce shipping method registered only in preview. This prevents the native zero-method count from skipping delivery calculation; no real shipping-zone or method-instance record is added. Native count/package caches are invalidated with the code revision. Before a real-store release, configure and test genuine shipping methods and clear shipping caches when disabling preview.
