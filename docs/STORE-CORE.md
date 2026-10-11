# Store behaviour — commerce phase

Maqwi Store Core is a small site-owned plugin. Checkout and catalogue rules stay active when the visual theme changes. It depends on WooCommerce, declares HPOS compatibility and makes no activation-time migrations or external requests.

## Classic checkout

Use native Shortcode blocks containing `[woocommerce_cart]` and `[woocommerce_checkout]` on the existing assigned WooCommerce pages. The plugin's field customisations intentionally target shortcode checkout, not the Checkout block.

Full name replaces separate name inputs; company and address line two are removed. Country is hidden with Pakistan as default, and server-side validation rejects another billing or delivery country. Province and city remain required; postcode and nearby landmark are optional. Native WooCommerce account, order, nonce and payment processing remain in use.

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
