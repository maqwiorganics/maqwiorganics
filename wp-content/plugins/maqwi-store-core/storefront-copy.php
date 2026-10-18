<?php
/** Fixed customer-copy revision. Prices, inventory and order controls are unchanged. */
defined( 'ABSPATH' ) || exit;

function maqwi_customer_copy() {
    return array(
        'Rooted in everyday traditions' => 'The Maqwi collection',
        'For your morning toast and evening tea.' => 'For breakfast, drinks and everyday recipes.',
        'Can I place an order yet?' => 'When can I order?',
        'What will Maqwi offer?' => 'What is in the collection?',
        'Where will you deliver?' => 'Where will delivery be available?',
        'Welcome to Maqwi' => 'Everyday essentials, brought together.',
        'Maqwi Organics is a Pakistan-based brand bringing together honey, desi ghee, achaar and itar.' => 'Maqwi Organics is a Pakistan-based brand with a collection of honey, desi ghee, achaar and itar, inspired by familiar flavours and fragrances.',
        'Our range covers everyday cooking, the breakfast table and personal fragrance. Browse each product for its pack size, price and care information.' => 'From everyday cooking and the breakfast table to personal fragrance, our products have a place in the way you live. Each listing provides pack sizes, prices and care information to help you choose.',
        'A little extra beside paratha, rice or dal.' => 'An accompaniment to paratha, rice and traditional meals.',
        'A spoonful of sweetness for breakfast, drinks and everyday recipes.' => 'Honey for breakfast, beverages and everyday recipes.',
        'A familiar addition to roti, rice, dal and home cooking.' => 'Desi ghee for cooking and finishing roti, rice and dal.',
        'A practical choice for the everyday dishes you enjoy sharing at home.' => 'Use in everyday recipes or add before serving to suit your preference.',
        'A personal ritual, a familiar fragrance.' => 'Everyday fragrance in a compact 12 ml bottle.',

        'Find your familiar favourite.' => 'Explore our collection.',
        'Four traditions. One thoughtful beginning.' => 'Honey · Desi Ghee · Achaar · Itar',
        'A personal ritual, a familiar fragrance.' => 'Everyday fragrance in a compact 12 ml bottle.',
        'A familiar finishing touch to your table.' => 'For everyday cooking and finishing your favourite dishes.',
        'The little extra beside a favourite meal.' => 'An accompaniment to paratha, rice and traditional meals.',
        'The Maqwi beginning' => 'About Maqwi',
        'Old favourites.<br>A new chapter.' => 'The Maqwi approach.',
        'Some things belong to more than a meal. A spoonful of ghee. Achaar passed around the table. The fragrance you remember from home.' => 'Maqwi Organics is a Pakistan-based brand bringing together familiar flavours and personal fragrance in one considered collection.',
        'Maqwi begins with those everyday traditions. We’re building a collection around clear product information and the stories behind what you choose.' => 'Our range includes honey, desi ghee, achaar and itar. Explore each product for its available pack size, price and care information.',
        'What we are building towards' => 'Product information',
        'Details that earn your trust.' => 'Product details, clearly presented.',
        'The story behind it' => 'Our collection',
        'Where a product comes from and how it is prepared, described with care.' => 'Explore four product categories, from kitchen staples to personal fragrance.',
        'The details that matter' => 'Pack sizes',
        'Ingredients, pack sizes and storage guidance, clearly stated for each product.' => 'Refer to the listed net weight or volume for the pack you select.',
        'A clear next step' => 'Product care',
        'Payment, delivery and support information before you place an order.' => 'Consult the product details and supplied label for ingredients, allergens and storage guidance.',
        'Before we begin' => 'Common questions',
        'A few things<br>you may wonder.' => 'Your questions, answered.',
        'Orders are not open yet. We are preparing the collection and testing the store before launch.' => 'Ordering opens soon. You can browse the collection and product details now.',
        'Our starting range is honey, desi ghee, achaar and itar. The final variants, sizes and prices will be listed when the collection is ready.' => 'Maqwi Honey: 1 kg, PKR 4,500. Desi Ghee: 1 kg, PKR 3,500. Itar: 12 ml, PKR 200. Achaar is coming soon.',
        'We are preparing for customers in Pakistan. Delivery areas, charges and expected timings will be confirmed before orders open.' => 'Delivery information for Pakistan will be published here when ordering opens.',
        'A thoughtful beginning' => 'Discover Maqwi',
        'Tradition has a place here.' => 'Explore the Maqwi collection.',
        'We’re taking the time to prepare the details. Come back soon to meet the Maqwi collection.' => 'View our products, compare pack sizes and find the details you need in one place.',
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
    if ( ! maqwi_allow_automatic_content_releases() ) { return; }
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

/** Apply the approved brand wording to existing public text without changing records. */
add_filter( 'the_content', function ( $content ) {
    if ( is_admin() || ! ( is_front_page() || is_page( array( 'about', 'faq' ) ) || ( function_exists( 'is_product' ) && is_product() ) ) ) { return $content; }
    return strtr( $content, maqwi_customer_copy() );
}, 25 );
add_filter( 'woocommerce_short_description', function ( $description ) {
    return is_admin() ? $description : strtr( $description, maqwi_customer_copy() );
}, 25 );

/** Expire cached public pages once after this wording release. */
add_action( 'wp_loaded', function () {
    if ( ! maqwi_allow_automatic_content_releases() ) { return; }
    $revision = '2026-10-17-brand-copy-2';
    if ( ! get_option( 'maqwi_catalogue_release' ) || get_option( 'maqwi_brand_wording_release' ) === $revision ) { return; }
    update_option( 'maqwi_brand_wording_release', $revision, false );
    if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
}, 50 );
