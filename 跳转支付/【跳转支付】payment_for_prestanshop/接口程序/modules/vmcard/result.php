<?php
	$useSSL = true;
	include(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../header.php');
	//include(dirname(__FILE__).'/SHT.php');
	//error_reporting(E_ALL);
/* 	if (!$cookie->isLogged())
		Tools::redirect('authentication.php?back=order.php'); */
	//获得接口返回数据
    if(!isset($_GET['result']) && base64_decode($_GET['result']) === false){
        $url        = 'http://'. $_SERVER['HTTP_HOST'];
        @header("Location: {$url}");
    }
 
    parse_str(base64_decode($_GET['result']),$myArr);
	$BillNo 		= $myArr['BillNo'];
	$Currency 		= $myArr['Currency'];
	$CurrencyName	= $myArr['CurrencyName'];
	$Amount 		= $myArr['Amount'];
	$Succeed 		= $myArr['Succeed'];
	$Result 		= $myArr['Result'];
	$MD5info 		= $myArr['MD5info'];
	$MD5key 		= Configuration :: get('MODULE_PAYMENT_VMCARD_MD5KEY');
	$md5src 		= $BillNo . $Currency . $Amount . $Succeed . $MD5key;
	$md5sign 		= strtoupper(md5($md5src));
	$description    = isset($_GET['description']) ? trim($_GET['description']) : 'unkown case';

	$message='';
	$flag = false;

	//基本验证
	if ($MD5info == $md5sign) {
		$flag = true;
		if($Succeed =='88'){
			$Result = '<font color="green">' . $Result . "</font>";
			$sht_order_status = Configuration :: get('VMCARD_SUCCESS_ORDER_STATUS');

		} elseif($Succeed == '19'){
            $Result = '<font color="green">' . $Result .'</font>';
            $sht_order_status = Configuration :: get('VMCARD_PROCESSING_ORDER_STATUS');
        }else {
			$Result='<font color="red">'.$Result."</font>";
			$sht_order_status = Configuration :: get('VMCARD_FAILED_ORDER_STATUS');
		}
		$message='<h3>'.$Result.'</h3>Your Order Number : '.$BillNo.'<br/><br/>Amount : '.$Amount.' '.$CurrencyName.'<br/><br/>Payment Result : <font color="red">'.$description."</font>&nbsp;&nbsp;&nbsp;&nbsp;Response Code : &nbsp;&nbsp;&nbsp;&nbsp;".$Succeed;

	} else {
		$message='<h3>Checkout Pay Result</h3><font color="red">Pay result:verification failed.</font>';
		$sht_order_status = Configuration :: get('VMCARD_FAILED_ORDER_STATUS');
	}
	echo $message;
	

/* 	if($BillNo && $flag === true){
		$version    = trim(str_replace('.', '', _PS_VERSION_));
		$verInt		= intval(substr($version, 0, 3));
		$order_id 			= $myArr['BillNo'];
		$order 				= new Order((int)($order_id));
		$history 			= new OrderHistory();
		$history->id_order 	= (int)($order->id);
	    $extraVars          = array();
		if($verInt>=152){
			$history->changeIdOrderState((int)$sht_order_status,$order);
		}else{
			$history->changeIdOrderState((int)$sht_order_status, (int)($order->id));
		}
		$history->addWithemail(true, $extraVars);
	}
	 */
	include_once(dirname(__FILE__).'/../../footer.php');
?>
