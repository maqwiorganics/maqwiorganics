<?php
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-page mq-404"><p class="mq-eyebrow">Maqwi Organics · 404</p><h1>This page has wandered.</h1><p>The link may have changed, or this page may no longer be available. Our collection and story are waiting back at home.</p><div class="mq-404-links"><a class="mq-button-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Maqwi</a><a class="mq-text-link" href="<?php echo esc_url( home_url( '/#the-collection' ) ); ?>">Meet the collection <span aria-hidden="true">↗</span></a></div></main>
<?php get_footer( 'maqwi' ); ?>
