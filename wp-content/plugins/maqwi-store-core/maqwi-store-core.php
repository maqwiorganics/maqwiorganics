<?php
/**
 * Plugin Name: Maqwi Store Core
 * Description: Pakistan checkout rules and verified product details, independent of the theme.
 * Version: 0.1.0
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
