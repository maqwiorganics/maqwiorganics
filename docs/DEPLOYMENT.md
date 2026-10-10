# Deployment policy — Maqwi Organics

## Current state
No FTP connection, workflow, or automatic deployment is enabled.

## Deployment scope
Deploy only reviewed and explicitly allowlisted custom theme/plugin files into the correct WordPress `wp-content` directories. Never sync the WordPress root or use a mirror/delete operation.

## Prerequisites
1. Verify site owner/admin access and current WordPress setup.
2. Identify InfinityFree account's actual FTP host, username, document root and site directory using the hosting panel, not guesses.
3. Verify parent theme and WooCommerce compatibility before writing a child theme.
4. Obtain restorable database and site-file backups; verify rollback steps.
5. Configure FTP secrets in GitHub Actions repository settings; do not place passwords in commits or chat.
6. Require manual approval for deployments and stage code validation first.
7. Validate on the temporary development site; never change the real domain or DNS without authorization.

## Known limitation
FTP controls files, not WordPress settings, plugin activation, database products, orders or site health. Those operations need verified alternative administrative access.

## Free hosting constraints
Confirm API reachability, cron/scheduled actions, outbound email, plugin requirements and PHP resource limits through actual end-to-end tests. Do not accept live orders before checkout and notifications are proven.
