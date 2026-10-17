# Recovery baseline — 11 October 2026

UpdraftPlus manual backup completed at the admin-displayed time 12:23:39. Components: database, plugins, themes, uploads and other wp-content files. Restore controls are present. Active theme at backup time: Twenty Twenty-Five. This backup predates the Kadence parent installation and custom theme installation.

An independent encrypted copy also includes the original wp-config.php and .htaccess, plus a SHA-256 manifest. It was downloaded, decrypted with the separate recovery key, and all seven component/configuration hashes were verified. A full restore to a separate WordPress environment remains a launch gate; file integrity alone does not prove application-level recovery.

Persistent files are **Maqwi-Encrypted-Recovery.zip** and **Maqwi-Recovery-Key.pem**, owned by the site owner. The key never enters GitHub. The checked-in recipient certificate contains only a public key. GitHub's encrypted transport artifact expires after seven days; the downloaded persistent copy is the retained recovery baseline.

A separate database-only backup completed at admin-displayed 12:49:07 before the scheduled-action investigation. It remains on the host and is not part of the independent encrypted full baseline.

A further database-only backup was verified at admin-displayed 14:04 before the classic-checkout changes. The commerce installer retained a verified source snapshot in `/htdocs/wp-content/themes/.maqwi-commerce-38127601023`. This source-only snapshot is not a substitute for the independent full backup.

## Recover the components privately

1. Download both persistent files into a private local directory. Extract `maqwi-prebuild.cms` from the ZIP.
2. Decrypt:

```sh
openssl cms -decrypt -binary -inform DER -in maqwi-prebuild.cms -inkey Maqwi-Recovery-Key.pem -out recovery.tar
```

3. Inspect the tar locally, check component hashes against `manifest.json`, and extract into private storage. It contains secrets; never upload it to the public repository or an unrestricted website directory.
4. For WordPress recovery, restore UpdraftPlus components using its authenticated Restore controls. Restore original config and .htaccess through secure hosting access when required; take care to preserve destination database credentials during migration.
5. Verify login, theme, product data, orders and checkout on the recovered site before reopening.

## Theme-only rollback

Activate Twenty Twenty-Five via Appearance > Themes. Custom theme source is versioned in staging and introduces no database migrations. Keep Coming soon enabled throughout recovery.
