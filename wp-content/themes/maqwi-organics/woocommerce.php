<?php
/** Keep native WooCommerce content, hooks and forms inside the Maqwi shell. */
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-commerce">
    <?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true"> / </span>' ) ); ?>
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
