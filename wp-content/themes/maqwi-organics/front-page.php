<?php
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-home">
<?php
if ( is_page() && have_posts() ) {
    while ( have_posts() ) {
        the_post();
        if ( trim( get_the_content() ) ) {
            the_content();
        } else {
            $pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'maqwi-organics/home' );
            if ( $pattern ) { echo do_blocks( $pattern['content'] ); }
        }
    }
} else {
    $pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'maqwi-organics/home' );
    if ( $pattern ) { echo do_blocks( $pattern['content'] ); }
}
?>
</main>
<?php get_footer( 'maqwi' ); ?>
