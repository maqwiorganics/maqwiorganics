<?php
/** Maqwi presentation layer. Commerce data remains in WooCommerce. */
defined( 'ABSPATH' ) || exit;

/** Editorial hero replaces only the saved homepage hero group at render time. */
function maqwi_premium_hero() {
    $assets = get_stylesheet_directory_uri() . '/assets/images/';
    $slides = array(
        array( 'file' => 'hero-collection-v3.webp', 'alt' => 'Maqwi Honey and Desi Ghee labelled 1 kg, Achaar and 12 ml Itar in a minimal ivory studio', 'name' => 'The collection' ),
        array( 'file' => 'hero-table-v3.webp', 'alt' => 'Maqwi Honey and Desi Ghee labelled 1 kg on understated ivory display plinths', 'name' => 'Honey & Ghee' ),
        array( 'file' => 'hero-itar-v3.webp', 'alt' => 'Maqwi Itar in a gold-capped 12 ml bottle on a pale stone studio plinth', 'name' => 'Personal fragrance' ),
    );
    ob_start(); ?>
    <section class="mq-premium-hero mq-slideshow-hero" aria-labelledby="mq-premium-title">
        <div class="mq-premium-copy">
            <p class="mq-eyebrow mq-premium-eyebrow"><span aria-hidden="true"></span>Maqwi Organics · Pakistan</p>
            <h1 id="mq-premium-title"><span class="mq-hero-line">Everyday essentials.</span><span class="mq-hero-line">Rooted in</span><span class="mq-hero-line">tradition.</span></h1>
            <p class="mq-premium-description">Honey, desi ghee, achaar and itar — familiar favourites for everyday living.</p>
            <div class="mq-premium-actions">
                <a class="mq-premium-primary" href="#the-collection">Explore the collection <span aria-hidden="true">↗</span></a>
                <a class="mq-premium-story" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Our story <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="mq-hero-gallery" role="region" aria-roledescription="carousel" aria-label="Maqwi collection photographs">
            <div class="mq-premium-media">
                <?php foreach ( $slides as $index => $slide ) : ?>
                <figure class="mq-hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' of 3: ' . $slide['name'] ); ?>"<?php if ( $index ) { echo ' hidden'; } ?>>
                    <img src="<?php echo esc_url( $assets . $slide['file'] . '?v=studio-1' ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>" width="1440" height="960" loading="<?php echo $index ? 'lazy' : 'eager'; ?>" decoding="async"<?php if ( ! $index ) { echo ' fetchpriority="high"'; } ?>>
                </figure>
                <?php endforeach; ?>
            </div>
            <div class="mq-hero-controls" hidden>
                <div class="mq-hero-slide-label" aria-hidden="true"><span class="mq-hero-index">01</span><span class="mq-hero-control-rule"></span><span class="mq-hero-name">The collection</span></div>
                <div class="mq-hero-control-buttons">
                    <button class="mq-hero-previous" type="button" aria-label="Previous photograph"><svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m14 6-6 6 6 6"/></svg></button>
                    <div class="mq-hero-dots" role="group" aria-label="Choose a photograph">
                        <?php foreach ( $slides as $index => $slide ) : ?><button type="button" data-slide="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( 'Show ' . strtolower( $slide['name'] ) ); ?>"<?php if ( ! $index ) { echo ' aria-current="true"'; } ?>><span></span></button><?php endforeach; ?>
                    </div>
                    <button class="mq-hero-next" type="button" aria-label="Next photograph"><svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m10 6 6 6-6 6"/></svg></button>
                    <button class="mq-hero-pause" type="button" aria-label="Pause slideshow" aria-pressed="false"><svg class="mq-pause-symbol" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 6v12M15 6v12"/></svg><svg class="mq-play-symbol" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m9 5 10 7-10 7V5Z"/></svg></button>
                </div>
            </div>
            <p class="mq-hero-gallery-note">Product presentation shown. Refer to each listing for pack details.</p>
            <span class="mq-hero-status mq-visually-hidden" aria-live="polite" aria-atomic="true"></span>
        </div>
    </section>
    <?php return ob_get_clean();
}
add_filter( 'render_block_core/group', function ( $html, $block ) {
    $classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
    return is_front_page() && version_compare( wp_get_theme()->get( 'Version' ), '0.6.0', '>=' ) && in_array( 'mq-hero', $classes, true ) ? maqwi_premium_hero() : $html;
}, 20, 2 );

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
    if ( maqwi_public_brand_mode() ) { return maqwi_home_anchor( 'the-collection' ); }
    $url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
    return $url && -1 !== $url ? $url : home_url( '/' );
}

