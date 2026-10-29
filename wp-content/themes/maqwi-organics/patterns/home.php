<?php
/**
 * Title: Maqwi home
 * Slug: maqwi-organics/home
 * Categories: featured
 * Description: A calm, mobile-first homepage for Maqwi Organics. Replace concept images before launch.
 */
defined( 'ABSPATH' ) || exit;
$assets = get_stylesheet_directory_uri() . '/assets/images/';
?>
<!-- wp:group {"className":"mq-hero","layout":{"type":"default"}} -->
<div class="wp-block-group mq-hero">
<!-- wp:group {"className":"mq-hero-copy","layout":{"type":"default"}} --><div class="wp-block-group mq-hero-copy">
<!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">Maqwi Organics · Pakistan</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">A little desi.<br>A little closer<br>to home.</h1><!-- /wp:heading -->
<!-- wp:paragraph --><p>Honey, desi ghee, achaar and itar. Familiar favourites, brought together for the rituals that make a day your own.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"mq-button"} --><div class="wp-block-button mq-button"><a class="wp-block-button__link wp-element-button" href="#the-collection">Meet the collection <span aria-hidden="true">&nbsp;↗</span></a></div><!-- /wp:button --></div><!-- /wp:buttons -->
<!-- wp:paragraph {"className":"mq-hero-note"} --><p class="mq-hero-note">Four traditions. One thoughtful beginning.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:image {"sizeSlug":"full","className":"mq-hero-media"} --><figure class="wp-block-image size-full mq-hero-media"><img src="<?php echo esc_url( $assets . 'honey.webp' ); ?>" alt="Maqwi honey packaging concept in a warm tabletop setting"><figcaption class="wp-element-caption">Packaging concept · final product photography follows before launch.</figcaption></figure><!-- /wp:image -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"mq-section mq-category-section","anchor":"the-collection","layout":{"type":"default"}} --><div id="the-collection" class="wp-block-group mq-section mq-category-section">
<!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">Rooted in everyday traditions</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Explore honey, desi ghee, achaar and itar.</h2><!-- /wp:heading -->
<!-- wp:group {"className":"mq-categories","layout":{"type":"default"}} --><div class="wp-block-group mq-categories">
<?php
$categories = array(
    array( 'honey', 'Honey', 'For your morning toast and evening tea.' ),
    array( 'ghee', 'Desi Ghee', 'A familiar finishing touch to your table.' ),
    array( 'achaar', 'Achaar', 'The little extra beside a favourite meal.' ),
    array( 'itar', 'Itar', 'A personal ritual, a familiar fragrance.' ),
);
foreach ( $categories as $category ) :
    $slug = 'ghee' === $category[0] ? 'desi-ghee' : $category[0];
    $term = get_term_by( 'slug', $slug, 'product_cat' );
    $category_url = $term ? get_term_link( $term ) : maqwi_shop_url();
    if ( is_wp_error( $category_url ) ) { $category_url = maqwi_shop_url(); }
?>
<!-- wp:group {"className":"mq-category","layout":{"type":"default"}} --><div class="wp-block-group mq-category">
<!-- wp:image {"sizeSlug":"full"} --><figure class="wp-block-image size-full"><img src="<?php echo esc_url( $assets . $category[0] . '.webp' ); ?>" alt="<?php echo esc_attr( 'Maqwi ' . $category[1] . ' packaging concept' ); ?>"></figure><!-- /wp:image -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><a href="<?php echo esc_url( $category_url ); ?>"><?php echo esc_html( $category[1] ); ?> <span aria-hidden="true">↗</span></a></h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><?php echo esc_html( $category[2] ); ?></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<?php endforeach; ?>
</div><!-- /wp:group -->
<!-- wp:paragraph {"className":"mq-artwork-note"} --><p class="mq-artwork-note">Our collection is in preparation. The artwork shows packaging concepts; final ingredients, sizes, sourcing details, prices and availability will be provided before orders open.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:shortcode -->[maqwi_featured_products]<!-- /wp:shortcode -->
<!-- wp:group {"className":"mq-story","anchor":"our-story","layout":{"type":"default"}} --><div id="our-story" class="wp-block-group mq-story">
<!-- wp:group {"className":"mq-story-inner","layout":{"type":"default"}} --><div class="wp-block-group mq-story-inner">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">The Maqwi beginning</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Old favourites.<br>A new chapter.</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph --><p>Some things belong to more than a meal. A spoonful of ghee. Achaar passed around the table. The fragrance you remember from home.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Maqwi begins with those everyday traditions. We’re building a collection around clear product information and the stories behind what you choose.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group --></div><!-- /wp:group -->
<!-- wp:group {"className":"mq-section","layout":{"type":"default"}} --><div class="wp-block-group mq-section">
<!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">What we are building towards</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Details that earn your trust.</h2><!-- /wp:heading -->
<!-- wp:group {"className":"mq-principles","layout":{"type":"default"}} --><div class="wp-block-group mq-principles">
<?php foreach ( array( array( '01', 'The story behind it', 'Where a product comes from and how it is prepared, described with care.' ), array( '02', 'The details that matter', 'Ingredients, pack sizes and storage guidance, clearly stated for each product.' ), array( '03', 'A clear next step', 'Payment, delivery and support information before you place an order.' ) ) as $principle ) : ?>
<!-- wp:group {"className":"mq-principle","layout":{"type":"default"}} --><div class="wp-block-group mq-principle"><!-- wp:paragraph {"className":"mq-number"} --><p class="mq-number"><?php echo esc_html( $principle[0] ); ?></p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><?php echo esc_html( $principle[1] ); ?></h3><!-- /wp:heading --><!-- wp:paragraph --><p><?php echo esc_html( $principle[2] ); ?></p><!-- /wp:paragraph --></div><!-- /wp:group -->
<?php endforeach; ?>
</div><!-- /wp:group --></div><!-- /wp:group -->
<!-- wp:group {"className":"mq-section mq-faq-section","anchor":"questions","layout":{"type":"default"}} --><div id="questions" class="wp-block-group mq-section mq-faq-section">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">Before we begin</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">A few things<br>you may wonder.</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"mq-faq","layout":{"type":"default"}} --><div class="wp-block-group mq-faq">
<!-- wp:details --><details class="wp-block-details"><summary>Can I place an order yet?</summary><!-- wp:paragraph --><p>Orders are not open yet. We are preparing the collection and testing the store before launch.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
<!-- wp:details --><details class="wp-block-details"><summary>What will Maqwi offer?</summary><!-- wp:paragraph --><p>Our starting range includes honey, desi ghee, achaar and itar. Approved pack sizes and prices will be shown on their product pages once the catalogue is released.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
<!-- wp:details --><details class="wp-block-details"><summary>Where will you deliver?</summary><!-- wp:paragraph --><p>We plan to deliver across Pakistan with a flat Rs. 300 shipping fee per order. Ordering and delivery services will open after final testing.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
</div><!-- /wp:group --></div><!-- /wp:group -->
<!-- wp:group {"className":"mq-section mq-closing","layout":{"type":"default"}} --><div class="wp-block-group mq-section mq-closing"><!-- wp:paragraph {"className":"mq-eyebrow"} --><p class="mq-eyebrow">A thoughtful beginning</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Tradition has a place here.</h2><!-- /wp:heading --><!-- wp:paragraph --><p>We’re taking the time to prepare the details. Come back soon to meet the Maqwi collection.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
