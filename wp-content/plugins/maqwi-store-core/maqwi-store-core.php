<?php
/**
 * Plugin Name: Maqwi Store Core
 * Description: Pakistan checkout rules and verified product details, independent of the theme.
 * Version: 0.1.2
 * Requires Plugins: woocommerce
 * Requires PHP: 8.1
 * License: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;

add_action( 'before_woocommerce_init', function () {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/** Canonical Pakistani mobile numbers; invalid input never becomes a valid number. */
function maqwi_normalize_mobile( $value ) {
    $value = preg_replace( '/[\s()\-]+/', '', (string) $value );
    if ( preg_match( '/\A03[0-9]{9}\z/', $value ) ) { return '+92' . substr( $value, 1 ); }
    if ( preg_match( '/\A\+923[0-9]{9}\z/', $value ) ) { return $value; }
    return '';
}

/** Account address edits stay compatible with the checkout's single full name. */
add_filter( 'woocommerce_default_address_fields', function ( $fields ) {
    $fields['first_name']['label'] = 'Full name';
    $fields['first_name']['class'] = array( 'form-row-wide' );
    $fields['last_name']['required'] = false;
    $fields['postcode']['required'] = false;
    return $fields;
}, 30 );

/** These fields target the approved classic shortcode checkout only. */
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
    foreach ( array( 'billing', 'shipping' ) as $section ) {
        if ( empty( $fields[ $section ] ) ) { continue; }
        $prefix = $section . '_';
        unset( $fields[ $section ][ $prefix . 'company' ], $fields[ $section ][ $prefix . 'address_2' ], $fields[ $section ][ $prefix . 'last_name' ] );
        $fields[ $section ][ $prefix . 'first_name' ]['label'] = 'Full name';
        $fields[ $section ][ $prefix . 'first_name' ]['class'] = array( 'form-row-wide' );
        $fields[ $section ][ $prefix . 'first_name' ]['autocomplete'] = 'name';
        $fields[ $section ][ $prefix . 'address_1' ]['label'] = 'Full delivery address';
        $fields[ $section ][ $prefix . 'address_1' ]['placeholder'] = 'House / flat, street and area';
        $fields[ $section ][ $prefix . 'postcode' ]['required'] = false;
        $fields[ $section ][ $prefix . 'country' ]['type'] = 'hidden';
        $fields[ $section ][ $prefix . 'country' ]['default'] = 'PK';
        $fields[ $section ][ $prefix . 'state' ]['label'] = 'Province / territory';
        $fields[ $section ][ $prefix . 'state' ]['required'] = true;
        $fields[ $section ][ $prefix . 'city' ]['required'] = true;
        $fields[ $section ][ $prefix . 'landmark' ] = array( 'type' => 'text', 'label' => 'Nearby landmark', 'required' => false, 'class' => array( 'form-row-wide' ), 'priority' => 65, 'placeholder' => 'Optional — a place that helps the courier find you' );
    }
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['required'] = true;
        $fields['billing']['billing_phone']['label'] = 'Mobile number';
        $fields['billing']['billing_phone']['placeholder'] = '03001234567 or +923001234567';
        $fields['billing']['billing_phone']['class'] = array( 'form-row-wide' );
    }
    return $fields;
}, 30 );

add_filter( 'woocommerce_checkout_posted_data', function ( $data ) {
    $mobile = maqwi_normalize_mobile( $data['billing_phone'] ?? '' );
    if ( $mobile ) { $data['billing_phone'] = $mobile; }
    return $data;
} );

function maqwi_validate_checkout( $data, $errors ) {
    if ( ! maqwi_normalize_mobile( $data['billing_phone'] ?? '' ) ) {
        $errors->add( 'maqwi_mobile', 'Enter a Pakistani mobile number, such as 03001234567 or +923001234567.' );
    }
    if ( 'PK' !== ( $data['billing_country'] ?? '' ) || ( ! empty( $data['ship_to_different_address'] ) && 'PK' !== ( $data['shipping_country'] ?? '' ) ) ) {
        $errors->add( 'maqwi_country', 'Delivery is currently available in Pakistan only.' );
    }
}
add_action( 'woocommerce_after_checkout_validation', 'maqwi_validate_checkout', 10, 2 );

