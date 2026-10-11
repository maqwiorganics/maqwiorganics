<?php
/** Pure rules: no WordPress database, credentials, customers or email. */
define( 'ABSPATH', __DIR__ );
$filters = array();
$brand_mode = 'no';
$preview_mode = 'no';
function get_option( $key, $default = false ) { global $brand_mode, $preview_mode; if ( 'maqwi_storefront_preview' === $key ) { return $preview_mode; } return 'maqwi_brand_launch' === $key && null !== $brand_mode ? $brand_mode : $default; }
function add_action( ...$args ) {}
function add_filter( $hook, $callback, ...$args ) { global $filters; $filters[ $hook ] = $callback; }
require __DIR__ . '/../wp-content/plugins/maqwi-store-core/maqwi-store-core.php';
function check( $condition, $label ) { if ( ! $condition ) { throw new RuntimeException( $label ); } }
foreach ( array( '03001234567', '+923001234567', '0300 123-4567', '(+92) 300 1234567' ) as $number ) {
    check( '+923001234567' === maqwi_normalize_mobile( $number ), 'Valid mobile: ' . $number );
}
foreach ( array( '', '0300123456', '030012345678', '+92300123456', '0421234567', '+443001234567', 'abc03001234567', '03001234567<script>', '٠٣٠٠١٢٣٤٥٦٧' ) as $number ) {
    check( '' === maqwi_normalize_mobile( $number ), 'Invalid mobile accepted' );
}
class TestErrors {
    public $codes = array();
    function add( $code, $message ) { $this->codes[] = $code; }
}
$errors = new TestErrors();
maqwi_validate_checkout( array( 'billing_phone' => '03001234567', 'billing_country' => 'PK' ), $errors );
check( array() === $errors->codes, 'Valid Pakistan checkout rejected' );
$errors = new TestErrors();
maqwi_validate_checkout( array( 'billing_phone' => 'not-a-number', 'billing_country' => 'US', 'ship_to_different_address' => 1, 'shipping_country' => 'PK' ), $errors );
check( array( 'maqwi_mobile', 'maqwi_country' ) === $errors->codes, 'Invalid checkout not rejected' );
$errors = new TestErrors();
maqwi_validate_checkout( array( 'billing_phone' => '03001234567', 'billing_country' => 'PK', 'ship_to_different_address' => 1, 'shipping_country' => 'US' ), $errors );
check( array( 'maqwi_country' ) === $errors->codes, 'Foreign shipping accepted' );
check( 'on-hold' === $filters['woocommerce_cod_process_payment_order_status'](), 'COD must await confirmation' );
echo "Pakistan mobile, country and COD confirmation rules passed.\n";

$brand_mode = 'yes';
check( false === maqwi_brand_purchasable( true ), 'Brand launch allowed a purchase' );
check( array() === $filters['woocommerce_available_payment_gateways']( array( 'cod' => 'gateway' ) ), 'Brand launch exposed payment' );
$brand_mode = 'no';
check( true === maqwi_brand_purchasable( true ), 'Store mode lost purchasability' );
check( false === maqwi_brand_purchasable( false ), 'Store mode bypassed original product restriction' );
echo "Brand launch purchase and gateway guards passed.\n";

$brand_mode = null;
check( false === maqwi_brand_purchasable( true ), 'Missing setting must fail closed' );

$brand_mode = 'yes';
$preview_mode = 'yes';
class SampleProduct {
    function __construct( public $sample ) {}
    function get_parent_id() { return 0; }
    function get_meta( $key ) { return $this->sample; }
}
check( true === maqwi_brand_purchasable( true, new SampleProduct( 'yes' ) ), 'Sample bag interaction blocked' );
check( false === maqwi_brand_purchasable( true, new SampleProduct( 'no' ) ), 'Preview allowed a real product' );
check( false === maqwi_brand_purchasable( false, new SampleProduct( 'yes' ) ), 'Preview bypassed native restriction' );
check( array() === $filters['woocommerce_available_payment_gateways']( array( 'cod' => 'gateway' ) ), 'Preview exposed payment' );
check( 'no' === $filters['option_woocommerce_enable_myaccount_registration']( 'yes' ), 'Preview enabled registration' );
class WP_Error { function __construct( public $code, public $message, public $data ) {} }
class SampleRequest {
    function __construct( public $method, public $route ) {}
    function get_method() { return $this->method; }
    function get_route() { return $this->route; }
}
$r = $filters['rest_pre_dispatch']( null, null, new SampleRequest( 'POST', '/wc/store/v1/checkout' ) );
check( $r instanceof WP_Error && 403 === $r->data['status'], 'Store API order submission not blocked' );
$r = $filters['rest_pre_dispatch']( null, null, new SampleRequest( 'GET', '/wc/store/v1/checkout' ) );
check( $r instanceof WP_Error && 403 === $r->data['status'], 'Store API draft checkout not blocked' );
check( null === $filters['rest_pre_dispatch']( null, null, new SampleRequest( 'GET', '/wc/store/v1/cart' ) ), 'Cart read blocked' );
check( null === $filters['rest_pre_dispatch']( null, null, new SampleRequest( 'POST', '/wc/store/v1/cart/add-item' ) ), 'Sample cart API blocked' );
echo "Sample-only cart, payment, registration and Store API guards passed.\n";

check( true === $filters['wc_shipping_enabled']( false ), 'Preview shipping disabled' );
check( true === $filters['woocommerce_cart_ready_to_calc_shipping']( false ), 'Preview delivery not calculable' );
$preview_mode = 'no';
check( false === $filters['wc_shipping_enabled']( false ), 'Real shipping setting changed' );
check( false === $filters['woocommerce_cart_ready_to_calc_shipping']( false ), 'Real shipping address requirement changed' );
echo "Sample delivery configuration isolated from real shipping settings.\n";
