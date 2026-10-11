<?php
/** Maqwi presentation layer. Commerce data remains in WooCommerce. */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
    register_nav_menus( array( 'maqwi-primary' => __( 'Maqwi primary navigation', 'maqwi-organics' ) ) );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'maqwi-organics', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    if ( function_exists( 'WC' ) ) { wp_enqueue_script( 'wc-cart-fragments' ); }
}, 30 );

/** Return configured commerce routes without assuming page IDs or slugs. */
function maqwi_shop_url() {
    if ( function_exists( 'maqwi_brand_launch_mode' ) && maqwi_brand_launch_mode() ) { return home_url( '/#the-collection' ); }
    $url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
    return $url && -1 !== $url ? $url : home_url( '/' );
}

/** Only public, purchasable, in-stock WooCommerce products appear as featured. */
add_shortcode( 'maqwi_featured_products', function () {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return '';
    }
    $products = wc_get_products( array( 'status' => 'publish', 'featured' => true, 'stock_status' => 'instock', 'limit' => 4 ) );
    $products = array_filter( $products, function ( $product ) {
        return $product->is_visible() && $product->is_purchasable();
    } );
    if ( ! $products ) {
        return '';
    }
    ob_start();
    ?>
    <section class="mq-section mq-featured" aria-labelledby="mq-featured-title">
        <div class="mq-section-top"><div><p class="mq-eyebrow">A place in your everyday</p><h2 id="mq-featured-title">Explore the collection</h2></div><a href="<?php echo esc_url( maqwi_shop_url() ); ?>">View all products &rarr;</a></div>
        <div class="mq-product-grid">
        <?php foreach ( $products as $product ) : ?>
            <article class="mq-product-card"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ) ); ?><h3><?php echo esc_html( $product->get_name() ); ?></h3></a><div class="mq-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div><a class="mq-text-link" href="<?php echo esc_url( $product->get_permalink() ); ?>">Choose options &rarr;</a></article>
        <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

/** Fallback links stay useful before an owner has assigned a menu. */
function maqwi_default_navigation() {
    echo '<ul class="mq-menu"><li><a href="' . esc_url( maqwi_shop_url() ) . '">' . ( function_exists( 'maqwi_brand_launch_mode' ) && maqwi_brand_launch_mode() ? 'Collection' : 'Shop' ) . '</a></li><li><a href="' . esc_url( home_url( '/#our-story' ) ) . '">Our story</a></li><li><a href="' . esc_url( home_url( '/#questions' ) ) . '">Questions</a></li></ul>';
}

/** Add delivery attributes at render time so native Image blocks round-trip cleanly. */
add_filter( 'render_block_core/image', function ( $html, $block ) {
    $processor = new WP_HTML_Tag_Processor( $html );
    if ( ! $processor->next_tag( 'img' ) ) { return $html; }
    $src = $processor->get_attribute( 'src' );
    $prefix = get_stylesheet_directory_uri() . '/assets/images/';
    if ( ! is_string( $src ) || ! str_starts_with( $src, $prefix ) ) { return $html; }
    $hero = str_contains( $block['attrs']['className'] ?? '', 'mq-hero-media' );
    $processor->set_attribute( 'width', 900 );
    $processor->set_attribute( 'height', 900 );
    $processor->set_attribute( 'loading', $hero ? 'eager' : 'lazy' );
    $processor->set_attribute( 'decoding', 'async' );
    if ( $hero ) { $processor->set_attribute( 'fetchpriority', 'high' ); }
    return $processor->get_updated_html();
}, 10, 2 );

/** A small, live cart indicator uses WooCommerce's native fragment refresh. */
function maqwi_cart_count() {
    $count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    return '<span class="mq-bag-count" aria-label="' . esc_attr( sprintf( _n( '%d item in bag', '%d items in bag', $count, 'maqwi-organics' ), $count ) ) . '">' . esc_html( $count ) . '</span>';
}
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    $fragments['span.mq-bag-count'] = maqwi_cart_count();
    return $fragments;
} );

/** Category links resolve real terms and remain available for an empty catalogue. */
function maqwi_collection_navigation() {
    if ( ! taxonomy_exists( 'product_cat' ) ) { return; }
    $labels = array( 'honey' => 'Honey', 'desi-ghee' => 'Desi ghee', 'achaar' => 'Achaar', 'itar' => 'Itar' );
    echo '<nav class="mq-collection-nav" aria-label="Product collections"><a href="' . esc_url( maqwi_shop_url() ) . '"' . ( is_shop() ? ' aria-current="page"' : '' ) . '>All collections</a>';
    foreach ( $labels as $slug => $label ) {
        $term = get_term_by( 'slug', $slug, 'product_cat' );
        if ( ! $term ) { continue; }
        $url = get_term_link( $term );
        if ( is_wp_error( $url ) ) { continue; }
        echo '<a href="' . esc_url( $url ) . '"' . ( is_product_category( $slug ) ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a>';
    }
    echo '</nav>';
}

/** Commerce and regular pages share the storefront shell; Woo owns all forms. */
add_filter( 'template_include', function ( $template ) {
    if ( ! is_front_page() && is_page() ) {
        return get_stylesheet_directory() . '/page-maqwi.php';
    }
    return $template;
}, 99 );

add_filter( 'loop_shop_columns', function () { return 4; } );
add_filter( 'loop_shop_per_page', function () { return 12; }, 20 );
