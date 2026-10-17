# Three-scene Maqwi hero

## Release scope

Temporary site only, staging branch. Keeps the approved navigation and existing store/order controls. Rebalances the headline into three lines, shortens the description, removes the repeated category line, reduces the image scale and places the collection within a standard 1366 × 768 laptop viewport. The hero uses an uncropped 3:2 image panel with a stable height. The first image is prioritised; remaining images warm after the initial page load.

Three slides show the complete collection (including Itar), honey and ghee at the table, and Itar. Each image is 1440 × 960 WebP. Honey and ghee labels read 1 kg; Itar reads 12 ml; no unapproved achaar pack size or price is introduced. These campaign images represent the catalogue and do not independently verify physical packaging. Existing product-gallery pack notes remain relevant to those older photographs.

## Interaction

Seven-second automatic rotation, previous/next controls, three selectors, slide label and pause/play. Manual selection and keyboard focus pause rotation. Hover, hidden tabs and an offscreen carousel suspend timers. Reduced-motion users start paused, with no transition animation. Horizontal touch swipes and arrow keys select slides. The first photograph and primary links remain available without JavaScript; inactive controls stay hidden.

## Assets and generation

Built-in image generation with approved packaging, Itar bottle and authoritative logo references. No API/CLI fallback. Final assets:

- wp-content/themes/maqwi-organics/assets/images/hero-collection-v3.webp
- wp-content/themes/maqwi-organics/assets/images/hero-table-v3.webp
- wp-content/themes/maqwi-organics/assets/images/hero-itar-v3.webp

Shared prompt: Use case: ads-marketing. Asset type: responsive website hero slide; landscape 1536x1024. Input 1 is packaging/style reference, input 2 is itar reference, input 3 is authoritative logo reference. Maintain cream, forest green and muted gold palette. No text overlays, no UI, no border, no watermark. Only legible product labels. Preserve existing brand marks exactly. This is one photograph, not a collage or contact sheet.

Collection prompt: A single wide premium Maqwi Organics campaign photograph, all four products together on warm cream travertine stone: golden honey front, desi ghee left behind, achaar right behind, small elegant gold-capped itar bottle at right front. Keep realistic scale: itar bottle much smaller than jars. Correct only honey and ghee labels to exactly '1 kg'. Itar '12 ml'. Achaar no quantity visible, label name only. All products fully inside frame, airy composition. Subjects occupy central 65% width and 65% height, generous cream margins on all sides. Show stone surface and a little forest green linen. Warm natural sunlight, refined realistic studio product photography.

Table prompt: A single wide premium Maqwi Organics editorial breakfast-table photograph with just honey and desi ghee jars, both labels exactly '1 kg', arranged on a cream stone counter with a small piece of roti on a minimal ceramic plate and a wooden honey dipper. Preserve Maqwi label artwork, correct Urdu logo, glass jars and gold lids from references. No other products. Airy premium photography, both jars fully visible with ample margins. Subjects occupy central 60% width and 65% height. Warm cream wall and a subtle forest green linen fold, soft morning side light. Not a close crop.

Itar prompt: A single wide premium Maqwi Organics personal-fragrance editorial photograph with the exact small gold-capped transparent 12 ml ITAR bottle from reference. Keep exact bottle silhouette, updated Urdu logo, label, typography, and '12 ml'. Bottle on a low cream travertine plinth with forest green linen draped quietly behind, warm architectural wall and soft window shadows. No flowers or woods that imply unconfirmed scent ingredients. Elegant perfume photography, small product fully visible, occupies central 45% width and 65% height, generous cream negative space and refined golden reflections. No food products.

## Verification and rollback

The guarded deployment snapshots existing owned source, checks previous code hashes, verifies new assets are absent, then uploads image assets before their consumers. Text source readback is exact; host image recompression is permitted only with identical dimensions and maximum per-channel RMS difference 8/255. Errors restore original source and remove only the new assets.

The dependent browser review tests seven viewport widths, all slides and controls, reduced motion, automatic rotation, pause, site-script-disabled fallback, headline wrapping and laptop hero height. It also runs the existing 72-page catalogue checks. The standalone public QA job is skipped for commits marked [hero-release] because these same checks must follow deployment rather than racing it.

