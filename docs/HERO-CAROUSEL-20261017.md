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

The dependent browser review tests seven viewport widths, all slides and controls, reduced motion, automatic rotation, pause, no-JavaScript fallback, headline wrapping and laptop hero height. It also runs the existing 72-page catalogue checks. The standalone public QA job is skipped for commits marked [hero-release] because these same checks must follow deployment rather than racing it.
