<?php
/*
 * Plugin Name: WooCommerce Fashionpay Gateway
 * Plugin URI: http://www.fashionpay.com
 * Description: Integrate the Chinese FirstTeam payment gateway with Woocommerce. FirstTeam is one of the most widely used payment method in China.
 * Version: 2.0.4
 * Author: CodingPet
 * Author URI: http://www.fashionpay.com
 * Requires at least: 3.3
 * Tested up to: 3.5.1
 *
 * Text Domain: FirstTeam
 * Domain Path: /lang/
 */
if( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) { die('You are not allowed to call this page directly.'); }

add_action( 'plugins_loaded', 'FirstTeam_gateway_init');
function FirstTeam_gateway_init() {
    if( !class_exists('WC_Payment_Gateway') ) 
        return;
    load_plugin_textdomain( 'FirstTeam', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/'  );
    require_once( plugin_basename( 'class-wc-FirstTeam.php' ) );
    add_filter('woocommerce_payment_gateways', 'woocommerce_FirstTeam_add_gateway' );
}
 /**
 * Add the gateway to WooCommerce
 *
 * @access public
 * @param array $methods
 * @package		WooCommerce/Classes/Payment
 * @return array
 */
function woocommerce_FirstTeam_add_gateway( $methods ) {
    $methods[] = 'WC_FirstTeam';
    return $methods;
}
?>