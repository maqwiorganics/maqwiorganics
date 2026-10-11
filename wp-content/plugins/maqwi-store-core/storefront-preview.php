<?php
/** Owner-authorised sample catalogue. Ordinary visitors never trigger database setup. */
defined( 'ABSPATH' ) || exit;

/** Defence in depth: preview never creates orders, including Store API requests. */
add_filter( 'rest_pre_dispatch', function ( $result, $server, $request ) {
    if ( maqwi_brand_launch_mode() && preg_match( '#^/wc/store/v[0-9]+/checkout(?:/|$)#', $request->get_route() ) ) {
        return new WP_Error( 'maqwi_orders_closed', 'Orders are closed during the storefront preview.', array( 'status' => 403 ) );
    }
    return $result;
}, 10, 3 );
add_action( 'woocommerce_checkout_create_order', function () {
    if ( maqwi_brand_launch_mode() ) { throw new Exception( 'Orders are closed during the storefront preview.' ); }
}, 1 );
add_filter( 'woocommerce_order_button_html', function ( $html ) {
    return maqwi_storefront_preview() ? '<p class="mq-sample-note">You can test the delivery fields and bag totals. No payment will be taken and no order will be created.</p><button type="button" class="mq-preview-disabled" disabled>Orders open after launch</button>' : $html;
} );
add_filter( 'option_woocommerce_enable_myaccount_registration', function ( $value ) { return maqwi_storefront_preview() ? 'no' : $value; } );
add_filter( 'option_woocommerce_enable_signup_and_login_from_checkout', function ( $value ) { return maqwi_storefront_preview() ? 'no' : $value; } );
add_filter( 'woocommerce_package_rates', function ( $rates, $package ) {
    if ( ! maqwi_storefront_preview() ) { return $rates; }
    if ( 'PK' !== ( $package['destination']['country'] ?? '' ) ) { return array(); }
    return array( 'maqwi_preview' => new WC_Shipping_Rate( 'maqwi_preview', 'Sample delivery — final rate to be confirmed', 250, array(), 'maqwi_preview' ) );
}, 100, 2 );
add_action( 'woocommerce_before_checkout_form', function () {
    if ( maqwi_storefront_preview() ) { echo '<p class="mq-sample-note mq-preview-checkout-note">Storefront preview: sample products, prices and delivery rate. Order submission is disabled.</p>'; }
}, 5 );

