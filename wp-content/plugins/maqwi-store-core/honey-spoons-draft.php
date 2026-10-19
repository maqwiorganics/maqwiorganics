<?php
/**
 * Maqwi Honey Spoons: authenticated draft-only introduction.
 *
 * Never runs automatically, never changes existing products, and never enables
 * ordering or payments. The owner's stock count and final food-contact
 * packaging/photography are still pending.
 */
defined( 'ABSPATH' ) || exit;

const MAQWI_HONEY_SPOONS_SLUG = 'maqwi-honey-spoons';
const MAQWI_HONEY_SPOONS_SKU = 'MQ-HSP-010X007';

function maqwi_honey_spoons_create_draft() {
    if ( ! class_exists( 'WC_Product_Simple' ) || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
        throw new RuntimeException( 'WooCommerce is required to create the draft.' );
    }
    if ( ! function_exists( 'maqwi_brand_launch_mode' ) || ! maqwi_brand_launch_mode() ) {
        throw new RuntimeException( 'The public ordering guard must be enabled.' );
    }
    if ( get_page_by_path( MAQWI_HONEY_SPOONS_SLUG, OBJECT, 'product' ) ) {
        throw new RuntimeException( 'The product slug already exists. Review the existing record; do not overwrite it.' );
    }
    if ( wc_get_product_id_by_sku( MAQWI_HONEY_SPOONS_SKU ) ) {
        throw new RuntimeException( 'The SKU already exists. Review the existing record; do not overwrite it.' );
    }
    $product = new WC_Product_Simple();
    $product->set_name( 'Maqwi Honey Spoons — Pack of 10' );
    $product->set_slug( MAQWI_HONEY_SPOONS_SLUG );
    $product->set_status( 'draft' );
    $product->set_catalog_visibility( 'hidden' );
    $product->set_sku( MAQWI_HONEY_SPOONS_SKU );
    $product->set_regular_price( '899' );
    $product->set_manage_stock( true );
    $product->set_stock_quantity( 0 );
    $product->set_stock_status( 'outofstock' );
    $product->set_backorders( 'no' );
    $product->set_short_description(
        'Ten individually packed, single-use spoons, each pre-filled with 7 g of honey. 70 g total honey per 10-pack. PKR 899.'
    );
    $product->set_description(
        '<h2>Honey, one spoon at a time.</h2>'
        . '<p>Take Maqwi Honey Spoons with you for a simple serving of honey wherever your day takes you. Each individually packed single-use spoon contains 7 g of honey. Open and use as appropriate for the supplied spoon and its packaging.</p>'
        . '<h3>What is inside</h3>'
        . '<p><strong>10 pre-filled spoons × 7 g honey = 70 g net honey.</strong> One spoon per day lasts 10 days. Price: <strong>PKR 899 per pack</strong>.</p>'
        . '<h3>Important information</h3>'
        . '<p>Food-contact material, temperature limits, ingredients, batch, best-before and storage information must be confirmed against the final packaging before sales begin. Honey is not suitable for infants under 12 months. Do not assume the spoon is edible, biodegradable or suitable for hot drinks until verified.</p>'
    );
    $product->update_meta_data( '_maqwi_net_content', '70 g honey total (10 × 7 g)' );
    $product->update_meta_data( '_maqwi_pack_count', '10' );
    $product->update_meta_data( '_maqwi_honey_per_spoon_g', '7' );
    $product->update_meta_data( '_maqwi_sku_release', '2026-10-19-honey-spoons-7g-draft-2' );
    $product->update_meta_data( '_maqwi_packaging_review_pending', 'yes' );
    $id = $product->save();
    if ( ! $id ) {
        throw new RuntimeException( 'The draft product could not be saved.' );
    }
    $honey_term = get_term_by( 'slug', 'honey', 'product_cat' );
    if ( $honey_term && ! is_wp_error( $honey_term ) ) {
        wp_set_object_terms( $id, array( (int) $honey_term->term_id ), 'product_cat' );
    }
    return $id;
}

/** Staff-only WooCommerce page. No unattended or anonymous product creation. */
add_action( 'admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'Maqwi Honey Spoons',
        'Honey Spoons draft',
        'manage_woocommerce',
        'maqwi-honey-spoons',
        function () {
            if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
            $existing = get_page_by_path( MAQWI_HONEY_SPOONS_SLUG, OBJECT, 'product' );
            echo '<div class="wrap"><h1>Maqwi Honey Spoons — 10 × 7 g</h1>';
            echo '<p>Approved price: PKR 899 per 10-pack. Stock and final pack safety details are not yet confirmed. Public orders remain closed.</p>';
            if ( $existing ) {
                echo '<p>A record already exists. No changes will be made automatically. <a href="' . esc_url( get_edit_post_link( $existing->ID ) ) . '">Review product</a>.</p>';
            } else {
                echo '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post">';
                echo '<input type="hidden" name="action" value="maqwi_honey_spoons_draft">';
                wp_nonce_field( 'maqwi_honey_spoons_draft' );
                submit_button( 'Create a non-purchasable draft' );
                echo '</form>';
            }
            if ( isset( $_GET['created'] ) ) {
                echo '<div class="notice notice-success"><p>Draft created. Review packaging, stock and photography before publication.</p></div>';
            }
            echo '</div>';
        }
    );
} );

add_action( 'admin_post_maqwi_honey_spoons_draft', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { wp_die( 'Not authorised.' ); }
    check_admin_referer( 'maqwi_honey_spoons_draft' );
    try {
        $id = maqwi_honey_spoons_create_draft();
        wp_safe_redirect( add_query_arg( array( 'page' => 'maqwi-honey-spoons', 'created' => (int) $id ), admin_url( 'admin.php' ) ) );
        exit;
    } catch ( Throwable $error ) {
        wp_die( esc_html( $error->getMessage() ) );
    }
} );
