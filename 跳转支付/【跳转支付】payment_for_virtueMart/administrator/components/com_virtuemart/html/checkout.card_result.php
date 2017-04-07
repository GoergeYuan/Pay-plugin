<?php 
/**
* checkout.card_result.php
* CreditCard Payment Order Confirmation Handler
*
* @version $Id: checkout.2Checkout_result.php 1394 2008-05-04 19:05:15Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
if(!defined('_VALID_MOS') && !defined('_JEXEC')){
    die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );   
}
if(!isset($_REQUEST['BillNo']) || empty($_REQUEST['BillNo'])){
    echo $VM_LANG->_('VM_CHECKOUT_ORDERIDNOTSET');
}else{
    require_once(CLASSPATH . 'payment/ps_card.cfg.php');
    $BillNo     = vmGet($_REQUEST, 'BillNo');
    $Currency   = vmGet($_REQUEST, 'Currency');
    $Amount     = vmGet($_REQUEST, 'Amount');
    $Succeed    = vmGet($_REQUEST, 'Succeed');
    $Result     = vmGet($_REQUEST, 'Result');
    $MD5info    = vmGet($_REQUEST, 'MD5info');
    $MD5key     = _CARD_SECRETWORD;
    $MD5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
    $MD5sign    = strtoupper(md5($MD5src));
    if($MD5info != $MD5sign){
?>
    <img src="<?php echo VM_THEMEURL; ?>images/button_cancle.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" border="0"/>
    <span class="message"><?php echo $Result. 'Error Code : '.$Succeed; ?></span>
<?php 
    }else{
        $data['order_id']       = (int)$BillNo;
        if($Succeed == '88'){
            $data['order_status']   = _CARD_PAY_SUCCESS_STATUS;
?>
            <img src="<?php echo VM_THEMEURL ?>images/button_ok.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_SUCCESS'); ?>" border="0" />
            <h2><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_TRANSACTION_SUCCESS') ?></h2>
<?php
        }elseif($Succeed == '19'){
            $data['order_status']   = _CARD_PAY_PROCESSING_STATUS;
?>
			<img src="<?php echo VM_THEMEURL ?>images/button_ok.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_SUCCESS'); ?>" border="0" />
            <h2><?php echo $Result ?></h2>
<?php 
        }elseif($Succeed == '0'){
            $data['order_status']   = _CARD_PAY_FAIL_STATUS;
?>
			<img src="<?php echo VM_THEMEURL ?>images/button_cancel.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" border="0" />
            <h2><?php echo $Result ?></h2>
<?php 
        }else{
            $data['order_status']   = _CARD_PAY_CARDDECLINED_STATUS;
?>
            <img src="<?php echo VM_THEMEURL ?>images/button_cancel.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" border="0" />
            <h2>Payment Result &nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $Result ?></h2>
<?php
        }
        require_once(CLASSPATH . 'ps_order.php');
        $psOrder        = new ps_order;
        $psOrder->order_status_update($data);
    }
?>
<p><a href="<?php @$sess->purl( SECUREURL."index.php?option=com_virtuemart&page=account.order_details&order_id=".$data['order_id'] ) ?>">
   <?php echo $VM_LANG->_('PHPSHOP_ORDER_LINK') ?></a>
</p>
<?php 
}
?>