function maqwi_sample_catalogue() {
    return array(
        'maqwi-honey' => array( 'name' => 'Maqwi Honey', 'category' => 'honey', 'image' => 'honey', 'sizes' => array( '250 g' => '850', '500 g' => '1500' ), 'short' => 'A sweet addition to breakfast, tea and everyday recipes. Choose a jar size for your table.', 'description' => '<h2>A little sweetness, your way</h2><p>Bring a spoonful to warm toast, drizzle over yoghurt or stir into your favourite drink. This sample honey listing shows how the Maqwi range will be presented, with clear pack sizes and practical serving guidance.</p><h3>At your table</h3><p>Pair with breakfast bowls, fruit and baked treats. The final floral variety, flavour profile and source will be confirmed for the actual product.</p>', 'usage' => 'Serving copy for review: use a clean, dry spoon. Honey is not suitable for infants under 12 months.', 'storage' => 'Proposed guidance for approval: keep tightly closed in a cool, dry place away from direct sunlight.' ),
        'maqwi-desi-ghee' => array( 'name' => 'Maqwi Desi Ghee', 'category' => 'desi-ghee', 'image' => 'ghee', 'sizes' => array( '250 g' => '1100', '500 g' => '2000' ), 'short' => 'A familiar companion for traditional cooking and finishing your favourite meals.', 'description' => '<h2>For meals that feel like home</h2><p>From a warm roti to the finishing touch on dal or rice, desi ghee has a place in everyday food traditions. Choose the sample pack size that suits your kitchen.</p><h3>Cooking and finishing</h3><p>This sample description illustrates the serving story. The final preparation method, source, ingredients and dairy allergen information must be confirmed before sale.</p>', 'usage' => 'Serving copy for review: use a clean, dry spoon and add to cooking or as a finishing touch.', 'storage' => 'Storage conditions and shelf life must be confirmed from the final supplier label.' ),
        'maqwi-achaar' => array( 'name' => 'Maqwi Achaar', 'category' => 'achaar', 'image' => 'achaar', 'sizes' => array( '250 g' => '450', '500 g' => '800' ), 'short' => 'The little extra beside a favourite meal. A jar for everyday sharing around the table.', 'description' => '<h2>A place beside your favourite plate</h2><p>Serve a small helping alongside rice, dal, paratha or a simple home-cooked meal. This sample achaar listing brings together the product story, size choices and care information.</p><h3>Made for the table</h3><p>The final pickle variety, spice level, oil, ingredients and allergen list will be confirmed for the actual recipe. No specific recipe or ingredient claim is made by this sample.</p>', 'usage' => 'Serving copy for review: serve with a clean, dry spoon. Final portion and allergen guidance will follow the approved recipe.', 'storage' => 'Refrigeration after opening and shelf life are awaiting final label approval.' ),
        'maqwi-itar' => array( 'name' => 'Maqwi Itar', 'category' => 'itar', 'image' => 'itar', 'sizes' => array( '6 ml' => '650', '12 ml' => '1150' ), 'short' => 'An everyday fragrance ritual in a compact bottle. Explore the sample size options.', 'description' => '<h2>A fragrance to make your own</h2><p>A small bottle can become part of a familiar daily ritual. This sample listing shows the Maqwi fragrance presentation, with bottle sizes and room for the final scent notes.</p><h3>Your personal ritual</h3><p>Fragrance family, notes, concentration, ingredients and alcohol content will be confirmed for the actual blend. This sample does not claim a particular formulation or lasting time.</p>', 'usage' => 'Proposed caution for approval: external use only. Avoid eyes and broken skin; stop use if irritation occurs. Follow the final approved label.', 'storage' => 'Proposed guidance for approval: keep closed, away from heat and direct sunlight, and out of reach of children.' ),
    );
}
function maqwi_preview_pages() {
    return array(
        'about' => array( 'Our Story', '<p class="mq-eyebrow">The Maqwi beginning</p><h2>Old favourites. A new chapter.</h2><p>Maqwi Organics is a Pakistan-based startup bringing honey, desi ghee, achaar and itar together around everyday traditions.</p><p>A spoonful beside breakfast. A familiar finishing touch. Something passed around the table. A fragrance you make your own. We are building Maqwi around those small rituals.</p><div class="mq-info-grid"><section class="mq-info-card"><h2>Clear information</h2><p>Our aim is to present ingredients, sizes, sourcing and care information alongside each product, so you can make an informed choice.</p></section><section class="mq-info-card"><h2>A thoughtful beginning</h2><p>The current catalogue is a working preview. We will confirm product details, photography, delivery and support before taking orders.</p></section></div><p><a href="/shop/">Explore the collection →</a></p>' ),
        'contact' => array( 'Contact', '<h2>Let’s keep the conversation clear.</h2><p>Official support channels will be published here before orders open. We are not collecting enquiries through an unfinished form.</p><div class="mq-info-grid"><section class="mq-info-card"><h2>Product questions</h2><p>Explore the descriptions and size options, then visit our frequently asked questions for the current launch information.</p><a href="/faq/">Read the FAQ →</a></section><section class="mq-info-card"><h2>Order support</h2><p>Orders are currently closed. Contact details and support hours are awaiting confirmation.</p><a href="/delivery/">Delivery information →</a></section></div>' ),
        'faq' => array( 'Frequently Asked Questions', '<h2>Before you choose</h2><details><summary>Can I place an order?</summary><p>Not yet. You can explore products, select sample sizes, add them to your bag and review checkout. No order can be submitted during this preview.</p></details><details><summary>Are these the final prices and products?</summary><p>No. The catalogue contains editable sample prices and sizes. Packaging images are concepts. Final details will be approved before sale.</p></details><details><summary>Where will you deliver?</summary><p>We are preparing for customers in Pakistan. Coverage, delivery charges and timings will be confirmed before launch. The checkout delivery rate is a sample.</p></details><details><summary>What payment methods will be available?</summary><p>Cash on delivery is planned, subject to confirmation. No payments are collected in the preview.</p></details><details><summary>Where can I find ingredients and allergens?</summary><p>Each product has a Product details tab. Unconfirmed information is clearly marked; use only the approved final label when products launch.</p></details><details><summary>Can I return a product?</summary><p>The final returns policy is awaiting approval. See the Returns page for the information we will confirm before taking orders.</p></details><p><a href="/shop/">Back to the collection →</a></p>' ),
        'delivery' => array( 'Delivery Information', '<h2>From our collection to your door</h2><p>We are preparing delivery within Pakistan. Final coverage, courier, charges and dispatch timings are awaiting approval.</p><div class="mq-info-grid"><section class="mq-info-card"><h2>For this preview</h2><p>Checkout uses a sample PKR 250 delivery rate to help test totals. This is not a confirmed delivery charge or promise of service.</p></section><section class="mq-info-card"><h2>Before orders open</h2><p>We will publish eligible areas, costs, dispatch times, tracking arrangements and the process for delivery issues.</p></section></div><h2>Your delivery details</h2><p>The checkout is designed for a full name, Pakistani mobile number, province, city and complete address, with an optional nearby landmark.</p><p><a href="/faq/">More launch questions →</a></p>' ),
        'returns' => array( 'Returns & Order Support', '<h2>A clear process before you order</h2><p>Our final returns and cancellation policy is awaiting approval. No return window, refund entitlement or exchange terms are promised by this preview.</p><div class="mq-info-grid"><section class="mq-info-card"><h2>What we will confirm</h2><p>Eligible products, condition requirements, cancellation cut-offs, return costs and refund handling will be published before sale.</p></section><section class="mq-info-card"><h2>If something arrives wrong</h2><p>The approved policy will explain how to report incorrect or damaged items and which details our support team needs.</p></section></div><p>Orders are closed; there are no preview orders to return or cancel.</p><p><a href="/contact/">Contact information →</a></p>' ),
        'privacy-preview' => array( 'Privacy Information', '<h2>Privacy during the preview</h2><p>This site uses WordPress and WooCommerce. Adding sample items to your bag uses the browser and store session to retain your choices. Do not enter real personal information into test checkout fields.</p><p>Orders and account registration are disabled in the storefront preview. An approved privacy policy identifying the business, service providers, retention periods, lawful processing and contact details is required before the store opens.</p><p>This page is launch information, not a substitute for the final approved privacy policy.</p>' ),
    );
}
add_action( 'admin_post_maqwi_seed_preview', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) || ! current_user_can( 'publish_products' ) || ! current_user_can( 'publish_pages' ) ) { wp_die( 'You cannot prepare this storefront.' ); }
    check_admin_referer( 'maqwi_seed_preview' );
    // The sales guard is enabled before creating any public samples.
    update_option( 'maqwi_brand_launch', 'yes' );
    if ( ! maqwi_brand_launch_mode() ) { wp_die( 'The order guard could not be enabled.' ); }
    try {
        foreach ( maqwi_sample_catalogue() as $slug => $item ) {
            $existing = get_page_by_path( $slug, OBJECT, 'product' );
            if ( $existing ) {
                $product = wc_get_product( $existing->ID );
                if ( ! $product || 'yes' !== $product->get_meta( '_maqwi_sample' ) ) { throw new Exception( 'A non-sample product already uses a sample slug. No existing product was changed.' ); }
                continue;
            }
            $term = get_term_by( 'slug', $item['category'], 'product_cat' );
            if ( ! $term ) { throw new Exception( 'A required collection category is missing.' ); }
            $product = new WC_Product_Variable();
            $product->set_name( $item['name'] );
            $product->set_slug( $slug );
            $product->set_status( 'draft' );
            $product->set_catalog_visibility( 'visible' );
            $product->set_featured( true );
            $product->set_category_ids( array( $term->term_id ) );
            $product->set_description( $item['description'] . '<p><strong>Sample catalogue:</strong> product specifications, photography, prices and sizes are for review. Orders remain closed.</p>' );
            $product->set_short_description( $item['short'] );
            $product->set_reviews_allowed( false );
            $product->update_meta_data( '_maqwi_sample', 'yes' );
            $product->update_meta_data( '_maqwi_concept_image', $item['image'] );
            $product->update_meta_data( '_maqwi_net_content', implode( ' / ', array_keys( $item['sizes'] ) ) . ' — sample sizes' );
            $product->update_meta_data( '_maqwi_ingredients', 'Final ingredient list awaiting approval.' );
            $product->update_meta_data( '_maqwi_allergens', 'Final allergen information awaiting approval; do not assume allergen-free.' );
            $product->update_meta_data( '_maqwi_origin', 'Source and preparation details awaiting supplier confirmation.' );
            $product->update_meta_data( '_maqwi_storage', $item['storage'] );
            $product->update_meta_data( '_maqwi_usage', $item['usage'] );
            $attribute = new WC_Product_Attribute();
            $attribute->set_name( 'Size' );
            $attribute->set_options( array_keys( $item['sizes'] ) );
            $attribute->set_visible( true );
            $attribute->set_variation( true );
            $product->set_attributes( array( $attribute ) );
            $id = $product->save();
            foreach ( $item['sizes'] as $size => $price ) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id( $id );
                $variation->set_attributes( array( 'size' => $size ) );
                $variation->set_regular_price( $price );
                $variation->set_status( 'publish' );
                $variation->set_stock_status( 'instock' );
                $variation->set_manage_stock( false );
                $variation->save();
            }
            WC_Product_Variable::sync( $id );
            $product = wc_get_product( $id );
            $product->set_status( 'publish' );
            $product->save();
        }
        foreach ( maqwi_preview_pages() as $slug => $page ) {
            $existing = get_page_by_path( $slug, OBJECT, 'page' );
            if ( $existing ) { continue; }
            $id = wp_insert_post( array( 'post_type' => 'page', 'post_title' => $page[0], 'post_name' => $slug, 'post_status' => 'publish', 'post_content' => '<!-- wp:html -->' . $page[1] . '<!-- /wp:html -->', 'meta_input' => array( '_maqwi_sample_page' => 'yes' ) ), true );
            if ( is_wp_error( $id ) ) { throw new Exception( 'An information page could not be created.' ); }
        }
        update_option( 'maqwi_storefront_preview', 'yes' );
        wc_delete_product_transients();
        if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
    } catch ( Exception $error ) {
        wp_die( esc_html( $error->getMessage() ) . ' Orders remain blocked. Check the generated draft/sample records before retrying.' );
    }
    wp_safe_redirect( admin_url( 'admin.php?page=maqwi-store-setup&preview=ready' ) );
    exit;
} );


