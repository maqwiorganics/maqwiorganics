# Maqwi UI releases

Owner instruction, 18 October 2026: publish authorized UI changes to the live temporary site after verification; do not leave completed changes on a staging-only branch. Use main for these releases. This does not authorize public ordering, payments or production domain/DNS changes.

The hero release publishes only functions.php and style.css. A read-only live snapshot establishes the reviewed baseline; source backups must be persisted before remote writes. Deployment rechecks every live hash, uploads and verifies temporary files, renames into place and restores attempted files on failure. Public browser acceptance follows publication. Update the reviewed baseline for each later release; never remove its guard.
