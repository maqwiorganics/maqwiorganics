# Maqwi Honey Spoons — product launch specification (19 October 2026)

**Status:** Owner approved the 10 × 7 g, PKR 899 single-pack offer on 19 October 2026. The staff-only draft creator is prepared in GitHub staging. Not deployed to production. Do not publish or accept orders for this item before the remaining evidence is complete.

## Product

- Customer name: **Maqwi Honey Spoons — 10 Pack**
- Slug: `maqwi-honey-spoons`
- Proposed unique SKU: `MQ-HSP-010X007` (check against the actual WooCommerce catalog before creation)
- Category: Honey
- WooCommerce type: simple product, one pack per item ordered
- Pack contents: **10 individually packed, single-use spoons**, each pre-filled with **7 g honey**
- Total net honey: **70 g** (10 × 7 g); packing and spoon weight are additional and have not been verified
- Approved retail price: **PKR 899 per 10-pack**
- Usage framing: 1 spoon per day is a convenient **10-day supply**, not a health recommendation
- Nationwide shipping plan: **PKR 300 per order in Pakistan**, subject to the shipping setup and checkout tests
- Initial stock quantity: **NOT PROVIDED** — draft must stay at 0/out of stock; never invent an opening quantity
- Availability: draft, hidden until the physical product, pack labeling, photos and stock are verified

## Customer-facing listing

**Title:** Maqwi Honey Spoons — 10 Pack

**Short description:**
A convenient way to enjoy honey, one spoon at a time. Ten individually packed, pre-filled spoons with 7 g of honey each. 70 g total honey per pack.

**Full description:**

### Honey, ready when you are.

Bring a little honey wherever your day takes you. Maqwi Honey Spoons are individually packed, single-use spoons, each containing a 7 g portion of honey for simple, measured serving.

Open one spoon and enjoy the honey with food or drinks in accordance with the final packaging instructions. No jar to carry and no portion to measure.

### What's in the pack

- 10 individually packed honey spoons
- 7 g honey per spoon
- 70 g total honey
- PKR 899 for the pack

Using one spoon a day, a pack lasts ten days.

**Important:** Honey is not suitable for infants under 12 months. Final ingredient, allergen, batch, storage, best-before, packaging and use instructions must be checked against the actual label.

## Photography brief — real product only

1. Main hero: all ten **actual** sealed spoons inside or beside the real branded 10-pack packaging on a cream background.
2. Individual product: close-up of one unopened honey spoon clearly showing its contained 7 g serving and hygienic seal.
3. Size illustration: physical 10-pack shown together, with an accurate statement `10 × 7 g = 70 g honey`.
4. Lifestyle: one spoon used beside a breakfast plate or cup; **do not depict immersion in boiling/hot liquids** unless the food-contact material is proven temperature-safe.
5. Pack reverse: legible ingredients, allergens, batch, expiry, manufacturer, storage, single-use disposal and contact information as legally appropriate.

The final spoon and wrap material are **unknown**. Do not claim they are edible, recyclable, compostable, BPA-free, sterile, plastic-free, safe at high temperatures, leakproof or laboratory tested without documentation.

## Native WooCommerce integration rules

- Create only after an authenticated staff administrator submits a nonce-protected action under WooCommerce → Honey Spoons draft.
- Check for an existing product by slug and SKU; fail closed instead of creating duplicates or updating a non-owned record.
- Set simple product status to `draft`, catalog visibility `hidden`, regular price 899, stock management true, quantity 0, backorders disabled, stock status out of stock.
- Assign the existing Honey category when it exists. Do not set shipping weight from the 70 g net honey (outer pack weight is different).
- Do not run this as a recurring inventory sync. Opening stock requires verified supplier inventory and a separate authenticated update.
- Do not enable payments/checkout as part of adding the product. Do not deploy the old staging plugin over the hardened live 0.3.3 version without reviewed source reconciliation.

## Remaining physical-product confirmations before publication

- Verified pack-level stock quantity
- Actual spoon material, food-contact and temperature-use instructions
- Real product and packaging photography
- Final ingredient/label, sourcing, storage, expiry and safety information
- Physical fulfillment and leak/damage checks

## Approved economics and bundle references (retail, not cost)

- 1 kg Maqwi Honey remains PKR 4,500 (unchanged).
- Honey retail equivalent for 70 g at the existing jar price: PKR 315.
- Spoon-pack price PKR 899 = PKR 89.90 per spoon; the PKR 584 difference versus jar-equivalent retail honey is **not** profit; individual food-safe packaging, filling, labour, wastage and operational expenses are still unknown.
- Proposed Honey Spoons Duo: 20 spoons (140 g), PKR 1,699 before shipping.
- Proposed Honey Spoons Monthly Pack: 30 spoons (210 g), PKR 2,399 before shipping.
- Proposed gift products and source-of-truth component prices are in `config/offer-plan.json`. They are proposed until actual costs and product compliance are verified.
- Flat-rate PKR 300 shipping is added **per Pakistan delivery order**, not included in any product price.