The first seven-width interaction review passed layout, manual navigation, autoplay, pause and reduced-motion checks. A fully JavaScript-disabled fresh browser could not enter the shared host. The fallback review therefore leaves hosting entry scripts intact and blocks only the site-owned public.js file; this verifies progressive enhancement without bypassing the host challenge. The three optimized scenes total 347,430 bytes; each remains below 150,000 bytes. The total theme budget is 900,000 bytes to include the three approved scenes, compared with the previous one-scene budget of 700,000 bytes. The reviewed theme totals 886,533 bytes. These checks run before the guarded image refinement.

## Larger photographic treatment

The owner reviewed the inset layout and requested stronger product scale. The refinement restores an edge-to-edge desktop campaign treatment with a cream fade behind the copy, larger imagery and controls over the photograph. Desktop height is bounded at 620–680 px; the 1366 × 768 laptop view retains the start of the collection. Tablet photography fills a 440 px panel below the copy; phone photography extends to both viewport edges in a 4:3 panel. The three scenes, corrected hero label sizes, headline, shorter copy and slideshow interactions are retained. The Itar-only desktop image uses contain sizing to retain the complete bottle.

## Minimal studio imagery revision

The owner approved the larger hero layout but requested smaller objects inside the photographs and removal of irrelevant food and fabric props. The layout dimensions and slideshow behaviour stay the same. The three campaign assets are replaced with minimal studio scenes: the four-product collection, Honey & Ghee on low ivory plinths, and Itar on a pale stone plinth. A new image query revision avoids stale browser copies. Product labels retain the listed 1 kg honey/ghee and 12 ml Itar; no unconfirmed achaar quantity appears.

Generation mode: built-in image generation, editing each preceding hero photograph with the authoritative logo reference. New prompt set supersedes the earlier styling instructions:

Shared prompt: Professional minimal studio campaign photograph for a website hero, landscape 1536x1024. Preserve product packaging design and updated green-and-gold Urdu logo. Zoom out substantially; products must look smaller and properly scaled rather than oversized close-ups. Seamless warm ivory/cream studio cyclorama, fine matte limestone or ivory plinths, delicate geometric architectural light and shadow, diffused directional studio light, controlled glass and gold reflections. Remove all cloth, fabric, linen, drapery, roti, chapati, bread, plates, bowls, honey dippers, spilled honey, flowers, leaves, branches, plants, scattered food, ingredients and clutter. Only specified packaged products and geometric display plinths. Generous negative space on all sides. No claims, certification badges, text overlays, UI, watermark or border. Label size text exactly 1 kg for honey/ghee and 12 ml for Itar; achaar has no quantity. Legible product names and Maqwi branding.

Collection: Exactly four products, with realistic relative scale. Three spaced jars and the small Itar bottle forward at the right. Group width around 58% of image; tallest jar 43% of image height; grouping slightly right of centre on one or two low rectangular pale limestone platforms.

Honey & Ghee: Exactly two jars, each marked 1 kg, at two subtle depths on low matte ivory geometric studio plinths. Each jar at most 43% of frame height; grouping in the middle 48% of image width, slightly right of centre. Camera pulled back, no other objects.

Itar: Exactly one small Maqwi Itar 12 ml bottle retaining its transparent glass shape, gold domed cap and label. Entire bottle 45% of frame height with generous clear space, on one understated pale limestone cube, slightly right of centre. No other objects.

## Automatic image and copy scenes

The owner requested automatically changing images with relevant copy and no slider buttons. The control bar, arrows, dots, counter and pause/play buttons have been removed from the HTML. Every seven seconds the scene updates its photograph, three-line headline, description and primary-link label/destination in a single synchronous update, using PHP-authored data attributes. Scenes introduce the collection, Honey & Ghee, and Maqwi Itar. The separate Our Story link remains stable. Text reserves sufficient space to avoid vertical movement between descriptions.

There is no hover-based rotation stop, which previously made the carousel appear static while a pointer rested over it. Keyboard focus in the hero pauses rotation so links remain stable while being used; Escape stops rotation. Hidden tabs and offscreen scenes suspend the timer. Reduced-motion preferences keep the initial scene static. The first scene remains readable if the site-owned slideshow script is unavailable. Browser checks observe real automatic changes, compare image/copy/link metadata for each scene at seven widths, confirm the absence of slider controls, and verify focus pause, reduced motion and script-disabled fallback.
