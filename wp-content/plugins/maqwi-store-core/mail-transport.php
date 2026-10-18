<?php
/** Optional Resend SMTP transport using WordPress's bundled PHPMailer. */
defined( 'ABSPATH' ) || exit;

function maqwi_transactional_mail_configured() {
    return defined( 'MAQWI_RESEND_API_KEY' ) && is_string( MAQWI_RESEND_API_KEY ) && str_starts_with( MAQWI_RESEND_API_KEY, 're_' ) && strlen( MAQWI_RESEND_API_KEY ) > 15
        && defined( 'MAQWI_TRANSACTIONAL_FROM' ) && is_email( MAQWI_TRANSACTIONAL_FROM );
}

add_action( 'phpmailer_init', function ( $mailer ) {
    if ( ! maqwi_transactional_mail_configured() ) { return; }
    $mailer->isSMTP();
    $mailer->Host = 'smtp.resend.com';
    $mailer->Port = 587;
    $mailer->SMTPSecure = 'tls';
    $mailer->SMTPAutoTLS = true;
    $mailer->SMTPAuth = true;
    $mailer->Username = 'resend';
    $mailer->Password = MAQWI_RESEND_API_KEY;
    $mailer->SMTPDebug = 0;
    $mailer->Timeout = 15;
    // Certificate validation uses PHPMailer's secure defaults; never override SMTPOptions.
    $mailer->setFrom( MAQWI_TRANSACTIONAL_FROM, 'Maqwi Organics', false );
}, 20 );

add_filter( 'wp_mail_from', function ( $address ) { return maqwi_transactional_mail_configured() ? MAQWI_TRANSACTIONAL_FROM : $address; } );
add_filter( 'wp_mail_from_name', function ( $name ) { return maqwi_transactional_mail_configured() ? 'Maqwi Organics' : $name; } );

add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) || maqwi_transactional_mail_configured() ) { return; }
    echo '<div class="notice notice-warning"><p><strong>Maqwi email setup:</strong> Transactional delivery is not configured. Keep ordering closed until the sender is authenticated and administrator/customer inbox tests pass.</p></div>';
} );