/** Only public, purchasable, in-stock WooCommerce products appear as featured. */
add_shortcode( 'maqwi_featured_products', function () {
    if ( maqwi_public_brand_mode() ) { return maqwi_brand_collection_details(); }
    if ( ! function_exists( 'wc_get_products' ) ) {
        return '';
    }
    $products = wc_get_products( array( 'status' => 'publish', 'featured' => true, 'limit' => 4 ) );
    $products = array_filter( $products, function ( $product ) {
        return $product->is_visible() && ( $product->is_purchasable() || $product->get_meta( '_maqwi_catalogue_release' ) );
    } );
    if ( ! $products ) {
        return '';
    }
    ob_start();
    ?>
    <section class="mq-section mq-featured" aria-labelledby="mq-featured-title">
        <div class="mq-section-top"><div><p class="mq-eyebrow">Shop Maqwi</p><h2 id="mq-featured-title">Explore the collection</h2></div><a href="<?php echo esc_url( maqwi_shop_url() ); ?>">View all products &rarr;</a></div>
        <div class="mq-product-grid">
        <?php foreach ( $products as $product ) : ?>
            <article class="mq-product-card"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ) ); ?><h3><?php echo esc_html( $product->get_name() ); ?></h3></a><div class="mq-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div><a class="mq-text-link" href="<?php echo esc_url( $product->get_permalink() ); ?>">View product &rarr;</a></article>
        <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

/** Fallback links stay useful before an owner has assigned a menu. */
function maqwi_default_navigation() {
    echo '<ul class="mq-menu"><li><a href="' . esc_url( maqwi_shop_url() ) . '">' . ( maqwi_public_brand_mode() ? 'Collection' : 'Shop' ) . '</a></li><li><a href="' . esc_url( home_url( '/about/' ) ) . '">Our story</a></li><li><a href="' . esc_url( home_url( '/faq/' ) ) . '">Questions</a></li><li><a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact</a></li></ul>';
}

/** Add delivery attributes at render time so native Image blocks round-trip cleanly. */
add_filter( 'render_block_core/image', function ( $html, $block ) {
    if ( get_option( 'maqwi_catalogue_release' ) ) {
        $names = array( 'honey' => 'honey', 'ghee' => 'desi-ghee', 'achaar' => 'achaar', 'itar' => 'itar' );
        foreach ( $names as $old => $name ) {
            $html = str_replace( get_stylesheet_directory_uri() . '/assets/images/' . $old . '.webp', plugins_url( 'photography/01-' . $name . '-catalogue.webp', WP_PLUGIN_DIR . '/maqwi-store-core/maqwi-store-core.php' ), $html );
        }
        if ( str_contains( $html, '/photography/01-' ) ) {
            $html = preg_replace( '#<figcaption\b[^>]*>.*?</figcaption>#s', '', $html );
            $html = str_replace( ' packaging concept', '', $html );
        }
    }
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
    return function_exists( 'maqwi_brand_launch_mode' ) && maqwi_brand_launch_mode() && !( function_exists( 'maqwi_storefront_preview' ) && maqwi_storefront_preview() );
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
        <div class="mq-section-top"><div><p class="mq-eyebrow">Meet the Maqwi collection</p><h2 id="mq-collection-details-title">Four favourites.<br>Explore the Maqwi range.</h2></div><p class="mq-section-aside">Explore each collection below.<br>Orders open once the details are ready.</p></div>
        <div class="mq-collection-detail-list">
        <?php $number = 0; foreach ( maqwi_brand_collections() as $slug => $item ) : $number++; ?>
            <details class="mq-collection-detail">
                <summary><span class="mq-detail-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $number ) ); ?></span><span><span class="mq-detail-title"><?php echo esc_html( $item['name'] ); ?></span><span class="mq-detail-line"><?php echo esc_html( $item['line'] ); ?></span></span><span class="mq-detail-toggle" aria-hidden="true"></span></summary>
                <div id="<?php echo esc_attr( 'collection-' . $slug ); ?>" class="mq-detail-body"><figure><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $item['image'] . '.webp' ); ?>" alt="<?php echo esc_attr( 'Maqwi ' . $item['name'] . '' ); ?>" width="900" height="900" loading="lazy" decoding="async"></figure><div><p class="mq-eyebrow">Maqwi collection</p><h3><?php echo esc_html( $item['line'] ); ?></h3><p><?php echo esc_html( $item['copy'] ); ?></p><p><?php echo esc_html( $item['details'] ); ?></p><p class="mq-detail-note">Artwork is an early. Final product details, photographs, prices and availability will be confirmed before orders open.</p><a class="mq-text-link" href="<?php echo esc_url( maqwi_home_anchor( 'questions' ) ); ?>">Before you order <span aria-hidden="true">&nbsp;↗</span></a></div></div>
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
                $processor->set_attribute( 'href', maqwi_home_anchor( 'collection-' . $slug ) );
                break;
            }
        }
    }
    return $processor->get_updated_html();
} );
/** Rewrite only known homepage block copy; retain the editable block structure. */
add_filter( 'render_block', function ( $html, $block ) {
    if ( ! is_front_page() || ! function_exists( 'maqwi_customer_copy' ) ) { return $html; }
    if ( 'core/paragraph' === $block['blockName'] && str_contains( $block['attrs']['className'] ?? '', 'mq-artwork-note' ) ) { return ''; }
    return strtr( $html, maqwi_customer_copy() );
}, 30, 2 );

