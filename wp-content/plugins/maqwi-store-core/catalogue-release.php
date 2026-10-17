<?php
/** Fixed, owner-authorised catalogue revision. No request supplies product data. */
defined( 'ABSPATH' ) || exit;

/** Non-sensitive release health for automated acceptance, never customer data. */
add_action( 'wp_head', function () {
    if ( ! maqwi_storefront_preview() ) { return; }
    $codes = array(
        'Incomplete photography package.' => 'incomplete-images',
        'Photography integrity check failed.' => 'image-integrity',
        'Expected sample catalogue was changed. Review before importing.' => 'catalogue-conflict',
        'A release SKU already exists. Review before importing.' => 'sku-conflict',
        'An earlier catalogue snapshot exists. Review before importing.' => 'snapshot-conflict',
        'Product media could not be saved.' => 'media-save',
        'Product media registration failed.' => 'media-register',
        'Product image metadata could not be generated.' => 'media-metadata',
        'Information page update failed.' => 'page-update'
    );
    $error = get_option( 'maqwi_catalogue_release_error', '' );
    $status = get_option( 'maqwi_catalogue_release' ) ? 'ready' : ( $error ? ( $codes[ $error ] ?? 'runtime-error' ) : 'pending' );
    echo '<meta name="maqwi-catalogue-status" content="' . esc_attr( $status ) . '">' . "\n";
    if ( 'image-integrity' === $status ) {
        $manifest = json_decode( file_get_contents( __DIR__ . '/photography/manifest.json' ), true );
        foreach ( (array) $manifest as $asset ) {
            $path = __DIR__ . '/photography/' . basename( $asset['filename'] );
            $actual = is_file( $path ) ? hash_file( 'sha256', $path ) : 'missing';
            if ( ! in_array( $actual, array( $asset['sha256'], $asset['source_sha256'] ?? '' ), true ) ) {
                echo '<meta name="maqwi-photo-integrity" content="' . esc_attr( basename( $asset['filename'] ) . ':' . $actual . ':' . ( is_file( $path ) ? filesize( $path ) : 0 ) ) . '">' . "\n";
                break;
            }
        }
    }
}, 6 );

function maqwi_launch_catalogue() {
    return array(
        'maqwi-honey' => array(
            'name' => 'Maqwi Honey', 'folder' => 'honey', 'size' => '1 kg', 'price' => '4500', 'sku' => 'MQ-HNY-STD-1000', 'weight' => '1',
            'short' => 'A spoonful of sweetness for breakfast, drinks and everyday recipes. Net weight: 1 kg.',
            'description' => '<h2>Maqwi Honey</h2><p>Bring Maqwi Honey to the table with warm toast, yoghurt, fruit or your favourite breakfast bowl. Stir a spoonful into a drink or use it as a sweet finishing touch in everyday recipes.</p><h3>Your pack</h3><p>Net weight: <strong>1 kg</strong>. Price: <strong>PKR 4,500</strong>. The gallery illustrates the brand packaging in a 500 g jar; it does not show the size of the 1 kg pack.</p><h3>Serving and care</h3><p>Use a clean, dry spoon and close the lid after use. Honey is not suitable for infants under 12 months. Check the supplied label for batch, best-before and storage instructions.</p>',
            'usage' => 'Use a clean, dry spoon. Honey is not suitable for infants under 12 months.',
            'photo_note' => 'Pictured pack: 500 g. Listed net weight: 1 kg.'
        ),
        'maqwi-desi-ghee' => array(
            'name' => 'Maqwi Desi Ghee', 'folder' => 'desi-ghee', 'size' => '1 kg', 'price' => '3500', 'sku' => 'MQ-GHE-STD-1000', 'weight' => '1',
            'short' => 'A familiar addition to roti, rice, dal and home cooking. Sold by net weight: 1 kg.',
            'description' => '<h2>Maqwi Desi Ghee</h2><p>Add Maqwi Desi Ghee while cooking or use a small spoonful to finish warm roti, rice or dal. A practical choice for the everyday dishes you enjoy sharing at home.</p><h3>Your pack</h3><p>Net weight: <strong>1 kg</strong>. Price: <strong>PKR 3,500</strong>. This product is sold by weight. The pictured jar is marked 500 ml. This listing is for 1 kg by weight.</p><h3>Serving and care</h3><p>Use a clean, dry utensil and keep the container closed between uses. Ghee is a dairy product. Check the supplied label for the final ingredient, allergen and storage information.</p>',
            'usage' => 'Use a clean, dry utensil for cooking or as a finishing touch. Dairy product.',
            'photo_note' => 'Pictured pack: 500 ml. Listed net weight: 1 kg. Volume and weight are not interchangeable.'
        ),
        'maqwi-achaar' => array(
            'name' => 'Maqwi Achaar', 'folder' => 'achaar', 'size' => '', 'price' => '', 'sku' => '', 'weight' => '',
            'short' => 'A little extra beside paratha, rice or dal. Pack size, recipe and price will be confirmed before ordering opens.',
            'description' => '<h2>Maqwi Achaar</h2><p>Bring a small helping of Maqwi Achaar to the table with paratha, rice, dal or a home-cooked meal.</p><h3>Before you order</h3><p>Coming soon. Recipe, ingredient and allergen information, pack sizes and prices will be available before ordering opens.</p><h3>Serving and care</h3><p>Use a clean, dry spoon. Follow the final product label for storage and best-before information.</p>',
            'usage' => 'Use a clean, dry spoon. Consult the final ingredient and allergen label before use.',
            'photo_note' => 'Pack sizes and pricing will be available when ordering opens.'
        ),
        'maqwi-itar' => array(
            'name' => 'Maqwi Itar', 'folder' => 'itar', 'size' => '12 ml', 'price' => '200', 'sku' => 'MQ-ITR-STD-012', 'weight' => '',
            'short' => 'Personal fragrance in a compact 12 ml bottle. PKR 200 per bottle.',
            'description' => '<h2>Maqwi Itar</h2><p>Maqwi Itar comes in a compact 12 ml bottle for everyday personal fragrance.</p><h3>Your bottle</h3><p>Net volume: <strong>12 ml</strong>. Price: <strong>PKR 200</strong>. Scent and ingredient information will be available before ordering opens.</p><h3>Use with care</h3><p>For external use only. Avoid contact with eyes and broken skin. Stop use if irritation occurs. Keep the bottle closed, away from heat and out of reach of children. Follow the supplied product label.</p>',
            'usage' => 'For external use only. Avoid eyes and broken skin. Stop use if irritation occurs. Keep out of reach of children.',
            'photo_note' => ''
        ),
    );
}

