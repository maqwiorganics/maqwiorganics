# Automatic Maqwi health checks

## Operating setup

A ChatGPT task is enabled for 09:00 Asia/Karachi daily, starting 17 October 2026. It uses the connected GitHub app to rerun only the `health` job in `.github/workflows/store-health.yml`, waits for completion and reads `MAQWI_HEALTH_SUMMARY` from the job log. It notifies the owner in simple English for failures, incomplete checks or actionable warnings; healthy checks are silent.

The task does not sign in to the hosting dashboard. GitHub Actions schedules normally run from the default branch; this project retains its unchanged `main` baseline, so the daily trigger is the ChatGPT task rather than a nonfunctional staging-only Actions cron entry. The workflow explicitly checks out current `staging`, including on reruns, and shares the deployment concurrency group so it does not inspect a partially uploaded release.

GitHub and ChatGPT task connections must remain available. Alerts appear through ChatGPT's task delivery; no external email, WhatsApp, customer message or public issue is sent. This is a daily check, not continuous uptime monitoring or an automatic repair service. Repeated shared-host outages cannot be prevented by this task.

## Coverage and limits

- Normal Chrome visits to home, shop and all four product pages at 390 px; checks HTTP status, actual Maqwi markup, page heading, four shop products, approved prices, horizontal overflow and JavaScript errors. An HTTP 200 hosting error page cannot count as success merely because its status is 200.
- One normal reload is permitted for a 502/503/504 response. A visible human-verification block stops the check; no challenge is bypassed or retried in a loop.
- A read-only GET confirms the currently closed Store API checkout guard. No order POST, account creation, checkout submission, stock change or payment takes place.
- Public TLS validation and a warning when the certificate is within 14 days of expiry.
- Backup filename metadata through the existing FTPS connection. Components are grouped by their full backup prefix; unrelated database and file backups cannot be combined into a fictional full set. Warn when no matching full component set exists or its date is over two days old. This does not validate archive integrity, off-site copies or successful restoration.

The check never reads database contents, `wp-config.php`, customer data or backup archive contents. It makes no hosting writes, deployment, DNS, credential, price, inventory, policy or sales-release changes. Dashboard resource quotas and current scheduler/database state remain outside its coverage.

## Initial verification

Run **38885962731**, health job **116923648908**, passed on 16 October. Seven public checks passed: home, shop, four product pages and the intentionally blocked checkout GET. TLS verified; expiry 29 November 2026 at 23:59:59 UTC. No frontend runtime errors.

The report correctly requested attention: **no matching full backup-component filename set was found**. The earlier date-based audit found database/plugins/themes/uploads/others filenames dated 11 October, but grouping components strictly by backup identifier does not establish a complete current set. A fresh full backup and independent restore verification remain outstanding. The owner-retained encrypted historical baseline is a separate earlier recovery artifact, not proof of a current backup.

JavaScript syntax, workflow YAML, embedded Python compilation and the repository's theme/checkout validation passed. Public reports and failure screenshots expire after three days to limit artifact storage. One small daily runner job is used rather than the full 72-page regression suite; usage still counts toward the repository's existing GitHub Actions allowance. No paid plan or upgrade was enabled.

## Maintenance

To run manually, use the **Maqwi daily health check** workflow's dispatch control or rerun its health job. Do not rerun one-release deployment workflows as a monitoring action. Full regressions remain in `tests/frontend/catalogue.cjs` for actual storefront changes.

Update the health expectations when prices or ordering status are intentionally changed. Pause the ChatGPT task when this temporary host is retired, and configure a reviewed monitor for the final hostname. Failures caused by GitHub access or an incomplete check must be reported as monitoring failures, never as healthy hosting.

Database repairs, scheduler inspection and native fresh backups remain blocked: automatic approval review requires explicit owner approval for the hosting-linked `php-myadmin.net` endpoint. Generic "fix everything" authorization was rejected for that private-database connection; no workaround was attempted.
