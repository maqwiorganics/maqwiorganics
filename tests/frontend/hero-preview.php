<?php
/** Isolated rendering of the actual hero function; no WordPress or commerce writes. */
define('ABSPATH', __DIR__);
function get_stylesheet_directory_uri() { return '/wp-content/themes/maqwi-organics'; }
function esc_url($v) { return htmlspecialchars($v, ENT_QUOTES); }
function esc_attr($v) { return htmlspecialchars($v, ENT_QUOTES); }
function wp_json_encode($v) { return json_encode($v); }
function home_url($v) { return $v; }
function maqwi_shop_url() { return '#the-collection'; }
$source=file_get_contents(__DIR__.'/../../wp-content/themes/maqwi-organics/functions.php');
$end=strpos($source,"add_filter( 'render_block_core/group'");
eval(substr($source,5,$end-5));
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="/wp-content/themes/maqwi-organics/style.css"></head><body class="mq-site"><header style="height:102px;padding:28px 22px;color:#2f5d3a;border-bottom:1px solid #ddd">MAQWI ORGANICS — layout preview</header><main class="mq-home"><?php echo maqwi_premium_hero(); ?><section id="the-collection">Explore the collection</section></main><script src="/wp-content/themes/maqwi-organics/assets/public.js"></script></body></html>
