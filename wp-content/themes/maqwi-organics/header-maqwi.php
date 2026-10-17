<?php defined( 'ABSPATH' ) || exit; $brand_launch = maqwi_public_brand_mode(); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body id="top" <?php body_class( 'mq-site' ); ?>>
<?php wp_body_open(); ?>
<a class="mq-skip-link" href="#main">Skip to content</a>
<?php if ( get_option( 'maqwi_catalogue_release' ) ) : ?><div class="mq-announcement">Meet the Maqwi collection · orders open soon</div><?php elseif ( function_exists( 'maqwi_storefront_preview' ) && maqwi_storefront_preview() ) : ?><div class="mq-announcement">Meet the Maqwi collection · orders open soon</div><?php elseif ( $brand_launch ) : ?><div class="mq-announcement">Welcome to Maqwi. Meet the collection — orders open soon.</div><?php elseif ( 'yes' === get_option( 'woocommerce_coming_soon', 'no' ) ) : ?>
<div class="mq-announcement">Our store is taking shape. Orders open after launch.</div>
<?php endif; ?>
<header class="mq-header">
    <a class="mq-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Maqwi Organics home"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-mark.png' ); ?>" alt="" width="68" height="60"><span>MAQWI<small>ORGANICS</small></span></a>
    <nav class="mq-desktop-nav" aria-label="Main navigation"><?php wp_nav_menu( array( 'theme_location' => 'maqwi-primary', 'container' => false, 'menu_class' => 'mq-menu', 'fallback_cb' => 'maqwi_default_navigation', 'depth' => 1 ) ); ?></nav>
    <div class="mq-header-actions">
    <?php if ( ! $brand_launch && function_exists( 'wc_get_page_permalink' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?><a class="mq-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="My account" title="My account"><svg class="mq-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="3.5"/><path d="M5 20v-1.5a7 7 0 0 1 14 0V20"/></svg></a><?php endif; ?>
    <?php if ( ! $brand_launch && function_exists( 'wc_get_cart_url' ) ) : ?><a class="mq-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Shopping bag" title="Shopping bag"><svg class="mq-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5.5 7.5h13l1 13h-15l1-13Z"/><path d="M8.5 8V6a3.5 3.5 0 0 1 7 0v2"/></svg><?php echo maqwi_cart_count(); ?></a><?php endif; ?>
    <details class="mq-mobile-nav"><summary aria-controls="mq-mobile-navigation"><span class="mq-menu-label">Menu</span><span class="mq-close-label">Close</span><span class="mq-menu-glyph" aria-hidden="true"><span></span><span></span></span></summary><nav id="mq-mobile-navigation" aria-label="Mobile navigation"><?php wp_nav_menu( array( 'theme_location' => 'maqwi-primary', 'container' => false, 'menu_class' => 'mq-menu', 'fallback_cb' => 'maqwi_default_navigation', 'depth' => 1 ) ); ?><?php if ( ! $brand_launch && function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?><a class="mq-mobile-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">My account</a><?php endif; ?></nav></details>
    </div>
</header>
