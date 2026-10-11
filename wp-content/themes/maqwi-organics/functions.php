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
    wp_enqueue_script( 'maqwi-public', get_stylesheet_directory_uri() . '/assets/public.js', array(), wp_get_theme()->get( 'Version' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
    if ( function_exists( 'WC' ) && ! maqwi_public_brand_mode() ) { wp_enqueue_script( 'wc-cart-fragments' ); }
}, 30 );

/** Return configured commerce routes without assuming page IDs or slugs. */
function maqwi_shop_url() {
    if ( function_exists( 'maqwi_brand_launch_mode' ) && maqwi_brand_launch_mode() ) { return home_url( '/#the-collection' ); }
    $url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
    return $url && -1 !== $url ? $url : home_url( '/' );
}

/** Only public, purchasable, in-stock WooCommerce products appear as featured. */
add_shortcode( 'maqwi_featured_products', function () {
    if ( maqwi_public_brand_mode() ) { return maqwi_brand_collection_details(); }
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
    $html = str_replace( 'Packaging concept · final product photography follows before launch.', 'Packaging concept · final photography follows before orders open.', $html );
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

/** Brand content remains separate from unapproved WooCommerce product data. */
function maqwi_public_brand_mode() {
    return function_exists( 'maqwi_brand_launch_mode' ) && maqwi_brand_launch_mode();
}
function maqwi_brand_collections() {
    return array(
        'honey' => array( 'name' => 'Honey', 'image' => 'honey', 'line' => 'A little sweetness in the everyday.', 'copy' => 'From a spoonful beside breakfast to the cup of tea you make your own, honey has a familiar place at the table.', 'details' => 'Varieties, net weight, origin, ingredients and storage information will accompany the final products.' ),
        'desi-ghee' => array( 'name' => 'Desi Ghee', 'image' => 'ghee', 'line' => 'A familiar finishing touch.', 'copy' => 'Some meals are remembered by the smallest details. Desi ghee is part of the food traditions that inspired the Maqwi collection.', 'details' => 'Ingredients, dairy allergen information, pack sizes, sourcing and storage instructions will be shared before ordering opens.' ),
        'achaar' => array( 'name' => 'Achaar', 'image' => 'achaar', 'line' => 'The extra beside a favourite meal.', 'copy' => 'Passed around the table or kept beside a favourite plate, achaar is a small ritual with a place in everyday meals.', 'details' => 'The final flavours, ingredient lists, allergens, net weights and storage guidance will be listed with each product.' ),
        'itar' => array( 'name' => 'Itar', 'image' => 'itar', 'line' => 'A fragrance. A personal ritual.', 'copy' => 'A fragrance can feel familiar before you can name it. Itar brings a different kind of everyday tradition to the Maqwi collection.', 'details' => 'Fragrance descriptions, bottle sizes, ingredients and safe-use guidance will be provided with the final range.' ),
    );
}
function maqwi_brand_collection_details() {
    ob_start(); ?>
    <section class="mq-section mq-collection-details" aria-labelledby="mq-collection-details-title">
        <div class="mq-section-top"><div><p class="mq-eyebrow">Meet the Maqwi collection</p><h2 id="mq-collection-details-title">Four favourites.<br>Room for your own ritual.</h2></div><p class="mq-section-aside">Explore each collection below.<br>Orders open once the details are ready.</p></div>
        <div class="mq-collection-detail-list">
        <?php $number = 0; foreach ( maqwi_brand_collections() as $slug => $item ) : $number++; ?>
            <details id="<?php echo esc_attr( 'collection-' . $slug ); ?>" class="mq-collection-detail">
                <summary><span class="mq-detail-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $number ) ); ?></span><span><span class="mq-detail-title"><?php echo esc_html( $item['name'] ); ?></span><span class="mq-detail-line"><?php echo esc_html( $item['line'] ); ?></span></span><span class="mq-detail-toggle" aria-hidden="true"></span></summary>
                <div class="mq-detail-body"><figure><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $item['image'] . '.webp' ); ?>" alt="<?php echo esc_attr( 'Maqwi ' . $item['name'] . ' packaging concept' ); ?>" width="900" height="900" loading="lazy" decoding="async"><figcaption>Packaging concept</figcaption></figure><div><p class="mq-eyebrow">Collection in preparation</p><h3><?php echo esc_html( $item['line'] ); ?></h3><p><?php echo esc_html( $item['copy'] ); ?></p><p><?php echo esc_html( $item['details'] ); ?></p><p class="mq-detail-note">Artwork is an early packaging concept. Final product details, photographs, prices and availability will be confirmed before orders open.</p><a class="mq-text-link" href="<?php echo esc_url( home_url( '/#questions' ) ); ?>">Before you order <span aria-hidden="true">&nbsp;↗</span></a></div></div>
            </details>
        <?php endforeach; ?>
        </div>
    </section>
    <?php return ob_get_clean();
}

/** Native saved category headings point straight to their brand details. */
add_filter( 'render_block_core/heading', function ( $html ) {
    if ( ! maqwi_public_brand_mode() || ! is_front_page() ) { return $html; }
    $processor = new WP_HTML_Tag_Processor( $html );
    while ( $processor->next_tag( 'a' ) ) {
        $url = $processor->get_attribute( 'href' );
        foreach ( maqwi_brand_collections() as $slug => $item ) {
            $term = get_term_by( 'slug', $slug, 'product_cat' );
            if ( ! $term ) { continue; }
            $category_url = get_term_link( $term );
            if ( ! is_wp_error( $category_url ) && $url === $category_url ) {
                $processor->set_attribute( 'href', home_url( '/#collection-' . $slug ) );
                break;
            }
        }
    }
    return $processor->get_updated_html();
} );
add_filter( 'render_block_core/paragraph', function ( $html ) {
    if ( ! maqwi_public_brand_mode() || ! is_front_page() ) { return $html; }
    return str_replace( 'We are preparing the collection and testing the store before launch.', 'We are preparing the collection. Product details, prices and delivery information will be shared before orders open.', $html );
} );

/** Accurate sharing metadata; no unverified business contacts or product offers. */
add_filter( 'document_title_parts', function ( $parts ) {
    if ( is_front_page() ) { $parts['title'] = 'Maqwi Organics'; $parts['tagline'] = 'Honey, Desi Ghee, Achaar & Itar'; }
    return $parts;
} );
add_action( 'wp_head', function () {
    if ( ! is_front_page() ) { return; }
    $description = 'Meet Maqwi Organics: honey, desi ghee, achaar and itar, rooted in everyday traditions. Explore the collection. Orders open soon.';
    $image = get_stylesheet_directory_uri() . '/assets/images/honey.webp';
    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    foreach ( array( 'og:type' => 'website', 'og:title' => 'Maqwi Organics', 'og:description' => $description, 'og:url' => home_url( '/' ), 'og:image' => $image, 'og:image:alt' => 'Maqwi honey packaging concept', 'og:site_name' => 'Maqwi Organics' ) as $name => $value ) {
        echo '<meta property="' . esc_attr( $name ) . '" content="' . esc_attr( $value ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 5 );
