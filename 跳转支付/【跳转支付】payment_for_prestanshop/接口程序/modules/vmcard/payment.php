<?php 
    $useSSL     = true;
    include_once(dirname(__FILE__) . '/../../config/config.inc.php');
    include_once(dirname(__FILE__) . '/../../header.php');
    include_once(_PS_CLASS_DIR_ . 'Cart.php');
    include_once(dirname(__FILE__) . '/vmcard.php');
    
    /* session_start(); */
    //$cart       = unserialize($_SESSION['sht_cart']);
    if(empty($cart->id)){
        Tools::redirect('history.php');
    }
    $sht        = new vmcard();
    echo $sht->commitPayment($cart);
	include_once(dirname(__FILE__) . '/../../footer.php');
?>