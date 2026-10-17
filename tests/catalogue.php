<?php
/** Verify the release cannot silently reintroduce inferred commercial offers. */
define( 'ABSPATH', __DIR__ );
function add_action( ...$args ) {}
function add_filter( ...$args ) {}
require __DIR__ . '/../wp-content/plugins/maqwi-store-core/catalogue-release.php';
$items = maqwi_launch_catalogue();
$approved = array( 'maqwi-honey' => array( '1 kg', '4500' ), 'maqwi-desi-ghee' => array( '1 kg', '3500' ), 'maqwi-itar' => array( '12 ml', '200' ) );
foreach ( $approved as $slug => $offer ) {
    if ( array( $items[ $slug ]['size'], $items[ $slug ]['price'] ) !== $offer ) { throw new Exception( 'Offer differs from owner approval.' ); }
}
if ( '' !== $items['maqwi-achaar']['price'] || '' !== $items['maqwi-achaar']['size'] ) { throw new Exception( 'Unapproved achaar offer.' ); }
if ( ! str_contains( $items['maqwi-desi-ghee']['photo_note'], 'not interchangeable' ) ) { throw new Exception( 'Ghee volume/weight ambiguity.' ); }
foreach ( $items as $item ) {
    if ( preg_match( '/100%|organic certified|alcohol.free|sugar.free|raw honey|pure honey/i', $item['description'] ) ) { throw new Exception( 'Unverified claim in copy.' ); }
}
echo "Owner-approved offers, missing-price handling and pack-size disclosure passed.\n";

require __DIR__ . '/../wp-content/plugins/maqwi-store-core/storefront-copy.php';
foreach ( maqwi_customer_pages() as $page ) {
    if ( preg_match( '/\\bAI\\b|sample|prototype|preview|for review/i', implode( ' ', $page ) ) ) { throw new Exception( 'Internal wording in customer page.' ); }
}
echo "Customer information pages contain no internal project wording.\n";
