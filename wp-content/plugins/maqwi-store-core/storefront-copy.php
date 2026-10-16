<?php
/** Fixed customer-copy revision. Prices, inventory and order controls are unchanged. */
defined( 'ABSPATH' ) || exit;

function maqwi_customer_copy() {
    return array(
        'Rooted in everyday traditions' => 'The Maqwi collection',
        'Find your familiar favourite.' => 'Explore the range.',
        'Four traditions. One thoughtful beginning.' => 'Honey · Desi Ghee · Achaar · Itar',
        'A personal ritual, a familiar fragrance.' => 'Personal fragrance in a 12 ml bottle.',
        'A familiar finishing touch to your table.' => 'For roti, rice, dal and everyday cooking.',
        'The little extra beside a favourite meal.' => 'Serve alongside paratha, rice or dal.',
        'The Maqwi beginning' => 'About Maqwi',
        'Old favourites.<br>A new chapter.' => 'Maqwi Organics.<br>Made for your everyday.',
        'Some things belong to more than a meal. A spoonful of ghee. Achaar passed around the table. The fragrance you remember from home.' => 'Maqwi Organics is a Pakistan-based brand offering honey, desi ghee, achaar and itar.',
        'Maqwi begins with those everyday traditions. We’re building a collection around clear product information and the stories behind what you choose.' => 'Explore the range and find pack sizes, prices and product information on each listing.',
        'What we are building towards' => 'Product information',
        'Details that earn your trust.' => 'Choose with confidence.',
        'The story behind it' => 'Explore the range',
        'Where a product comes from and how it is prepared, described with care.' => 'Honey, desi ghee, achaar and itar, all in one collection.',
        'The details that matter' => 'Check your pack size',
        'Ingredients, pack sizes and storage guidance, clearly stated for each product.' => 'See the listed net weight or volume when choosing a product.',
        'A clear next step' => 'Read the label',
        'Payment, delivery and support information before you place an order.' => 'Follow the product label for ingredients, allergens and storage instructions.',
        'Before we begin' => 'Common questions',
        'A few things<br>you may wonder.' => 'How can we help?',
        'Orders are not open yet. We are preparing the collection and testing the store before launch.' => 'Ordering opens soon. You can browse the collection and product details now.',
        'Our starting range is honey, desi ghee, achaar and itar. The final variants, sizes and prices will be listed when the collection is ready.' => 'Maqwi Honey: 1 kg, PKR 4,500. Desi Ghee: 1 kg, PKR 3,500. Itar: 12 ml, PKR 200. Achaar is coming soon.',
        'We are preparing for customers in Pakistan. Delivery areas, charges and expected timings will be confirmed before orders open.' => 'Delivery information for Pakistan will be published here when ordering opens.',
        'A thoughtful beginning' => 'Discover Maqwi',
        'Tradition has a place here.' => 'Find your favourites.',
        'We’re taking the time to prepare the details. Come back soon to meet the Maqwi collection.' => 'Browse honey, desi ghee, achaar and itar in the Maqwi collection.',
    );
}

function maqwi_customer_pages() {
    return array(
        'about' => array( 'Our Story', '<p class="mq-eyebrow">Maqwi Organics · Pakistan</p><h2>Welcome to Maqwi</h2><p>Maqwi Organics is a Pakistan-based brand bringing together honey, desi ghee, achaar and itar.</p><p>Our range covers everyday cooking, the breakfast table and personal fragrance. Browse each product for its pack size, price and care information.</p><p><a href="/shop/">Explore the collection →</a></p>' ),
        'contact' => array( 'Contact', '<h2>Get in touch</h2><p>Customer support contact details will be available here when ordering opens.</p><p>For now, visit our frequently asked questions for information about the collection and availability.</p><p><a href="/faq/">Frequently asked questions →</a></p>' ),
        'faq' => array( 'Frequently Asked Questions', '<details><summary>Can I place an order?</summary><p>Ordering opens soon. You can browse the collection and product details now.</p></details><details><summary>What are the prices and pack sizes?</summary><p>Maqwi Honey is PKR 4,500 for 1 kg. Desi Ghee is PKR 3,500 for 1 kg. Itar is PKR 200 for 12 ml. Achaar is coming soon.</p></details><details><summary>Why does the pictured pack show a different size?</summary><p>Some images show a different pack size. The net weight or volume stated on the product listing applies to your selected pack.</p></details><details><summary>Where do you deliver?</summary><p>Delivery areas, charges and estimated timings for Pakistan will be published when ordering opens.</p></details><details><summary>Which payment methods can I use?</summary><p>Available payment methods will be listed when ordering opens. Payments are currently unavailable.</p></details><details><summary>Where can I find ingredient and allergen information?</summary><p>Read the product details and the supplied label before use. Do not assume a product is allergen-free.</p></details><details><summary>What is your returns policy?</summary><p>Return and cancellation terms will be available on our Returns page before orders open.</p></details><p><a href="/shop/">Browse products →</a></p>' ),
        'delivery' => array( 'Delivery Information', '<h2>Delivery within Pakistan</h2><p>Ordering opens soon. Delivery coverage, charges and estimated timings will be published here before you can place an order.</p><p><a href="/faq/">Frequently asked questions →</a></p>' ),
        'returns' => array( 'Returns & Order Support', '<h2>Returns and cancellations</h2><p>Ordering is currently unavailable. Return eligibility, cancellation terms and the process for reporting damaged or incorrect items will be published here before orders open.</p><p><a href="/contact/">Contact information →</a></p>' ),
        'privacy-preview' => array( 'Privacy Information', '<h2>Your browsing session</h2><p>This website uses WordPress and WooCommerce. Store sessions and browser cookies retain bag selections while you browse.</p><p>Ordering and new account registration are currently unavailable. Please do not submit personal details through checkout.</p><p>Full privacy information, including business contact details and how order information is handled, will be published before ordering opens.</p>' ),
    );
}

