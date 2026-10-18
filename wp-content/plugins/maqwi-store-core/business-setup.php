<?php
/** Owner-approved, one-time commerce configuration. No public management endpoint. */
defined( 'ABSPATH' ) || exit;

const MAQWI_BUSINESS_RELEASE = '2026-10-18-approved-commerce-1';

function maqwi_approved_business_catalogue() {
    return array(
        'maqwi-honey' => array( 'id' => 26, 'variation' => 62, 'stock' => 20, 'size' => '1 kg', 'price' => '4500', 'sku' => 'MQ-HNY-STD-1000' ),
        'maqwi-desi-ghee' => array( 'id' => 29, 'variation' => 63, 'stock' => 15, 'size' => '1 kg', 'price' => '3500', 'sku' => 'MQ-GHE-STD-1000' ),
        'maqwi-achaar' => array( 'id' => 32, 'stock' => 5, 'sizes' => array( '500 g' => '1500', '1 kg' => '3000' ) ),
        'maqwi-itar' => array( 'id' => 35, 'variation' => 64, 'stock' => 10, 'size' => '12 ml', 'price' => '200', 'sku' => 'MQ-ITR-STD-012' ),
    );
}

function maqwi_approved_business_pages() {
    return array(
        'contact' => array( 39, '58606508f9b2c45b59ea0995282c414acfd231dd3c22f847049d5516ace8d566', '<h2>Contact Maqwi Organics</h2><p>For product questions and order support, contact us using the details below.</p><p><strong>Phone:</strong> <a href="tel:+923171935828">0317 1935828</a><br><strong>Email:</strong> <a href="mailto:info@maqwiorganics.com">info@maqwiorganics.com</a><br><strong>Location:</strong> Ali Town, Lahore, Punjab, Pakistan</p><p>Ordering is currently closed while we complete our launch checks.</p>' ),
        'delivery' => array( 41, 'a6522f47534e262dd0c94acf96c012cc61e78993a0ece941c30759a54d326502', '<h2>Nationwide delivery in Pakistan</h2><p>Delivery costs <strong>PKR 300 per order</strong>, including orders containing more than one item. We currently accept delivery addresses in Pakistan only.</p><p>Please provide your full name, Pakistani mobile number, province, city and complete delivery address. You can include a nearby landmark to help locate the address.</p><p>Contact us for delivery questions. Ordering remains closed until our launch checks are complete.</p><p><a href="/contact/">Contact Maqwi →</a></p>' ),
        'faq' => array( 40, '35dd28531612375634ff641358cd82150e0f94eef3589192209d168cc1bdc964', '<h2>Frequently asked questions</h2><details><summary>Can I place an order?</summary><p>Ordering is currently closed while we complete our launch checks.</p></details><details><summary>What are the prices and pack sizes?</summary><p>Honey: 1 kg, PKR 4,500. Desi Ghee: 1 kg, PKR 3,500. Achaar: 500 g, PKR 1,500, or 1 kg, PKR 3,000. Itar: 12 ml, PKR 200.</p></details><details><summary>What does delivery cost?</summary><p>Nationwide delivery in Pakistan is PKR 300 per order, including orders with multiple items.</p></details><details><summary>Which payment methods are available?</summary><p>Payments are currently unavailable. Available payment methods will be published when ordering opens.</p></details><details><summary>Which pack size will I receive?</summary><p>The selected net weight or volume on the product listing applies. Some gallery photographs show another pack size.</p></details><details><summary>Where can I find ingredients and allergens?</summary><p>Refer to the supplied product label and product details. Contact us before ordering if you need information about an ingredient or allergen.</p></details><p><a href="/contact/">Contact Maqwi →</a></p>' ),
    );
}

