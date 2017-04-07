<?php
/*
 * Created on 2009-12-3
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  define('MODULE_PAYMENT_CARD_TEXT_ADMIN_TITLE', 'CreditCard Payment Gateway(fashionpay)');
  define('MODULE_PAYMENT_CARD_TEXT_CATALOG_TITLE', 'CreditCard Payment Gateway(fashionpay)');
  define('MODULE_PAYMENT_CARD_TEXT_DESCRIPTION', 'CreditCard Payment Gateway(fashionpay)');

  define('MODULE_PAYMENT_CARD_MARK_BUTTON_IMG', HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/card/card.png');
  define('MODULE_PAYMENT_CARD_PAY_BUTTON_IMG', DIR_WS_MODULES . 'payment/card/card_submit_button.gif');
  define('MODULE_PAYMENT_CARD_PAY_BUTTON_ALT', 'Go to checkout with CreditCard');
  define('MODULE_PAYMENT_CARD_MARK_BUTTON_ALT', 'Checkout with CreditCard');
  define('MODULE_PAYMENT_CARD_ACCEPTANCE_MARK_TEXT', 'CreditCard Payment Gateway');

  define('MODULE_PAYMENT_CARD_TEXT_CATALOG_LOGO', '<img src="' . MODULE_PAYMENT_CARD_MARK_BUTTON_IMG . '" width="200" alt="' . MODULE_PAYMENT_CARD_MARK_BUTTON_ALT . '" title="' . MODULE_PAYMENT_CARD_MARK_BUTTON_ALT . '" />Support for the largest amount of $600' );

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_1_1', 'Enable CreditCard Module');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_1_2', 'Do you want to accept CreditCard payments?');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_2_1', 'CreditCard ID');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_2_2', 'CreditCard ID');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_3_1', 'CreditCard MD5 key');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_3_2', 'CreditCard MD5 key');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_4_1', 'Currency');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_4_2', 'Currency Type: 1-USD, 2-EUR,3-CNY,4-GBP');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_5_1', 'Language');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_5_2', 'Language: de-German,es-Spanish,fr-French,it-Italian,ja-Japanese,ko-Korean,en-English');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_6_1', 'Payment Zone');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_6_2', 'If a zone is selected, only enable this payment method for that zone.');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_7_1', 'Set Pending Notification Status');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_7_2', 'Set the status of orders made with this payment module to this value<br />(Processing recommended)');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_8_1', 'Sort order of display');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_8_2', 'Sort order of display. Lowest is displayed first.');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_9_1', 'CreditCard transaction URL<br />Default: <code>' . HTTP_SERVER . DIR_WS_CATALOG . 'submitOrder.php</code><br />');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_9_2', 'CreditCard transaction URL');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_10_1', 'CreditCard Return URL');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_10_2', 'CreditCard Return URL');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_11_1', 'Filished order status');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_11_2', '9Set your order status for order filished');

  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_12_1', 'Payment Log');
  define('MODULE_PAYMENT_CARD_TEXT_CONFIG_12_2', 'Record all payment return and error messages.');
 
?>
