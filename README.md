> **Catalogue review — 16 October 2026**: https://maqwiorganics.gt.tc/. Theme0.5.0 / Store Core0.3.0. Four native product galleries and descriptions, owner-approved Honey1kg PKR4500, Ghee1kg PKR3500 and Itar12ml PKR200. Achaar pricing/pack sizes and real inventory remain unconfirmed; orders stay closed. Public catalogue acceptance38869235803 passed52 page/width checks. See docs/CATALOGUE-RELEASE-20261016.md for release and sales prerequisites.

# Maqwi Organics

WordPress + WooCommerce storefront for Pakistan, developed on InfinityFree at https://maqwiorganics.gt.tc/.

- `staging`: active development and validation.
- `main`: controlled baseline; no automatic website deployment.
- `wp-content/themes/maqwi-organics/`: Kadence child theme with editable native homepage and commerce presentation.
- `wp-content/plugins/maqwi-store-core/`: checkout rules, verified product details and the owner setup screen.
- `docs/BUILD-STATUS.md`: delivered work, installation state and remaining requirements.
- `docs/DEPLOYMENT.md`: guarded installation and rollback.
- `docs/STORE-CORE.md`: checkout/catalogue behaviour and owner instructions.

The storefront theme and site-owned commerce core are installed and activated on the temporary site after backup and Live Preview. The editable Home page is published as the static homepage. Pakistan store settings and four product categories are configured; desktop category, FAQ, shop and cart navigation passed. Source and validation live in staging. Shop, product, cart, checkout and account presentation share the brand shell. Classic cart/checkout and COD confirmation setup are implemented, with a WooCommerce > Maqwi store setup screen for the owner. The approved brand introduction is public with fail-closed ordering guards. Real catalogue data, shipping/email integrations and completed-order QA are still in development. The real domain and DNS remain untouched.

Only custom source and selected brand assets belong here. Never commit credentials, config, customer/order data, database exports, 2FA codes, source archives or purchased theme packages.
