<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || DB_NAME !== 'maqwi_ci' || ! in_array( DB_HOST, array( '127.0.0.1:3306', '127.0.0.1' ), true ) ) { exit( 2 ); }
$order = wc_get_order( (int) ( $args[0] ?? 0 ) );
if ( ! $order || 'yes' !== $order->get_meta( '_maqwi_ci_reservation' ) ) { exit( 3 ); }
try { wc_reserve_stock_for_order( $order ); echo "reserved\n"; }
catch ( \Automattic\WooCommerce\Checkout\Helpers\ReserveStockException $error ) { echo "insufficient\n"; }