add_action( 'woocommerce_checkout_create_order', function ( $order, $data ) {
    foreach ( array( 'billing', 'shipping' ) as $section ) {
        $key = $section . '_landmark';
        if ( ! empty( $data[ $key ] ) ) { $order->update_meta_data( '_maqwi_' . $key, sanitize_text_field( $data[ $key ] ) ); }
    }
}, 10, 2 );
foreach ( array( 'billing', 'shipping' ) as $maqwi_address_type ) {
    add_action( 'woocommerce_admin_order_data_after_' . $maqwi_address_type . '_address', function ( $order ) use ( $maqwi_address_type ) {
        $value = $order->get_meta( '_maqwi_' . $maqwi_address_type . '_landmark' );
        if ( $value ) { echo '<p><strong>Nearby landmark:</strong> ' . esc_html( $value ) . '</p>'; }
    } );
}

/** COD enters the team's confirmation queue rather than promising dispatch. */
add_filter( 'woocommerce_cod_process_payment_order_status', function () { return 'on-hold'; }, 20 );

function maqwi_product_detail_fields() {
    return array(
        '_maqwi_net_content' => 'Net weight / volume',
        '_maqwi_ingredients' => 'Ingredients',
        '_maqwi_allergens' => 'Allergen information',
        '_maqwi_origin' => 'Origin and sourcing',
        '_maqwi_storage' => 'Storage instructions',
        '_maqwi_usage' => 'Serving / usage guidance',
        '_maqwi_batch' => 'Batch / lot',
        '_maqwi_packed' => 'Packing date',
        '_maqwi_best_before' => 'Best before'
    );
}
add_action( 'woocommerce_product_options_general_product_data', function () {
    echo '<div class="options_group"><p style="padding-left:12px"><strong>Maqwi product details</strong><br>Enter only verified label and supplier information. Empty fields stay hidden on the storefront.</p>';
    foreach ( maqwi_product_detail_fields() as $id => $label ) {
        woocommerce_wp_textarea_input( array( 'id' => $id, 'label' => $label, 'rows' => 2 ) );
    }
    echo '</div>';
} );
add_action( 'woocommerce_process_product_meta', function ( $product_id ) {
    if ( ! current_user_can( 'edit_post', $product_id ) ) { return; }
    $product = wc_get_product( $product_id );
    if ( ! $product ) { return; }
    foreach ( maqwi_product_detail_fields() as $id => $label ) {
        if ( isset( $_POST[ $id ] ) && is_string( $_POST[ $id ] ) ) {
            $value = sanitize_textarea_field( wp_unslash( $_POST[ $id ] ) );
            if ( '' === $value ) { $product->delete_meta_data( $id ); } else { $product->update_meta_data( $id, $value ); }
        }
    }
    $product->save();
} );
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    global $product;
    if ( ! $product ) { return $tabs; }
    foreach ( maqwi_product_detail_fields() as $id => $label ) {
        if ( $product->get_meta( $id ) ) {
            $tabs['maqwi_details'] = array( 'title' => 'Product details', 'priority' => 15, 'callback' => 'maqwi_render_product_details' );
            break;
        }
    }
    return $tabs;
} );
function maqwi_render_product_details() {
    global $product;
    if ( ! $product ) { return; }
    echo '<h2>Product details</h2><dl class="mq-product-details">';
    foreach ( maqwi_product_detail_fields() as $id => $label ) {
        $value = $product->get_meta( $id );
        if ( $value ) { echo '<dt>' . esc_html( $label ) . '</dt><dd>' . nl2br( esc_html( $value ) ) . '</dd>'; }
    }
    echo '</dl>';
}

