# Maqwi Organics — Honey Spoons, bundles and gifting implementation plan
Date: 19 October 2026

**Implementation state:** Commercial plan approved for the 10-spoon flagship only; all other offers are pricing proposals pending verified supplier and fulfillment costs. All changes reside in GitHub `staging`. No production WooCommerce mutations are approved by this document. Public ordering and payments stay closed.

## A. Immutable business constraints

- Existing product prices: Honey 1 kg PKR 4,500; Desi Ghee 1 kg PKR 3,500; Achaar 500 g PKR 1,500 / 1 kg PKR 3,000; Itar 12 ml PKR 200. **Never adjust these prices or current inventory as a side effect of this feature.**
- New approved product: **Maqwi Honey Spoons — 10 individually packed spoons × 7 g = 70 g honey, PKR 899 per pack.**
- One spoon per day is a 10-day supply (serving illustration, not a medical usage recommendation).
- Product slug `maqwi-honey-spoons`, reserved SKU `MQ-HSP-010X007`, category Honey; a simple WooCommerce product.
- No confirmed physical stock quantity or approved final packaging/material/labels/photos. Store draft must have hidden catalog visibility, managed stock quantity zero, backorders disabled, and out-of-stock status.
- PKR 300 shipping per Pakistan order, irrespective of how many products are in a single WooCommerce shipping package, as previously approved and subject to checkout verification. Gift orders to different addresses each require their own shipping charge.
- No COD, bank transfer, payment provider or online orders made accessible by this feature. Preserve the `maqwi_brand_launch` order guard and all existing backups.

## B. Price schedule: merchandise retail, before shipping

| SKU / marketing offer | Constituents | Retail PKR | Status |
| --- | --- | ---: | --- |
| Honey Spoons 10 Pack | 1 pack × 10 × 7 g = 70 g | **899** | Owner-approved flagship |
| Honey Spoons Duo | 2 ten-packs = 20 spoons / 140 g | 1,699 | Proposed; saves PKR 99 versus 2 × 899 |
| Honey Spoons Monthly | 3 ten-packs = 30 spoons / 210 g | 2,399 | Proposed; saves PKR 298 versus 3 × 899 |
| The Everyday Gift | 1 ten-pack + branded gift sleeve, ribbon, card | 1,099 | Proposed; PKR 200 over base pack |
| The Honey Ritual Duo | 2 ten-packs + rigid presentation box/card | 1,999 | Proposed; PKR 300 over Duo |
| Sweet & Scent | 1 ten-pack + 1 Maqwi Itar 12 ml + gift presentation | 1,499 | Proposed; PKR 400 above standalone components (899 + 200) |
| The Signature Honey Gift | Honey 1 kg + one ten-pack + Itar 12 ml + gift box | 5,999 | Proposed; PKR 400 above standalone components (4500 + 899 + 200) |

**Example checkout totals including one PKR 300 shipping charge:** flagship 1,199; Duo 1,999; Monthly 2,699; Everyday Gift 1,399; Honey Ritual Duo 2,299; Sweet & Scent 1,799; Signature Honey Gift 6,299. All totals exclude any separately applicable tax and are illustrations, not proof of hosted checkout configuration. Don't offer unapproved discounts, free shipping, stacking or a paid subscription service.

## C. Price/margin evidence required before publishing bundles

The existing jar retail price is PKR 4,500 / 1,000 g = PKR 4.50 per g, making 70 g retail equivalent **PKR 315**. Approved Honey Spoons retail is **PKR 899** or PKR 89.90/spoon. The PKR 584 difference versus the jar-equivalent RETAIL price is **not gross profit or product cost**.

Collect actual invoices or quotes for:
1. Honey acquisition, filling, food-grade single-use spoon and heat/food-contact compatibility, individual seal, primary and outer pack.
2. Labour, manufacturing losses, QA, batch/traceability label, and food regulatory compliance.
3. Gift sleeves, ribbons, message cards, rigid boxes, void fill and breakage/leak risk per pack.
4. Actual courier charge per parcel, return-to-origin risk and COD/remittance fees, if ever approved.
5. Merchandising, support and platform expenses.

Calculate verified contribution for each offer: **retail price − components' actual landed costs − gift packaging/assembly − allocated fulfillment and gateway/marketing costs**; check shipping revenue versus real courier expense separately. Do not describe retail uplift as margin. Revisit proposed prices if they fail the required target margin.

