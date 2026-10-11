<?php defined( 'ABSPATH' ) || exit; ?>
<footer class="mq-footer">
    <div class="mq-footer-inner"><div><p class="mq-footer-brand">Maqwi Organics</p><p>Honey, desi ghee, achaar and itar.<br>Traditions with a place in your everyday.</p></div><nav aria-label="Footer navigation"><a href="<?php echo esc_url( maqwi_shop_url() ); ?>">Shop</a><a href="<?php echo esc_url( home_url( '/#our-story' ) ); ?>">Our story</a><a href="<?php echo esc_url( home_url( '/#questions' ) ); ?>">Questions</a><?php if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">My account</a><?php endif; ?><?php if ( get_privacy_policy_url() ) : ?><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy</a><?php endif; ?><?php if ( function_exists( 'wc_get_page_permalink' ) && wc_get_page_id( 'terms' ) > 0 ) : ?><a href="<?php echo esc_url( wc_get_page_permalink( 'terms' ) ); ?>">Terms</a><?php endif; ?></nav></div>
    <div class="mq-footer-bottom"><span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Maqwi Organics</span><span>Made for everyday traditions.</span></div>
</footer>
<?php wp_footer(); ?>
</body></html>