/** Register the supplied, locally verified assets as normal WordPress media. */
function maqwi_catalogue_media( $filename, $title, $parent = 0 ) {
    $key = '_maqwi_photo_20261016';
    $ids = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'fields' => 'ids', 'numberposts' => 1, 'meta_key' => $key, 'meta_value' => $filename ) );
    if ( $ids ) { return (int) $ids[0]; }
    $source = __DIR__ . '/photography/' . $filename;
    $upload = wp_upload_bits( 'maqwi-' . $filename, null, file_get_contents( $source ) );
    if ( $upload['error'] ) { throw new Exception( 'Product media could not be saved.' ); }
    $id = wp_insert_attachment( array( 'post_mime_type' => 'image/webp', 'post_title' => $title, 'post_excerpt' => '', 'post_status' => 'inherit' ), $upload['file'], $parent, true );
    if ( is_wp_error( $id ) ) { throw new Exception( 'Product media registration failed.' ); }
    update_post_meta( $id, $key, $filename );
    update_post_meta( $id, '_wp_attachment_image_alt', $title );
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $metadata = wp_generate_attachment_metadata( $id, $upload['file'] );
    if ( empty( $metadata['width'] ) ) { throw new Exception( 'Product image metadata could not be generated.' ); }
    wp_update_attachment_metadata( $id, $metadata );
    return (int) $id;
}

/**
 * This fixed release updates only the four previously seeded sample products.
 * It does not accept a URL trigger, payload, price, path or product ID.
 * An option lock prevents overlapping migrations; failure stays locked for review.
 * Original native product/variation/meta data is retained in a non-autoloaded option.
 */
