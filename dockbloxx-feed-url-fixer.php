<?php
/**
 * Plugin Name: DockBloxx Feed URL Fixer
 * Plugin URI:  https://dockbloxx.com/
 * Description: Rewrites WooCommerce Product Feed Manager product links from backend to frontend domain. By Tony Stark 
 * (The Moose).
 * Version:     1.0.0
 * Author:      The Moose
 * Author URI:  https://dockbloxx.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Filter product links in RexTheme Product Feed Manager XML to use the frontend domain.
 *
 * @param string $product_link The original product link in the feed.
 * @param WC_Product $product The WooCommerce product object.
 * @param array $feed_rules Feed config array.
 * @param array $product_information Additional product info.
 * @return string Modified product link.
 */

/*
 |------------------------------------------------------------------
 | 1)  Primary hook – fires when the rule itself is “link”
 |------------------------------------------------------------------
 */
add_filter( 'rex_feed_product_url', 'dbx_fix_feed_link', 10, 1 );

/*
 |------------------------------------------------------------------
 | 2)  Fallback / catch-all – fires for _every_ attribute.
 |     If the rule’s attr == link (or parent_url etc.) we rewrite.
 |------------------------------------------------------------------
 */
add_filter( 'rexfeed_product_attribute_raw_value', 'dbx_fix_feed_link_catchall', 10, 3 );

/**
 * Core replacer.
 *
 * @param  string $url
 * @return string
 */
function dbx_fix_feed_link( $url ) {
    $from = 'https://dbp.dockbloxx.com/';
    $to   = 'https://dockbloxx.com/';
    return str_replace( $from, $to, $url );
}


/**
 * Catch-all wrapper.  Only touch the product URL fields.
 *
 * @param mixed $val                Raw attribute value.
 * @param array $rule               Feed-rule array (has 'attr' key).
 * @param Rex_Product_Data_Retriever $ctx (unused, but handy if you ever
 *                                         need product/currency info)
 */
function dbx_fix_feed_link_catchall( $val, $rule, $ctx ) {

	if ( isset( $rule['attr'] ) && in_array( $rule['attr'], array( 'link', 'parent_url', 'review_url' ), true ) ) {
		$val = dbx_fix_feed_link( $val );
	}
	return $val;
}