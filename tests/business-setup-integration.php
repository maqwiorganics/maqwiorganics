<?php
/** Runs only inside the disposable CI WordPress database; no live URL or credentials. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit( 1 ); }
if ( DB_NAME !== 'maqwi_ci' || ! in_array( DB_HOST, array( '127.0.0.1:3306', '127.0.0.1' ), true ) ) { throw new RuntimeException( 'Refusing to run fixtures outside the isolated CI database.' ); }
$checks = 0;
global $wpdb;
foreach ( $wpdb->get_col( 'SHOW TABLES' ) as $table ) { if ( str_starts_with( $table, $wpdb->prefix ) && preg_match( '/^[a-zA-Z0-9_]+$/', $table ) ) { $wpdb->query( 'ALTER TABLE `' . $table . '` ENGINE=MyISAM' ); } }
function mq_assert( $value, $message ) { global $checks; if ( ! $value ) { throw new RuntimeException( $message ); } $checks++; }
function mq_rejects( $callback, $message ) { try { $callback(); } catch ( RuntimeException $error ) { mq_assert( true, $message ); return; } throw new RuntimeException( 'Expected refusal: ' . $message ); }
add_filter( 'pre_wp_mail', function () { return true; }, PHP_INT_MAX );
$fixture = json_decode( file_get_contents( getenv( 'MAQWI_FIXTURE' ) ), true, 512, JSON_THROW_ON_ERROR );
foreach ( $fixture['posts'] as $row ) {
    $id = (int) $row['ID'];unset( $row['ID'] );$row['import_id'] = $id;
    $saved = wp_insert_post( wp_slash( $row ), true );
    mq_assert( ! is_wp_error( $saved ) && $saved === $id, 'Fixture identity ' . $id );
    if ( $row['post_type'] === 'product' && $row['post_status'] === 'publish' ) { wp_set_object_terms( $id, 'variable', 'product_type' ); }
}
foreach ( $fixture['meta'] as $row ) { update_post_meta( (int) $row['post_id'], $row['meta_key'], maybe_unserialize( $row['meta_value'] ) ); }
foreach ( array( 'maqwi_brand_launch' => 'yes', 'maqwi_storefront_preview' => 'yes', 'woocommerce_currency' => 'PKR', 'woocommerce_calc_taxes' => 'no', 'woocommerce_manage_stock' => 'yes', 'woocommerce_hold_stock_minutes' => '60', 'woocommerce_default_country' => 'PK:PB', 'woocommerce_allowed_countries' => 'specific', 'woocommerce_specific_allowed_countries' => array( 'PK' ), 'woocommerce_ship_to_countries' => 'specific', 'woocommerce_specific_ship_to_countries' => array( 'PK' ) ) as $k => $v ) { update_option( $k, $v ); }
update_option( 'woocommerce_cod_settings', array( 'enabled' => 'yes', 'title' => 'Cash on delivery' ) );
update_option( 'woocommerce_bacs_settings', array( 'enabled' => 'yes', 'title' => 'Bank transfer' ) );
wc_delete_product_transients();
mq_assert( ! get_option( 'maqwi_business_setup_release' ), 'No implicit content migration' );
mq_assert( count( maqwi_business_setup_preflight() ) === 4, 'Exact live-shaped baseline accepted' );
wc_update_product_stock( 62, 1 );
mq_rejects( 'maqwi_business_setup_preflight', 'Changed stock must not be overwritten' );
wc_update_product_stock( 62, 0 );
mq_assert( maqwi_apply_approved_business_setup(), 'Approved configuration applied' );
mq_assert( MAQWI_BUSINESS_RELEASE === get_option( 'maqwi_business_setup_release' ), 'One-time release marker persisted' );
foreach ( array( 62 => 20, 63 => 15, 64 => 10, 32 => 5 ) as $id => $quantity ) { mq_assert( (int) wc_get_product( $id )->get_stock_quantity() === $quantity, 'Correct opening stock: ' . $id ); }
$achaar = wc_get_product( 32 );$children = array_filter( $achaar->get_children(), function ( $id ) { return wc_get_product( $id )->get_status() === 'publish'; } );
mq_assert( count( $children ) === 2, 'Exactly two active Achaar variants' );
$variants = array();foreach ( $children as $id ) { $v = wc_get_product( $id );$variants[ $v->get_attribute( 'size' ) ] = $v;mq_assert( ! $v->get_manage_stock( 'edit' ) && $v->get_stock_managed_by_id() === 32, 'Achaar shares parent stock' );mq_assert( $v->get_backorders() === 'no', 'Backorders disabled' ); }
mq_assert( (float) $variants['500 g']->get_price() === 1500.0 && (float) $variants['1 kg']->get_price() === 3000.0, 'Achaar approved prices' );
foreach ( array( 27, 28, 30, 31, 33, 34, 36, 37 ) as $id ) { mq_assert( get_post_status( $id ) === 'private', 'Legacy variation preserved: ' . $id ); }
foreach ( array( 'cod', 'bacs', 'cheque' ) as $gateway ) { mq_assert( 'no' === get_option( 'woocommerce_' . $gateway . '_settings' )['enabled'], 'Gateway stays disabled: ' . $gateway ); }
mq_assert( apply_filters( 'woocommerce_available_payment_gateways', array( 'fake' => new stdClass() ) ) === array(), 'Public gateway guard remains active' );
mq_assert( ! wc_get_product( 62 )->is_purchasable(), 'Public product purchase remains blocked' );
$request = new WP_REST_Request( 'POST', '/wc/store/v1/checkout' );$blocked = apply_filters( 'rest_pre_dispatch', null, rest_get_server(), $request );
mq_assert( is_wp_error( $blocked ) && $blocked->get_error_code() === 'maqwi_orders_closed', 'Store API order creation blocked' );
$errors = new WP_Error();do_action( 'woocommerce_after_checkout_validation', array( 'billing_phone' => '03001234567', 'billing_country' => 'PK' ), $errors );
mq_assert( in_array( 'maqwi_orders_closed', $errors->get_error_codes(), true ), 'Classic checkout guard remains active' );
try { do_action( 'woocommerce_checkout_create_order', new WC_Order(), array() ); throw new RuntimeException( 'Checkout order creation unexpectedly permitted' ); }
catch ( Exception $error ) { mq_assert( $error->getMessage() === 'Ordering is not available yet.', 'Checkout create-order guard' ); }
wc_load_cart();
$package = array( 'contents' => array( 'h' => array( 'data' => wc_get_product( 62 ), 'quantity' => 2, 'line_total' => 9000, 'line_subtotal' => 9000 ) ), 'contents_cost' => 9000, 'applied_coupons' => array(), 'user' => array( 'ID' => 0 ), 'destination' => array( 'country' => 'PK', 'state' => 'PB', 'postcode' => '', 'city' => 'Lahore', 'address' => '', 'address_1' => '', 'address_2' => '' ) );
$zone = WC_Shipping_Zones::get_zone_matching_package( $package );$methods = $zone->get_shipping_methods( true );
mq_assert( count( $methods ) === 1, 'One native Pakistan shipping method' );$method = reset( $methods );$rates = apply_filters( 'woocommerce_package_rates', $method->get_rates_for_package( $package ), $package );
mq_assert( count( $rates ) === 1 && (float) reset( $rates )->get_cost() === 300.0, 'One PKR 300 delivery charge for multi-item order' );
$package['destination']['country'] = 'GB';$outside = WC_Shipping_Zones::get_zone_matching_package( $package );
mq_assert( count( $outside->get_shipping_methods( true ) ) === 0, 'No non-Pakistan shipping method' );
$order = wc_create_order();$order->add_product( $variants['500 g'], 1 );$order->add_product( $variants['1 kg'], 1 );$shipping = new WC_Order_Item_Shipping();$shipping->set_method_title( 'Nationwide delivery' );$shipping->set_method_id( 'flat_rate' );$shipping->set_total( 300 );$order->add_item( $shipping );$order->calculate_totals();$order->save();
mq_assert( (float) $order->get_total() === 4800.0, 'Mixed Achaar order total includes shipping once' );
$order->update_status( 'on-hold' );
mq_assert( (int) wc_get_product( 32 )->get_stock_quantity() === 3, 'Both sizes consume the same parent stock' );
wc_reduce_stock_levels( $order->get_id() );mq_assert( (int) wc_get_product( 32 )->get_stock_quantity() === 3, 'Repeated stock reduction is idempotent' );
$order->update_status( 'cancelled' );mq_assert( (int) wc_get_product( 32 )->get_stock_quantity() === 5, 'Cancellation restores shared stock' );
wc_update_product_stock( 62, 19 );mq_assert( ! maqwi_apply_approved_business_setup(), 'Completed release is not replayed' );mq_assert( (int) wc_get_product( 62 )->get_stock_quantity() === 19, 'Later sold stock is never reset to opening quantity' );
foreach ( maqwi_approved_business_pages() as $slug => $page ) { mq_assert( str_contains( get_post( $page[0] )->post_content, $page[2] ), 'Approved page content: ' . $slug );mq_assert( count( wp_get_post_revisions( $page[0] ) ) > 0, 'Previous page content recoverable: ' . $slug ); }
$reservations = array();
foreach ( array( $variants['500 g'], $variants['1 kg'] ) as $variant ) {
    $pending = wc_create_order();$pending->add_product( $variant, 3 );$pending->update_meta_data( '_maqwi_ci_reservation', 'yes' );$pending->calculate_totals();$pending->save();$reservations[] = $pending;
}
$workers = array();
foreach ( $reservations as $pending ) {
    $pipes = array();$process = proc_open( array( 'php', '/tmp/wp.phar', '--allow-root', '--path=/tmp/maqwi-ci', 'eval-file', getenv( 'MAQWI_RESERVATION_WORKER' ), (string) $pending->get_id() ), array( 0 => array( 'pipe', 'r' ), 1 => array( 'pipe', 'w' ), 2 => array( 'pipe', 'w' ) ), $pipes );
    mq_assert( is_resource( $process ), 'Concurrent reservation worker started' );fclose( $pipes[0] );$workers[] = array( $process, $pipes );
}
$results = array();
foreach ( $workers as $worker ) {
    $result = trim( stream_get_contents( $worker[1][1] ) );$stderr = stream_get_contents( $worker[1][2] );fclose( $worker[1][1] );fclose( $worker[1][2] );$exit = proc_close( $worker[0] );mq_assert( $exit === 0, 'Reservation worker completed: ' . $stderr );$results[] = $result;
}
sort( $results );mq_assert( $results === array( 'insufficient', 'reserved' ), 'Concurrent mixed-size orders cannot reserve six units from shared stock five' );
mq_assert( (int) wc_get_held_stock_quantity( $variants['500 g'] ) === 3, 'Reservation uses shared parent stock counter' );
foreach ( $reservations as $pending ) { wc_release_stock_for_order( $pending ); }
mq_assert( (int) wc_get_held_stock_quantity( $variants['500 g'] ) === 0, 'Reservations released correctly' );
mq_assert( maqwi_brand_launch_mode(), 'Ordering remains closed after all tests' );
echo 'MAQWI_COMMERCE_CHECKS=' . $checks . "\n";