/** Accurate sharing metadata; no unverified business contacts or product offers. */
add_filter( 'document_title_parts', function ( $parts ) {
    if ( is_front_page() ) { $parts['title'] = 'Maqwi Organics'; $parts['tagline'] = 'Honey, Desi Ghee, Achaar & Itar'; }
    return $parts;
} );
add_action( 'wp_head', function () {
    if ( ! is_front_page() ) { return; }
    $description = 'Explore Maqwi Honey, Desi Ghee, Achaar and Itar. View pack sizes, prices and product details. Orders open soon.';
    $image = get_option( 'maqwi_collection_banner' ) ? wp_get_attachment_image_url( get_option( 'maqwi_collection_banner' ), 'full' ) : get_stylesheet_directory_uri() . '/assets/images/honey.webp';
    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    foreach ( array( 'og:type' => 'website', 'og:title' => 'Maqwi Organics', 'og:description' => $description, 'og:url' => home_url( '/' ), 'og:image' => $image, 'og:image:alt' => 'Maqwi Organics product collection', 'og:site_name' => 'Maqwi Organics' ) as $name => $value ) {
        echo '<meta property="' . esc_attr( $name ) . '" content="' . esc_attr( $value ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 5 );

/** Stay in the current homepage document, including normal host query redirects. */
function maqwi_home_anchor( $anchor ) {
    return is_front_page() ? '#' . $anchor : home_url( '/#' . $anchor );
}

/** Preview cards, galleries and safety label retain normal native WooCommerce data. */
add_action( 'woocommerce_before_single_product', function () {
    global $product;
    if ( $product && $product->get_meta( '_maqwi_concept_image' ) && ! $product->get_image_id() ) { remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 ); }
} );
add_action( 'woocommerce_before_single_product_summary', function () {
    global $product;
    if ( ! $product || ! $product->get_meta( '_maqwi_concept_image' ) || $product->get_image_id() ) { return; }
    echo '<div class="mq-concept-gallery"><img src="' . esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $product->get_meta( '_maqwi_concept_image' ) . '.webp' ) . '" alt="' . esc_attr( $product->get_name() . '' ) . '" width="900" height="900"></div>';
}, 19 );
add_filter( 'woocommerce_product_get_image', function ( $html, $product, $size, $attr, $placeholder ) {
    $image = $product->get_meta( '_maqwi_concept_image' );
    if ( ! $image && $product->get_parent_id() ) { $parent = wc_get_product( $product->get_parent_id() ); $image = $parent ? $parent->get_meta( '_maqwi_concept_image' ) : ''; }
    if ( ! $image || $product->get_image_id() || ! in_array( $image, array( 'honey', 'ghee', 'achaar', 'itar' ), true ) ) { return $html; }
    return '<img class="mq-product-concept" src="' . esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $image . '.webp' ) . '" alt="' . esc_attr( $product->get_name() . '' ) . '" width="900" height="900" loading="lazy" decoding="async">';
}, 10, 5 );
add_action( 'woocommerce_single_product_summary', function () {
    global $product;
    if ( $product && 'yes' === $product->get_meta( '_maqwi_sample' ) ) { echo '<p class="mq-sample-note">Ordering opens soon.</p>'; }
}, 11 );
add_action( 'woocommerce_before_shop_loop', function () {
    if ( ! get_option( 'maqwi_catalogue_release' ) && function_exists( 'maqwi_storefront_preview' ) && maqwi_storefront_preview() ) { echo '<p class="mq-sample-note">Ordering opens soon.</p>'; }
}, 5 );

/** Clear page caches once after the reviewed campaign release. */
add_action( 'wp_loaded', function () {
    if ( get_option( 'maqwi_hero_scene_release' ) === '2026-10-17-studio-1' ) { return; }
    update_option( 'maqwi_hero_scene_release', '2026-10-17-studio-1', false );
    if ( function_exists( 'w3tc_flush_all' ) ) { w3tc_flush_all(); }
}, 55 );
