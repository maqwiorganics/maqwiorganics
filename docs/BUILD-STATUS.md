# Maqwi build status

## Foundation v0.1.0

Source: latest supplied master PRD v3.1 and technical requirements v2.0. Visual brief informs styling; its older Shopify references are superseded by WordPress/WooCommerce.

- Kadence child theme at `wp-content/themes/maqwi-organics/`.
- Native homepage pattern available as **Maqwi home** in the block inserter.
- Homepage fallback renders that pattern if the site uses latest posts or an empty front page. A populated static front page retains its own content until intentionally replaced.
- Homepage-specific header/footer avoid overriding unverified parent commerce markup.
- Native keyboard-operable menu/FAQs, visible focus and skip link; responsive category grid.
- Supplied original logo retained, locally optimized to WebP. It has an opaque white background; approved transparent master is still needed.
- Four supplied packaging concepts optimized to 42–56 KB each. They are visibly labelled concepts and must be replaced by verified product photography before launch. Claims printed within these images are unverified and must not become launch claims.
- Featured section queries WooCommerce public featured products and excludes hidden, unavailable and non-purchasable items. Prices come from WooCommerce. No invented catalogue, prices, reviews or support details.
- No page builder, external font, tracking script or newsletter form dependency.

## Current installation status

2026-10-11: WordPress browser connection received 502 Bad Gateway on admin/login pages. Backup and Kadence installation task made **no changes**. Theme code is in staging, not installed or activated. No production/DNS changes.

## Next installation sequence

1. Confirm admin responds and existing saved session remains valid.
2. Create and verify database + plugins + themes + uploads + other-file backup. Copy backups to independent private storage. Preserve config securely outside the public repository. Record timestamp and rollback route.
3. Install Kadence from the official directory; verify installed parent version and WooCommerce integration.
4. Deploy only the reviewed child theme directory via FTPS; no root sync or delete.
5. Preview child theme before activation, then activate on temporary development site, keeping Coming soon and search indexing disabled.
6. Create an editable Home page using Maqwi home pattern if needed, assign static front page and navigation. Review parent shop/product/cart/checkout templates before altering them.
7. QA 320/390/768/1440px, menu keyboard behavior, FAQ keyboard behavior, links and inherited WooCommerce screens. No claim of runtime compatibility until this passes.

## Unresolved launch data

Final SKU variants, prices, real stock, ingredients, source, batch/storage information and photography; business address/support contact; delivery rates/courier; approved policies; bank details; authenticated transactional email and analytics IDs. These do not block theme development but block accepting customer orders.

## Hosting findings

Prior audit: REST/loopback 403 in Site Health; external REST request returned a browser-challenge HTML document; overdue scheduler actions and a WooCommerce migration warning. Recheck after backup; do not run database migrations without a recovery baseline. Coming soon remains required while checkout, notifications and recovery are unproven.