/** A small owner-facing readiness screen; it never labels an untested store ready. */
add_action( 'admin_menu', function () {
    add_submenu_page( 'woocommerce', 'Maqwi store setup', 'Maqwi store setup', 'manage_woocommerce', 'maqwi-store-setup', 'maqwi_store_setup_screen' );
} );
function maqwi_store_setup_screen() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
    $counts = wp_count_posts( 'product' );
    $coming_soon = 'yes' === get_option( 'woocommerce_coming_soon' );
    $classic = true;
    foreach ( array( 'cart', 'checkout' ) as $type ) {
        $page = get_post( wc_get_page_id( $type ) );
        $classic = $classic && $page && has_shortcode( $page->post_content, 'woocommerce_' . $type ) && ! has_block( 'woocommerce/' . $type, $page->post_content );
    }
    $cod = get_option( 'woocommerce_cod_settings', array() );
    ?>
    <div class="wrap"><h1>Maqwi store setup</h1>
    <p>Build first, open for orders after checkout, delivery, email and recovery have been tested.</p>
    <table class="widefat striped" style="max-width:1000px"><thead><tr><th>Area</th><th>Current setup</th><th>Next action</th></tr></thead><tbody>
        <tr><td>Store visibility</td><td><?php echo $coming_soon ? 'Coming soon — store closed' : 'Live — review launch approval'; ?></td><td><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=site-visibility' ) ); ?>">Visibility settings</a></td></tr>
        <tr><td>Products</td><td><?php echo esc_html( (int) ( $counts->publish ?? 0 ) ); ?> published; <?php echo esc_html( (int) ( $counts->draft ?? 0 ) ); ?> drafts</td><td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>">Confirm prices, stock, label details and photos</a></td></tr>
        <tr><td>Cart and checkout</td><td><?php echo $classic ? 'Classic pages configured — order test still needed' : 'Classic page setup needed'; ?></td><td>Use the setup button below if these pages still contain Blocks.</td></tr>
        <tr><td>Cash on delivery</td><td><?php echo 'yes' === ( $cod['enabled'] ?? 'no' ) ? 'Enabled — orders await confirmation' : 'Not enabled'; ?></td><td><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cod' ) ); ?>">Payment settings</a></td></tr>
        <tr><td>Delivery</td><td>Courier coverage and rates need approval and a delivery test.</td><td><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ); ?>">Shipping zones and rates</a></td></tr>
        <tr><td>Business details</td><td>Verify address, support contact and published policies.</td><td><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=general' ) ); ?>">Store settings</a></td></tr>
        <tr><td>Order email</td><td>Authentication and customer/admin delivery are not proven by installed settings.</td><td><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=email' ) ); ?>">Email settings</a></td></tr>
    </tbody></table>
    <h2>Use the Maqwi checkout</h2><p>This replaces the contents of the assigned Cart and Checkout pages with native WooCommerce shortcodes. Titles, URLs and page assignments stay the same. Take a database backup first; previous page content is retained in revisions.</p>
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
        <input type="hidden" name="action" value="maqwi_setup_checkout">
        <?php wp_nonce_field( 'maqwi_setup_checkout' ); submit_button( 'Configure classic cart and checkout', 'secondary' ); ?>
    </form>
    <p><strong>Installed:</strong> Maqwi Store Core 0.1.2; WooCommerce <?php echo esc_html( defined( 'WC_VERSION' ) ? WC_VERSION : 'not available' ); ?>; database <?php echo esc_html( get_option( 'woocommerce_db_version', 'not available' ) ); ?>; theme <?php echo esc_html( wp_get_theme()->get( 'Name' ) . ' ' . wp_get_theme()->get( 'Version' ) ); ?>.</p>
    </div>
    <?php
}
add_action( 'admin_post_maqwi_setup_checkout', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { wp_die( 'You cannot change this store setup.' ); }
    check_admin_referer( 'maqwi_setup_checkout' );
    $pages = array();
    foreach ( array( 'cart', 'checkout' ) as $type ) {
        $id = wc_get_page_id( $type );
        $page = get_post( $id );
        if ( ! $page || 'page' !== $page->post_type || ! current_user_can( 'edit_post', $id ) ) { wp_die( 'Check the WooCommerce page assignments before configuring checkout.' ); }
        if ( ! wp_revisions_enabled( $page ) ) { wp_die( 'Enable page revisions before configuring checkout so the previous content can be recovered.' ); }
        $pages[ $type ] = $page;
    }
    if ( $pages['cart']->ID === $pages['checkout']->ID ) { wp_die( 'Cart and Checkout must be different pages.' ); }
    $updated = array();
    foreach ( $pages as $type => $page ) {
        $content = "<!-- wp:shortcode -->\n[woocommerce_" . $type . "]\n<!-- /wp:shortcode -->";
        if ( $content === trim( $page->post_content ) ) { continue; }
        wp_save_post_revision( $page->ID );
        $result = wp_update_post( array( 'ID' => $page->ID, 'post_content' => $content ), true );
        if ( is_wp_error( $result ) ) {
            foreach ( $updated as $original ) { wp_update_post( array( 'ID' => $original->ID, 'post_content' => $original->post_content ) ); }
            wp_die( 'Page setup failed. Check the previous page revisions before retrying.' );
        }
        $updated[] = $page;
    }
    wp_safe_redirect( admin_url( 'admin.php?page=maqwi-store-setup' ) );
    exit;
} );
