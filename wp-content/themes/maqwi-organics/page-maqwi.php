<?php
/** Native page content supplies cart, checkout, accounts and information pages. */
defined( 'ABSPATH' ) || exit;
get_header( 'maqwi' );
?>
<main id="main" class="mq-page">
<?php while ( have_posts() ) : the_post(); ?>
    <header class="mq-page-heading"><p class="mq-eyebrow">Maqwi Organics</p><h1><?php the_title(); ?></h1></header>
    <div class="mq-page-content"><?php the_content(); ?></div>
<?php endwhile; ?>
</main>
<?php get_footer( 'maqwi' ); ?>