add_action( 'init', function () {
    $version = '2026-10-16-copy-1';
    if ( get_option( 'maqwi_customer_copy_release' ) === $version || ! get_option( 'maqwi_catalogue_release' ) || ! maqwi_storefront_preview() ) { return; }
    if ( ! add_option( 'maqwi_customer_copy_lock', $version, '', false ) ) { return; }
    try {
        $snapshot = array(); $products = array(); $pages = array();
        foreach ( maqwi_launch_catalogue() as $slug => $item ) {
            $post = get_page_by_path( $slug, OBJECT, 'product' );
            $product = $post ? wc_get_product( $post->ID ) : false;
            if ( ! $product || '2026-10-16-catalogue-1' !== $product->get_meta( '_maqwi_catalogue_release' ) ) { throw new Exception( 'Copy revision product ownership check failed.' ); }
            $products[ $slug ] = $product;
            foreach ( array_merge( array( $product->get_id() ), $product->get_children() ) as $id ) {
                $p = wc_get_product( $id );
                $snapshot['products'][ $id ] = array( 'description' => $p->get_description(), 'short' => $p->get_short_description(), 'note' => $p->get_meta( '_maqwi_photo_note' ) );
            }
        }
        foreach ( maqwi_customer_pages() as $slug => $copy ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( $page && 'yes' === get_post_meta( $page->ID, '_maqwi_sample_page', true ) ) {
                $pages[ $page->ID ] = $copy;
                $snapshot['pages'][ $page->ID ] = array( $page->post_title, $page->post_content );
            }
        }
        $media = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'meta_key' => '_maqwi_photo_20261016' ) );
        foreach ( $media as $attachment ) { $snapshot['captions'][ $attachment->ID ] = $attachment->post_excerpt; }
        if ( ! add_option( 'maqwi_customer_copy_before_20261016', $snapshot, '', false ) ) { throw new Exception( 'Copy snapshot already exists.' ); }
        foreach ( $products as $slug => $product ) {
            $item = maqwi_launch_catalogue()[ $slug ];
            $product->set_description( $item['description'] );
            $product->set_short_description( $item['short'] );
            $product->update_meta_data( '_maqwi_photo_note', $item['photo_note'] );
            $product->save();
            foreach ( $product->get_children() as $id ) {
                $variation = wc_get_product( $id );
                if ( 'publish' === $variation->get_status() ) { $variation->set_description( '' ); $variation->save(); }
            }
            wc_delete_product_transients( $product->get_id() );
        }
        foreach ( $pages as $id => $copy ) {
            wp_save_post_revision( $id );
            $result = wp_update_post( array( 'ID' => $id, 'post_title' => $copy[0], 'post_content' => '<!-- wp:html -->' . $copy[1] . '<!-- /wp:html -->' ), true );
            if ( is_wp_error( $result ) ) { throw new Exception( 'Customer page update failed.' ); }
        }
        foreach ( $media as $attachment ) {
            $result = wp_update_post( array( 'ID' => $attachment->ID, 'post_excerpt' => '' ), true );
            if ( is_wp_error( $result ) ) { throw new Exception( 'Customer caption update failed.' ); }
        }
        update_option( 'maqwi_customer_copy_release', $version, false );
        delete_option( 'maqwi_customer_copy_lock' );
        if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
    } catch ( Throwable $error ) {
        update_option( 'maqwi_customer_copy_error', $error->getMessage(), false );
    }
}, 40 );