/** Clear preview wording instead of stock payment and privacy promises. */
add_filter( 'woocommerce_no_available_payment_methods_message', function ( $message ) {
    return maqwi_storefront_preview() ? 'Payment options will be enabled before launch. Preview orders cannot be submitted.' : $message;
} );
add_filter( 'woocommerce_get_privacy_policy_text', function ( $text, $type ) {
    return maqwi_storefront_preview() ? 'Use sample details to test this preview. No order will be created. Read the privacy information before the final store launches.' : $text;
}, 10, 2 );
/** Purge native page cache once when this approved storefront code release is first executed. */
add_action( 'init', function () {
    $release = '0.2.5/' . wp_get_theme()->get( 'Version' );
    if ( maqwi_storefront_preview() && $release !== get_option( 'maqwi_preview_cache_release', '' ) && function_exists( 'w3tc_flush_all' ) ) {
        delete_transient( 'wc_shipping_method_count' );
        if ( class_exists( 'WC_Cache_Helper' ) ) { WC_Cache_Helper::get_transient_version( 'shipping', true ); }
        w3tc_flush_all();
        update_option( 'maqwi_preview_cache_release', $release, false );
    }
}, 99 );

/** Sample delivery must remain testable even while real shipping is unconfigured. */
add_filter( 'wc_shipping_enabled', function ( $enabled ) { return maqwi_storefront_preview() ? true : $enabled; } );
add_filter( 'woocommerce_cart_ready_to_calc_shipping', function ( $ready ) { return maqwi_storefront_preview() ? true : $ready; } );

/** A preview-only native legacy method makes delivery calculable without creating real zones. */
add_action( 'woocommerce_shipping_init', function () {
    if ( class_exists( 'Maqwi_Preview_Shipping' ) ) { return; }
    class Maqwi_Preview_Shipping extends WC_Shipping_Method {
        public function __construct() {
            $this->id = 'maqwi_preview';
            $this->method_title = 'Maqwi sample delivery';
            $this->method_description = 'Preview only. Not an approved courier or delivery tariff.';
            $this->title = 'Sample delivery — final rate to be confirmed';
            $this->enabled = maqwi_storefront_preview() ? 'yes' : 'no';
            $this->supports = array();
        }
        public function calculate_shipping( $package = array() ) {
            if ( maqwi_storefront_preview() && 'PK' === ( $package['destination']['country'] ?? '' ) ) {
                $this->add_rate( array( 'id' => $this->id, 'label' => $this->title, 'cost' => 250, 'taxes' => false ) );
            }
        }
    }
} );
add_filter( 'woocommerce_shipping_methods', function ( $methods ) {
    if ( maqwi_storefront_preview() ) { $methods['maqwi_preview'] = 'Maqwi_Preview_Shipping'; }
    return $methods;
} );
