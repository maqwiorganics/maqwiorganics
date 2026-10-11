<?php
/** Keep native WooCommerce content, hooks and forms inside the Maqwi shell. */
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-commerce">
    <?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true"> / </span>' ) ); ?>
    <?php if ( ! is_product() ) : ?>
        <div class="mq-shop-intro"><p class="mq-eyebrow">The Maqwi collection</p>
        <p>Desi favourites for your kitchen, table and everyday rituals.</p></div>
        <?php maqwi_collection_navigation(); ?>
        <div class="mq-shop-search"><?php get_product_search_form(); ?></div>
    <?php endif; ?>
    <div class="woocommerce mq-commerce-content"><?php woocommerce_content(); ?></div>
</main>
<?php get_footer( 'maqwi' ); ?>
