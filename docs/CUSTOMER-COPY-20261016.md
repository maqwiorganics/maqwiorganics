# Customer copy revision — 16 October 2026

Theme 0.6.1 / Store Core 0.3.1. Source release: a9ce5aded46f1e8588f4d72bbf9f6c1403339a85.

Removed public AI captions and image-gallery captions, the homepage artwork disclaimer, sample-store messages and generic project-development wording. Rewrote the homepage story, product-information sections, product copy and six information pages for customers. Kept the approved hero composition and product photography.

Product copy is native WooCommerce content. A fixed, versioned migration updates only the four owned release products and the six owned information pages. Previous descriptions, variation descriptions, captions and page content are retained in a private, non-autoloaded snapshot (`maqwi_customer_copy_before_20261016`); failures retain a lock for review. No request parameters supply migration content.

Honey remains 1 kg / PKR 4,500; Desi Ghee 1 kg / PKR 3,500; Itar 12 ml / PKR 200. Honey's pictured 500 g jar and ghee's pictured 500 ml jar remain clearly distinguished from the listed 1 kg net weight. Achaar has no invented price or sellable size. Inventory remains unavailable and both classic and Store API order guards remain active. The unapproved sample delivery rate is suppressed for the released catalogue.

Deployment uses exact prior-source hash checks, saved originals, atomic renames, exact readback and source rollback on failure. It changes only the explicitly listed plugin/theme source files on the temporary site. Main, domain and DNS are unchanged.

Acceptance: see GitHub Actions run 38874056833. Source checks include PHP lint, checkout guard tests, approved-offer/pack-size checks and JavaScript syntax. Live browser acceptance covers the home, shop, four products, six information pages, cart, account and four category pages at 320, 390, 768 and 1440 px, plus native galleries, hero navigation, reduced motion and blocked checkout requests. Result: all 72 page/width checks passed, zero browser runtime errors, no broken visible main images or horizontal overflow, four usable gallery views per product, and eight expected blocked Store API requests. Desktop hero, honey product, About page and mobile homepage screenshots were visually inspected.
