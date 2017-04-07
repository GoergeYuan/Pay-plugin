<?php 
/**
* card_notify.php
*
* @version $Id: notify.php 1364 2008-04-09 16:44:28Z soeren_nb $
* @package VirtueMart
* @subpackage core
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
$messages = Array();
function debug_msg( $msg ) {
    global $messages;
    if( PAYPAL_DEBUG == "1" ) {
        if( !defined( "_DEBUG_HEADER")  ) {
            echo "<h2>CreditCard_Notify.php Debug OUTPUT</h2>";
            define( "_DEBUG_HEADER", "1" );
        }
        $messages[] = "<pre>$msg</pre>";
        echo end( $messages );
    }
}	
if ($_POST) {
	header("HTTP/1.0 200 OK");
    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,$mosConfig_mailfrom, $mosConfig_fromname;
        /*** access Joomla's configuration file ***/
        $my_path = dirname(__FILE__);
        if( file_exists($my_path."/../../../configuration.php")) {
            $absolute_path = dirname( $my_path."/../../../configuration.php" );
            require_once($my_path."/../../../configuration.php");
        }
        elseif( file_exists($my_path."/../../configuration.php")){
            $absolute_path = dirname( $my_path."/../../configuration.php" );
            require_once($my_path."/../../configuration.php");
        }
        elseif( file_exists($my_path."/configuration.php")){
            $absolute_path = dirname( $my_path."/configuration.php" );
            require_once( $my_path."/configuration.php" );
        }
        else {
            die( "Joomla Configuration File not found!" );
        }       
        $absolute_path = realpath( $absolute_path );        
        // Set up the appropriate CMS framework
        if( class_exists( 'jconfig' ) ) {
			define( '_JEXEC', 1 );
			define( 'JPATH_BASE', $absolute_path );
			define( 'DS', DIRECTORY_SEPARATOR );			
			// Load the framework
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
			// create the mainframe object
			$mainframe = & JFactory::getApplication( 'site' );			
			// Initialize the framework
			$mainframe->initialise();			
			// load system plugin group
			JPluginHelper::importPlugin( 'system' );			
			// trigger the onBeforeStart events
			$mainframe->triggerEvent( 'onBeforeStart' );
			$lang =& JFactory::getLanguage();
			$mosConfig_lang = $GLOBALS['mosConfig_lang']          = strtolower( $lang->getBackwardLang() );
			// Adjust the live site path
			$mosConfig_live_site = str_replace('/administrator/components/com_virtuemart', '', JURI::base());
			$mosConfig_absolute_path = JPATH_BASE;
        } else {
        	define('_VALID_MOS', '1');
        	require_once($mosConfig_absolute_path. '/includes/joomla.php');
        	require_once($mosConfig_absolute_path. '/includes/database.php');
        	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
        	$mainframe = new mosMainFrame($database, 'com_virtuemart', $mosConfig_absolute_path );
        }
        // load Joomla Language File
        if (file_exists( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' )) {
            require_once( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' );
        }
        elseif (file_exists( $mosConfig_absolute_path. '/language/english.php' )) {
            require_once( $mosConfig_absolute_path. '/language/english.php' );
        }
    /*** END of Joomla config ***/     
    /*** VirtueMart part ***/        
        require_once($mosConfig_absolute_path.'/administrator/components/com_virtuemart/virtuemart.cfg.php');
        include_once( ADMINPATH.'/compat.joomla1.5.php' );
        require_once( ADMINPATH. 'global.php' );
        require_once( CLASSPATH. 'ps_main.php' );
        
        /* @MWM1: Logging enhancements (file logging & composite logger). */
        $vmLogIdentifier = "card_notify.php";
        require_once(CLASSPATH."Log/LogInit.php");
              
        /* Load the card Configuration File */ 
        require_once( CLASSPATH. 'payment/ps_card.cfg.php' );
        
		if( _CARD_TESTMODE == "Y" ) {
			$debug_email_address = '1093184107@qq.com';
		}
		else {
			$debug_email_address = _CARD_EMAIL;
		}
	    // restart session
	    // Constructor initializes the session!
	    $sess = new ps_session();  
    
   
    //$payment_status = trim(stripslashes($_POST['payment_status']));
    
    // 支付平台流水号
	$TradeNo=$_POST["rorderno"];// 供商户在支付平台查询订单时使用,请合理保存
	// 订单号
	$BillNo = $_POST["BillNo"];	
	// 币种
	$Currency = $_POST["Currency"];
	// 订单金额
	$Amount = $_POST["Amount"];
	// 支付结果
	$Result = $_POST["Result"];// 交易结果: 0 : 失败；1 : 成功
	
    $Succeed    = $_POST['Succeed'];
	// 取得的MD5校验信息
	$MD5info = $_POST["MD5info"]; 
	
	// MD5私钥
	$MD5key = _CARD_SECRETWORD;
	// $MD5key = "12345678";//从支付平台获取
	// 校验源字符串
	$md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
    //$md5src = $TradeNo.$BillNo.$Currency.$Amount.$PaymentResult.$MD5key;
	// MD5检验结果
	$md5sign = strtoupper(md5($md5src));
     
      // Get the Order Details from the database      
      $qv = "SELECT `order_id`, `order_number`, `user_id`, `order_subtotal`,`order_total`, `order_currency`, `order_tax`, `order_shipping_tax`, `coupon_discount`, `order_discount` FROM `#__{vm}_orders`  WHERE `order_id`='".(int)$BillNo."'";
      $db = new ps_DB;
      $db->query($qv);
      $db->next_record();
      $order_id = $db->f("order_id");
     
      $d['order_id'] = $order_id;
      $d['notify_customer'] = "Y";

      if($MD5info==$md5sign){
      // UPDATE THE ORDER STATUS to 'Completed'
                /* if(eregi ("1", $PaymentResult)) { */
                if($Succeed == '88'){
                    $d['order_status'] = _CARD_PAY_SUCCESS_STATUS;
					require_once ( CLASSPATH . 'ps_order.php' );
	                $ps_order= new ps_order;
	                $ps_order->order_status_update($d);
	                $mailsubject = "CreditCard IPN txn on your site";
	                $mailbody = "Hello,\n\n";
	                $mailbody .= "a CreditCard transaction for you has been made on your website!\n";
	                $mailbody .= "-----------------------------------------------------------\n";
	                $mailbody .= "Transaction ID: $TradeNo\n";
	                $mailbody .= "Order ID: $BillNo\n";
	                $mailbody .= "Payment Status: {$Result}\n";
	                vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
                }
				/* elseif(eregi ("2", $PaymentResult)) { */
                elseif($Succeed == '19'){
                    $d['order_status'] = _CARD_PAY_PROCESSING_STATUS;
					require_once ( CLASSPATH . 'ps_order.php' );
	                $ps_order= new ps_order;
	                $ps_order->order_status_update($d);
                }
                // UPDATE THE ORDER STATUS to 'Pending'
                /* elseif(eregi ("0", $PaymentResult)) { */
                else{
                    $d['order_status'] = _CARD_PAY_FAIL_STATUS;
					require_once ( CLASSPATH . 'ps_order.php' );
	                $ps_order= new ps_order;
	                $ps_order->order_status_update($d);
                }                
        }
		else{
			
		}
    
}
?>
