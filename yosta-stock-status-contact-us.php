<?php
/*
Plugin Name: Yosta stock status contact us
Description: Add Woo Custom Stock Status contact us
Version: 0.1
Author: Joost Abrahams
Author URI: https://mantablog.nl
GitHub Plugin URI: https://github.com/joost-abrahams/yosta-stock-status-contact-us/
Source  URI: https://rudrastyh.com/woocommerce/add-id-column-to-payment-methods-table.html
License: GPLv3
Requires Plugins: woocommerce
*/

// Exit if accessed directly
defined( 'ABSPATH' ) or die;

//declare complianz with consent level API
$plugin = plugin_basename( __FILE__ );
add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );

// Happy hacking

//Add a Custom Order Status into Product Settings
add_filter( 'woocommerce_product_stock_status_options', 'rudr_product_statuses' );

function rudr_product_statuses( $product_statuses ){

	// let's add our custom product status in a format slug => name
	$product_statuses[ 'contact-us' ] = 'Contact us';
	// you can also remove some of the default product stock statuses by the way

	// don't forget to return the changed array of statuses
	return $product_statuses;
}

//Make Products with a Custom Product Status Non-purchasable
add_filter( 'woocommerce_product_is_in_stock', 'rudr_allow_purchase', 10, 2 );
//add_filter( 'woocommerce_is_purchasable', 'rudr_allow_purchase', 10, 2 );

function rudr_allow_purchase( $allow, $product ) {
	
	if( 'contact-us' === $product->get_stock_status() ) {
		$allow = false;
	}
	
	return $allow;
}

//Display a Custom Message on the Product Page
add_filter( 'woocommerce_get_availability_text', 'rudr_product_stock_status_text', 10, 2 );

function rudr_product_stock_status_text( $text, $product ) {

	if( 'contact-us' === $product->get_stock_status() ) {
		$text = sprintf(
			'This product is only available on request, please <a href="%s">contact us</a>',
			site_url( 'contact' )
		);
	}

	return $text;

}

//Change “Add to cart” Buttons on the “Shop” Page
add_filter( 'woocommerce_product_add_to_cart_text', function( $text, $product ) {

	if( 'contact-us' === $product->get_stock_status() ) {
		$text = 'Contact us';
	}

	return $text;

}, 25, 2 );

add_filter( 'woocommerce_product_add_to_cart_url', function( $url, $product ) {

	if( 'contact-us' === $product->get_stock_status() ) {
		$url = site_url( 'contact' );
	}

	return $url;

}, 25, 2 );

//Admin Product List Table
add_filter( 'woocommerce_admin_stock_html', function( $stock_html, $product ) {

	if( 'contact-us' === $product->get_stock_status() ) {
		$stock_html = '<mark class="onbackorder">On request</mark>';
	}

	return $stock_html;

}, 25, 2 );
  
