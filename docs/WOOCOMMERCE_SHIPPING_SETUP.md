# WooCommerce shipping configuration — prepared, NOT applied
Approved 2026-10-17: PKR 300 per order, nationwide Pakistan.

## Intended WooCommerce settings
- WooCommerce > Settings > Shipping > Shipping zones: Pakistan (country-wide).
- One Flat rate shipping method with cost `300` PKR, tax treatment based on store's approved tax setup.
- Do not create duplicate matching shipping zones or methods; inspect existing zones first.
- Avoid a second flat-rate method in 'Locations not covered by other zones'.
- Checkout selling/shipping country Pakistan, store currency PKR.
- Do not force free shipping, local pickup, or amount-dependent fee.
- Protect existing launch guard: leave public ordering disabled.
- Test address in Punjab, Sindh, Khyber Pakhtunkhwa, Balochistan, Islamabad and other serviceable regions, and verify one PKR 300 shipping line per order.
- Verify courier availability and package-weight policies for honey/ghee and multi-item orders before opening real orders.
- Achaar variations 500g / 1kg share five total jars; separate WooCommerce variation counters alone cannot safely enforce shared physical jar inventory.
- Confirm payment method and notification email delivery.
- Never run live paid order testing before account/fulfillment readiness.

## Access state
WordPress admin integration remains unavailable to automation on current InfinityFree environment; settings require verified authenticated path. This document is not evidence of an applied WooCommerce configuration.
