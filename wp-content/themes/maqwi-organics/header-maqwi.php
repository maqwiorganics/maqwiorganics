<?php defined( 'ABSPATH' ) || exit; $brand_launch = maqwi_public_brand_mode(); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body id="top" <?php body_class( 'mq-site' ); ?>>
<?php wp_body_open(); ?>
<a class="mq-skip-link" href="#main">Skip to content</a>
<?php if ( function_exists( 'maqwi_storefront_preview' ) && maqwi_storefront_preview() ) : ?><div class="mq-announcement">Store preview · sample prices and products · orders are closed</div><?php elseif ( $brand_launch ) : ?><div class="mq-announcement">Welcome to Maqwi. Meet the collection — orders open soon.</div><?php elseif ( 'yes' === get_option( 'woocommerce_coming_soon', 'no' ) ) : ?>
<div class="mq-announcement">Our store is taking shape. Orders open after launch.</div>
<?php endif; ?>
<header class="mq-header">
    <a class="mq-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Maqwi Organics home"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-mark.png' ); ?>" alt="" width="68" height="60"><span>MAQWI<small>ORGANICS</small></span></a>
    <nav class="mq-desktop-nav" aria-label="Main navigation"><?php wp_nav_menu( array( 'theme_location' => 'maqwi-primary', 'container' => false, 'menu_class' => 'mq-menu', 'fallback_cb' => 'maqwi_default_navigation', 'depth' => 1 ) ); ?></nav>
    <div class="mq-header-actions">
    <?php if ( ! $brand_launch && function_exists( 'wc_get_page_permalink' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?><a class="mq-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Account</a><?php endif; ?>
    <?php if ( ! $brand_launch && function_exists( 'wc_get_cart_url' ) ) : ?><a class="mq-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">Bag <?php echo maqwi_cart_count(); ?></a><?php endif; ?>
    <details class="mq-mobile-nav"><summary>Menu</summary><nav aria-label="Mobile navigation"><?php wp_nav_menu( array( 'theme_location' => 'maqwi-primary', 'container' => false, 'menu_class' => 'mq-menu', 'fallback_cb' => 'maqwi_default_navigation', 'depth' => 1 ) ); ?><?php if ( ! $brand_launch && function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?><a class="mq-mobile-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">My account</a><?php endif; ?></nav></details>
    </div>
</header>