/** Preflight only: never edits products, options, orders or shipping tables. */
function maqwi_business_setup_preflight() {
    if ( ! maqwi_brand_launch_mode() || ! maqwi_storefront_preview() ) { throw new RuntimeException( 'The public order guard must remain enabled.' ); }
    if ( 'PKR' !== get_option( 'woocommerce_currency' ) || 'no' !== get_option( 'woocommerce_calc_taxes' ) ) { throw new RuntimeException( 'Currency or tax configuration changed; review before applying.' ); }
    if ( wc_get_orders( array( 'limit' => 1, 'return' => 'ids' ) ) || get_posts( array( 'post_type' => 'shop_order', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) { throw new RuntimeException( 'Orders exist; opening inventory must be reviewed rather than reset.' ); }
    if ( WC_Shipping_Zones::get_zones() || ( new WC_Shipping_Zone( 0 ) )->get_shipping_methods() ) { throw new RuntimeException( 'Shipping has changed; existing methods were preserved.' ); }
    $products = array();
    foreach ( maqwi_approved_business_catalogue() as $slug => $item ) {
        $post = get_page_by_path( $slug, OBJECT, 'product' );
        $parent = $post ? wc_get_product( $post->ID ) : false;
        if ( ! $parent || $parent->get_id() !== $item['id'] || ! $parent->is_type( 'variable' ) || '2026-10-16-catalogue-1' !== $parent->get_meta( '_maqwi_catalogue_release' ) || $parent->get_manage_stock() ) { throw new RuntimeException( 'Product identity or opening-stock baseline changed: ' . $slug ); }
        $active = get_posts( array( 'post_type' => 'product_variation', 'post_parent' => $parent->get_id(), 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
        if ( isset( $item['variation'] ) ) {
            if ( count( $active ) !== 1 || (int) $active[0] !== $item['variation'] ) { throw new RuntimeException( 'Active variations changed: ' . $slug ); }
            $v = wc_get_product( $item['variation'] );
            if ( ! $v || $v->get_parent_id() !== $item['id'] || $v->get_sku() !== $item['sku'] || ! $v->get_manage_stock() || (float) $v->get_stock_quantity() !== 0.0 || (float) $v->get_regular_price() !== (float) $item['price'] ) { throw new RuntimeException( 'Existing variation baseline changed: ' . $slug ); }
        } else {
            if ( $active || wc_get_product_id_by_sku( 'MQ-ACH-0500' ) || wc_get_product_id_by_sku( 'MQ-ACH-1000' ) ) { throw new RuntimeException( 'Achaar variations already exist; no duplicates were created.' ); }
        }
        $products[ $slug ] = $parent;
    }
    foreach ( maqwi_approved_business_pages() as $slug => $definition ) {
        $page = get_post( $definition[0] );
        if ( ! $page || $page->post_name !== $slug || 'page' !== $page->post_type || ! hash_equals( $definition[1], hash( 'sha256', $page->post_content ) ) || 'yes' !== get_post_meta( $page->ID, '_maqwi_sample_page', true ) || ! wp_revisions_enabled( $page ) ) { throw new RuntimeException( 'Page has changed; existing content was preserved: ' . $slug ); }
    }
    return $products;
}

/** Native CRUD; a completed release never reapplies opening quantities. */
function maqwi_apply_approved_business_setup() {
    if ( MAQWI_BUSINESS_RELEASE === get_option( 'maqwi_business_setup_release' ) ) { return false; }
    $products = maqwi_business_setup_preflight();
    if ( ! add_option( 'maqwi_business_setup_lock', MAQWI_BUSINESS_RELEASE, '', false ) ) { throw new RuntimeException( 'A prior setup attempt is locked for review.' ); }
    $snapshot = array( 'products' => array(), 'pages' => array(), 'options' => array(), 'created_variations' => array() );
    foreach ( $products as $parent ) {
        foreach ( array_merge( array( $parent->get_id() ), $parent->get_children() ) as $id ) {
            $p = wc_get_product( $id );
            if ( $p ) { $snapshot['products'][ $id ] = array( 'data' => $p->get_data(), 'meta' => array_map( function ( $m ) { return $m->get_data(); }, $p->get_meta_data() ) ); }
        }
    }
    foreach ( maqwi_approved_business_pages() as $page ) { $snapshot['pages'][ $page[0] ] = get_post( $page[0] )->post_content; }
    foreach ( array( 'woocommerce_store_address', 'woocommerce_store_city', 'woocommerce_default_country', 'woocommerce_allowed_countries', 'woocommerce_specific_allowed_countries', 'woocommerce_ship_to_countries', 'woocommerce_specific_ship_to_countries', 'woocommerce_cod_settings', 'woocommerce_bacs_settings', 'woocommerce_cheque_settings' ) as $key ) { $snapshot['options'][ $key ] = get_option( $key, null ); }
    if ( ! add_option( 'maqwi_business_setup_before_20261018', $snapshot, '', false ) ) { throw new RuntimeException( 'A setup snapshot already exists; no changes were made.' ); }
    try {
        foreach ( maqwi_approved_business_catalogue() as $slug => $item ) {
            $parent = $products[ $slug ];
            if ( isset( $item['variation'] ) ) {
                $v = wc_get_product( $item['variation'] );
                $v->set_stock_quantity( $item['stock'] );
                $v->set_stock_status( 'instock' );
                $v->set_backorders( 'no' );
                $v->save();
            } else {
                $attribute = new WC_Product_Attribute();
                $attribute->set_name( 'Size' );$attribute->set_options( array_keys( $item['sizes'] ) );$attribute->set_visible( true );$attribute->set_variation( true );
                $parent->set_attributes( array( $attribute ) );
                $parent->set_default_attributes( array( 'size' => '500 g' ) );
                $parent->set_manage_stock( true );$parent->set_stock_quantity( $item['stock'] );$parent->set_backorders( 'no' );$parent->set_stock_status( 'instock' );
                $parent->set_short_description( 'Achaar for paratha, rice and traditional meals. Choose 500 g or 1 kg.' );
                $parent->set_description( '<h2>Maqwi Achaar</h2><p>Serve alongside paratha, rice, dal or a home-cooked meal.</p><h3>Choose your pack</h3><p>500 g: PKR 1,500. 1 kg: PKR 3,000. Nationwide delivery in Pakistan: PKR 300 per order.</p><p>Use a clean, dry spoon. Refer to the supplied label for ingredients, allergens, storage and best-before information. Contact us if you need further product details.</p>' );
                $parent->update_meta_data( '_maqwi_net_content', '500 g / 1 kg' );
                $parent->update_meta_data( '_maqwi_photo_note', 'The selected net weight on this listing applies to your order.' );
                $parent->save();
                foreach ( $item['sizes'] as $size => $price ) {
                    $v = new WC_Product_Variation();$v->set_parent_id( $parent->get_id() );$v->set_attributes( array( 'size' => $size ) );$v->set_sku( '500 g' === $size ? 'MQ-ACH-0500' : 'MQ-ACH-1000' );$v->set_regular_price( $price );$v->set_status( 'publish' );$v->set_manage_stock( false );$v->set_backorders( 'no' );$v->set_stock_status( 'instock' );$v->set_weight( '500 g' === $size ? '0.5' : '1' );$v->set_image_id( $parent->get_image_id() );$v->save();
                    $snapshot['created_variations'][] = $v->get_id();
                    update_option( 'maqwi_business_setup_before_20261018', $snapshot, false );
                }
            }
            WC_Product_Variable::sync( $parent->get_id() );wc_delete_product_transients( $parent->get_id() );
        }
        $zone = new WC_Shipping_Zone();$zone->set_zone_name( 'Pakistan — nationwide' );$zone->set_zone_order( 0 );$zone->add_location( 'PK', 'country' );$zone->save();
        $instance = $zone->add_shipping_method( 'flat_rate' );
        if ( ! $instance ) { throw new RuntimeException( 'Native shipping method creation failed.' ); }
        // Keep the existing tax configuration; the observed store has taxes disabled.
        update_option( 'woocommerce_flat_rate_' . $instance . '_settings', array( 'title' => 'Nationwide delivery', 'tax_status' => 'taxable', 'cost' => '300', 'type' => 'order', 'class_costs' => '', 'no_class_cost' => '' ) );
        $snapshot['created_shipping_zone'] = $zone->get_id();$snapshot['created_shipping_method'] = $instance;
        update_option( 'maqwi_business_setup_before_20261018', $snapshot, false );
        update_option( 'woocommerce_store_address', 'Ali Town' );update_option( 'woocommerce_store_city', 'Lahore' );update_option( 'woocommerce_default_country', 'PK:PB' );
        update_option( 'woocommerce_allowed_countries', 'specific' );update_option( 'woocommerce_specific_allowed_countries', array( 'PK' ) );update_option( 'woocommerce_ship_to_countries', 'specific' );update_option( 'woocommerce_specific_ship_to_countries', array( 'PK' ) );
        foreach ( array( 'cod', 'bacs', 'cheque' ) as $gateway ) {
            $key = 'woocommerce_' . $gateway . '_settings';$settings = get_option( $key, array() );$settings['enabled'] = 'no';update_option( $key, $settings );
        }
        foreach ( maqwi_approved_business_pages() as $page ) {
            wp_save_post_revision( $page[0] );
            $result = wp_update_post( array( 'ID' => $page[0], 'post_content' => '<!-- wp:html -->' . $page[2] . '<!-- /wp:html -->' ), true );
            if ( is_wp_error( $result ) ) { throw new RuntimeException( 'Business page could not be saved.' ); }
        }
        WC_Cache_Helper::get_transient_version( 'shipping', true );delete_transient( 'wc_shipping_method_count' );
        update_option( 'maqwi_business_setup_release', MAQWI_BUSINESS_RELEASE, false );
        delete_option( 'maqwi_business_setup_lock' );
        if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
        return true;
    } catch ( Throwable $error ) {
        update_option( 'maqwi_brand_launch', 'yes' );
        update_option( 'maqwi_business_setup_error', $error->getMessage(), false );
        // Keep the lock and native snapshot for reviewed recovery; never replay a partial stock import.
        throw $error;
    }
}

/** An authenticated hosting administrator must explicitly request this exact release. */
add_action( 'init', function () {
    if ( MAQWI_BUSINESS_RELEASE !== get_option( 'maqwi_business_setup_request' ) || MAQWI_BUSINESS_RELEASE === get_option( 'maqwi_business_setup_release' ) || get_option( 'maqwi_business_setup_lock' ) ) { return; }
    if ( untrailingslashit( home_url() ) !== 'https://maqwiorganics.gt.tc' ) { return; }
    try { maqwi_apply_approved_business_setup(); }
    catch ( Throwable $error ) { update_option( 'maqwi_business_setup_error', $error->getMessage(), false ); }
}, 60 );