## D. Rollout stages, with hard pass gates

### Stage 1 — Product and supplier specification (no site changes)
- Confirm 7 g net honey per sealed spoon *by calibrated measurement*, ten per carton, actual pack weight, spoon type/material, fill process, shelf life, humidity/heat storage, courier-safe seal integrity and disposable-use instructions.
- Obtain ingredient/allergen/lot/date/manufacturer/storage and any locally required registrations/label approvals from the responsible supplier/manufacturer.
- Verify that honey is not presented as infant-safe; no unverified `organic` certification, sterilization, biodegradability, recyclability, edible spoon, heat resistance or health benefits.
- Take real photos of ten sealed spoons, a single spoon/label and packaging. If promotional mockups are used internally, mark them clearly as concepts.

### Stage 2 — Deployment and safe product draft (after reconciled source, backed up)
- Reconcile hardened live Maqwi Store Core 0.3.3 against staging before deployment. Never blindly overwrite newer production files.
- Run PHP lint, catalog/offer CI, source-hash guard, fresh verified file and database backups, and a scoped rollout with recovery.
- The prepared `honey-spoons-draft.php` is **not an automatic importer**. Authenticated WooCommerce staff select `Honey Spoons draft` and perform a nonce-protected creation. It rejects duplicate SKU/slug, sets product status draft, quantity 0, hidden visibility and out-of-stock.
- Keep the existing 1 kg Honey, Ghee, Achaar and Itar products intact; never reset their opening quantities.
- Verify WooCommerce draft name, SKU, price, 70 g label, no purchasing URL, category, hidden shop result and checkout block.
- A draft should not be made live or in-stock merely because product copy was approved.

### Stage 3 — Bundle/gifting development (no checkout activation)
- Use `config/offer-plan.json` as a **planning input**, not an automatically applied product importer. All six prices are proposed until actual costs are checked.
- Implement bundles as **server-validated combinations of actual native WooCommerce component products**. One Duo decrements 2 base ten-packs, Monthly decrements 3, Sweet & Scent decrements 1 ten-pack and 1 Itar, Signature decrements 1 ten-pack, 1 Itar and 1 Honey 1 kg. Never introduce independent duplicate stock pools for bundles.
- Avoid overselling: WooCommerce stock reservation, reduction, restoration and refund/cancellation pathways must use actual components. Do not decrement both a wrapper product and its components. Model gift packaging as separately tracked supplies if needed without subtracting honey inventory twice.
- Gift message: optional, sanitized plain text; validate length; staff-only viewing, no public exposure or unneeded retention.
- No discounts stacking, hidden manual checkout links, public draft product ordering, free-shipping triggers, subscription or untested multi-address shipping.
- Display honest inclusions, counts and total honey; selected recipient/address details per order only when public ordering is subsequently authorized.

### Stage 4 — Verification before any launch
- Use an isolated database/store with matching WordPress/WooCommerce and MyISAM behavior to test checkout and stock concurrency first. Restore from backups there to prove recovery, not merely archive integrity.
- Assert SKU/slug uniqueness, 10 × 7 = 70, flagship price 899, all six offer price formulas, catalog hidden status and shipping once per delivery order.
- Confirm mixed carts, stock shortage, duplicate submission, two simultaneous orders, expiration/cancel/refund/reduce/restore stock, failed packaging stock, failed email and payment suppression.
- Run mobile/desktop accessibility (320, 375, 390, 768 px, desktop), screenshots, real sender→customer/admin email delivery, courier charge and gift-unboxing fulfillment checks.
- Do a final hosted prelaunch test with ordering guard still active and write a factual pass/fail report with rollback steps.
- Launch/payments remain off pending a separate owner decision.

## E. Roles, dependencies and next source-control steps

- **Immediately in source control:** approved product specification, corrected staff-only draft creator, pricing plan, source CI assertions, and this document.
- **Needs real-world confirmation before merchant launch:** product inventory count; actual spoon/foil material and food-contact temperature rating; packaging supplier quotes; final photographs and legal product labeling.
- **Needs authenticated WooCommerce access:** create verified draft product and actual native business setup; brand/design and product photography checks; tested shipping, email and recoverable rollback.
- **Needs owner launch confirmation only after successful tests:** publish product, enable payment options/public checkout, configure promotional offer availability.

Do not claim CI passing is proof that any of these changes are deployed to InfinityFree or that the store is ready to take sales.
