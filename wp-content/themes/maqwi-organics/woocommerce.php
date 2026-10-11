<?php
/** Keep native WooCommerce content, hooks and forms inside the Maqwi shell. */
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-commerce">
    <?php
    // Generate native Woo crumbs once without invoking parent-theme breadcrumb output hooks.
    $breadcrumb = new WC_Breadcrumb();
    $breadcrumb->add_crumb( 'Home', home_url( '/' ) );
    $crumbs = $breadcrumb->generate();
    if ( $crumbs ) {
        echo '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">';
        foreach ( $crumbs as $index => $crumb ) {
            if ( $index ) { echo '<span aria-hidden="true"> / </span>'; }
            if ( ! empty( $crumb[1] ) && $index < count( $crumbs ) - 1 ) { echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>'; }
            else { echo '<span' . ( $index === count( $crumbs ) - 1 ? ' aria-current="page"' : '' ) . '>' . esc_html( $crumb[0] ) . '</span>'; }
        }
        echo '</nav>';
    }
    ?>
    <?php if ( ! is_product() ) : ?>
        <div class="mq-shop-intro"><div class="mq-shop-heading"><p class="mq-eyebrow">The Maqwi collection</p><h1><?php woocommerce_page_title(); ?></h1></div>
        <p>Desi favourites for your kitchen, table and everyday rituals.</p></div>
        <?php maqwi_collection_navigation(); ?>
        <div class="mq-shop-search"><?php get_product_search_form(); ?></div>
    <?php endif; ?>
    <div class="woocommerce mq-commerce-content"><?php
    if ( ! is_product() ) { add_filter( 'woocommerce_show_page_title', '__return_false', 99 ); }
    woocommerce_content();
    if ( ! is_product() ) { remove_filter( 'woocommerce_show_page_title', '__return_false', 99 ); }
    ?></div>
</main>
<?php get_footer( 'maqwi' ); ?>
