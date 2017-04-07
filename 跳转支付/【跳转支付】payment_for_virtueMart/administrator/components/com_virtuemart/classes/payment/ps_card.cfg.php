<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
//ps_card.cfg.php
define ('_CARD_LOGIN', '10003');
define ('_CARD_SECRETWORD', '12345678');
define ('_CARD_MONEYTYPE', '1');
define ('_CARD_LANGUAGE', 'en');
define ('_CARD_MERWEBSITE', 'http://virtuemart.com');
define ('_CARD_REMARK', 'http://virtuemart.com');
define ('_CARD_SUBMIT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/submitOrder.php');
define ('_CARD_PAY_SUCCESS_STATUS', 'B');
define ('_CARD_PAY_FAIL_STATUS', 'F');
define ('_CARD_PAY_PROCESSING_STATUS', 'H');
define ('_CARD_PAY_CARDDECLINED_STATUS', 'D');
define ('_CARD_TESTMODE', 'Y');
define ('_CARD_MERCHANT_EMAIL', 'True');
define ('_CARD_EMAIL', '1097184107@qq.com');
?>