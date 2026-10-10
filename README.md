# Maqwi Organics

WordPress and WooCommerce storefront project for Maqwi Organics.

## Environments

- `main`: controlled baseline; no automatic deployment.
- `staging`: development changes and review before release.
- Development website: https://maqwiorganics.gt.tc/
- Hosting: InfinityFree (subject to capability and end-to-end compatibility tests).

## Safety

Do not commit WordPress credentials, FTP credentials, application passwords, access tokens, database dumps, 2FA recovery codes, customer/order data, or `wp-config.php`.

Only custom theme and custom plugin source belongs in this repository. Do not vendor WordPress core, private project archives, or commercial theme packages.

Deployment is disabled until we verify the actual WordPress document root, FTP remote path, backup procedure, and rollback strategy. GitHub Actions secrets must be configured securely before any deployment.

## Planned structure

- `wp-content/themes/maqwi-child/` — child theme once parent theme is confirmed.
- `wp-content/plugins/maqwi-core/` — optional small project-specific customizations.
- `docs/` — technical decisions, QA and deployment runbooks.

Current status: repository setup only; nothing deployed to WordPress.