add_action( 'init', function () {
    if ( ! maqwi_allow_automatic_content_releases() ) { return; }
    $version = '2026-10-16-catalogue-1';
    if ( get_option( 'maqwi_catalogue_release' ) === $version || ! class_exists( 'WC_Product_Variable' ) ) { return; }
    if ( ! maqwi_storefront_preview() || ! maqwi_brand_launch_mode() ) { return; }
    // The prior hash check failed before snapshots/media/products were touched.
    // Retry that preflight once for this verifier revision, never a partial import.
    if ( 'final-file-hash-1' !== get_option( 'maqwi_catalogue_integrity_retry' ) && 'Photography integrity check failed.' === get_option( 'maqwi_catalogue_release_error' ) && ! get_option( 'maqwi_catalogue_before_20261016' ) ) {
        update_option( 'maqwi_catalogue_integrity_retry', 'final-file-hash-1', false );
        delete_option( 'maqwi_catalogue_release_lock' );
        delete_option( 'maqwi_catalogue_release_error' );
    }
    if ( ! add_option( 'maqwi_catalogue_release_lock', $version, '', false ) ) { return; }
    try {
        $items = maqwi_launch_catalogue();
        $manifest = json_decode( file_get_contents( __DIR__ . '/photography/manifest.json' ), true );
        if ( ! is_array( $manifest ) || 17 !== count( $manifest ) ) { throw new Exception( 'Incomplete photography package.' ); }
        foreach ( $manifest as $asset ) {
            $path = __DIR__ . '/photography/' . basename( $asset['filename'] );
            $hash = is_file( $path ) ? hash_file( 'sha256', $path ) : '';
            // Accept exact source bytes or the visually verified host readback.
            if ( ! $hash || ( ! hash_equals( $asset['sha256'], $hash ) && ! hash_equals( $asset['source_sha256'] ?? $asset['sha256'], $hash ) ) ) { throw new Exception( 'Photography integrity check failed.' ); }
        }
        $originals = array();
        $products = array();
        foreach ( $items as $slug => $item ) {
            $post = get_page_by_path( $slug, OBJECT, 'product' );
            $product = $post ? wc_get_product( $post->ID ) : false;
            if ( ! $product || ! $product->is_type( 'variable' ) || 'yes' !== $product->get_meta( '_maqwi_sample' ) ) { throw new Exception( 'Expected sample catalogue was changed. Review before importing.' ); }
            $products[ $slug ] = $product;
            foreach ( array_merge( array( $product->get_id() ), $product->get_children() ) as $id ) {
                $p = wc_get_product( $id );
                $originals[ $id ] = array( 'data' => $p->get_data(), 'meta' => array_map( function ( $m ) { return $m->get_data(); }, $p->get_meta_data() ) );
            }
            if ( $item['sku'] && wc_get_product_id_by_sku( $item['sku'] ) ) { throw new Exception( 'A release SKU already exists. Review before importing.' ); }
        }
        if ( ! add_option( 'maqwi_catalogue_before_20261016', $originals, '', false ) ) { throw new Exception( 'An earlier catalogue snapshot exists. Review before importing.' ); }
        $shots = array( 'catalogue', 'lifestyle', 'detail', 'alternate-angle' );
        $galleries = array();
        foreach ( $items as $slug => $item ) {
            $galleries[ $slug ] = array();
            foreach ( $shots as $n => $shot ) {
                $filename = sprintf( '%02d-%s-%s.webp', $n + 1, $item['folder'], $shot );
                $galleries[ $slug ][] = maqwi_catalogue_media( $filename, $item['name'] . ' — ' . str_replace( '-', ' ', $shot ), $products[ $slug ]->get_id() );
            }
        }
        $banner = maqwi_catalogue_media( 'maqwi-collection-banner.webp', 'Maqwi Organics collection — Honey, Desi Ghee, Achaar and Itar' );
        foreach ( $items as $slug => $item ) {
            $product = $products[ $slug ];
            // Old sample variations are retained as drafts, never permanently deleted.
            foreach ( $product->get_children() as $child ) {
                $variation = wc_get_product( $child );
                $variation->set_status( 'private' );
                $variation->set_regular_price( '' );
                $variation->set_sale_price( '' );
                $variation->set_price( '' );
                $variation->set_stock_status( 'outofstock' );
                $variation->save();
            }
            $product->set_name( $item['name'] );
            $product->set_description( $item['description'] );
            $product->set_short_description( $item['short'] );
            $product->set_image_id( $galleries[ $slug ][0] );
            $product->set_gallery_image_ids( array_slice( $galleries[ $slug ], 1 ) );
            $product->set_default_attributes( array() );
            $product->set_attributes( array() );
            $product->set_manage_stock( false );
            $product->set_stock_status( 'outofstock' );
            $product->delete_meta_data( '_maqwi_sample' );
            $product->delete_meta_data( '_maqwi_concept_image' );
            foreach ( array_keys( maqwi_product_detail_fields() ) as $key ) { $product->delete_meta_data( $key ); }
            $product->update_meta_data( '_maqwi_net_content', $item['size'] ?: 'Pack sizes awaiting confirmation' );
            $product->update_meta_data( '_maqwi_usage', $item['usage'] );
            $product->update_meta_data( '_maqwi_catalogue_release', $version );
            $product->update_meta_data( '_maqwi_photo_note', $item['photo_note'] );
            if ( $item['size'] ) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_name( 'Size' );
                $attribute->set_options( array( $item['size'] ) );
                $attribute->set_visible( true );
                $attribute->set_variation( true );
                $product->set_attributes( array( $attribute ) );
                $product->set_default_attributes( array( 'size' => $item['size'] ) );
            }
            $product->save();
            if ( $item['size'] ) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id( $product->get_id() );
                $variation->set_attributes( array( 'size' => $item['size'] ) );
                $variation->set_sku( $item['sku'] );
                $variation->set_regular_price( $item['price'] );
                $variation->set_status( 'publish' );
                $variation->set_manage_stock( true );
                $variation->set_stock_quantity( 0 );
                $variation->set_stock_status( 'outofstock' );
                $variation->set_backorders( 'no' );
                $variation->set_weight( $item['weight'] );
                $variation->set_image_id( $galleries[ $slug ][0] );
                $variation->set_description( $item['size'] . '. ' . $item['photo_note'] );
                $variation->save();
            }
            WC_Product_Variable::sync( $product->get_id() );
            wc_delete_product_transients( $product->get_id() );
        }
        update_option( 'maqwi_collection_banner', $banner, false );
        $page_originals = array();
        foreach ( maqwi_preview_pages() as $slug => $definition ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( ! $page || 'yes' !== get_post_meta( $page->ID, '_maqwi_sample_page', true ) ) { continue; }
            $expected = '<!-- wp:html -->' . $definition[1] . '<!-- /wp:html -->';
            if ( $page->post_content !== $expected ) { continue; }
            $page_originals[ $page->ID ] = $page->post_content;
        }
        add_option( 'maqwi_catalogue_pages_before_20261016', $page_originals, '', false );
        $replacements = array(
            'The current catalogue is a working preview. We will confirm product details, photography, delivery and support before taking orders.' => 'Explore our collection and confirmed prices. We are finalising stock, delivery and support before taking orders.',
            'No. The catalogue contains editable sample prices and sizes. Packaging images are concepts. Final details will be approved before sale.' => 'Honey is PKR 4,500 for 1 kg, Desi Ghee is PKR 3,500 for 1 kg, and Itar is PKR 200 for 12 ml. Achaar pricing is being finalised. Galleries use AI product visuals; pictured pack sizes may differ from the net contents stated on the listing.',
            'Not yet. You can explore products, select sample sizes, add them to your bag and review checkout. No order can be submitted during this preview.' => 'Not yet. You can explore product galleries, descriptions and confirmed prices. Availability, delivery and checkout are being finalised before we accept orders.',
            'Each product has a Product details tab. Unconfirmed information is clearly marked; use only the approved final label when products launch.' => 'Read the Product details tab and the supplied product label. Final recipe, supplier and batch information is being confirmed. Do not assume a product is allergen-free.',
            'Adding sample items to your bag uses' => 'Adding items to your bag uses'
        );
        foreach ( $page_originals as $id => $content ) {
            $changed = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
            if ( $changed === $content ) { continue; }
            wp_save_post_revision( $id );
            $result = wp_update_post( array( 'ID' => $id, 'post_content' => $changed ), true );
            if ( is_wp_error( $result ) ) { throw new Exception( 'Information page update failed.' ); }
        }
        update_option( 'maqwi_catalogue_release', $version, false );
        delete_option( 'maqwi_catalogue_release_lock' );
        wc_delete_product_transients();
        if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
    } catch ( Throwable $error ) {
        // Never fail the storefront request or open orders following a partial import.
        update_option( 'maqwi_catalogue_release_error', $error->getMessage(), false );
        update_option( 'maqwi_brand_launch', 'yes' );
    }
}, 30 );

add_action( 'woocommerce_single_product_summary', function () {
    global $product;
    if ( $product && $product->get_meta( '_maqwi_photo_note' ) ) {
        echo '<p class="mq-sample-note">' . esc_html( $product->get_meta( '_maqwi_photo_note' ) ) . '</p>';
    }
}, 12 );

add_filter( 'woocommerce_get_availability_text', function ( $text, $product ) {
    $parent = $product->get_parent_id() ? wc_get_product( $product->get_parent_id() ) : $product;
    return $parent && $parent->get_meta( '_maqwi_catalogue_release' ) && ! $product->is_in_stock() ? 'Currently unavailable' : $text;
}, 10, 2 );

add_filter( 'woocommerce_variable_empty_price_html', function ( $html, $product ) {
    return $product->get_meta( '_maqwi_catalogue_release' ) ? '<span class="mq-price-pending">Price to be confirmed</span>' : $html;
}, 10, 2 );
