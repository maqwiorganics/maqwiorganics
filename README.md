# Maqwi Organics

WordPress/WooCommerce on the existing InfinityFree temporary domain. Public ordering remains closed.

- `staging`: integrated storefront source, approved business configuration and release work.
- `main`: controlled baseline; source promotion is separate from hosting deployment.
- No push automatically deploys a theme or plugin. The guarded plugin workflow is manual-only on main and requires a reviewed live-source hash baseline.
- Approved products, stock, PKR 300 Pakistan-wide shipping and contact details: `config/approved-store.json`. This manifest is not an applied database configuration.
- Current evidence, remaining integration blockers, deployment safeguards and WooCommerce application steps: [completion status](docs/COMPLETION-STATUS-20261017.md).
- Existing backup inventory and recovery procedure: [recovery](docs/RECOVERY.md). Preserve those archives and private recovery keys.

Do not commit credentials, wp-config.php, database dumps, orders, customer details or recovery keys. Never enable ordering or payments as part of deployment. Launch requires completed tests and a separate owner decision.
