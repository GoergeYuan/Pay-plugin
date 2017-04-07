<?php 
	//error_reporting(E_ALL);
	if(isset($zco_notifier)){
		$zco_notifier->notify('NOTIFY_HEADER_START_CHECKOUT_PAYRESULT');
	}
	//重置通知 
	$messageStack->reset();

	require_once(DIR_WS_MODULES.zen_get_module_directory('require_languages.php'));
	/*
	$extra_file = DIR_WS_MODULES.'payment/card/email_constant.php';
	if(file_exists($extra_file)){
		require_once($extra_file);
	}
	*/
	if(!isset($_GET['result']) || base64_decode($_GET['result']) === false) exit('Data error');

	parse_str(base64_decode($_GET['result']),$data);

	$BillNo			= trim($data['BillNo']);
	$Currency		= trim($data['Currency']);
	$Amount			= trim($data['Amount']);
	$Succeed		= trim($data['Succeed']);
	$MD5info		= trim($data['MD5info']);
	$currencyName	= trim($data['CurrencyName']);
	$MD5key			= MODULE_PAYMENT_CARD_MD5KEY;
	$Result			= $data['Result'];
	$description    = $_GET['description'] ? $_GET['description'] : 'Unknown cause';
	$md5str			= $BillNo.$Currency.$Amount.$Succeed.$MD5key;
	$md5Sign		= strtoupper(md5($md5str));
	//验证
	$title	= 'checkout_payresult';
	if($MD5info == $md5Sign)
	{
		$rorderno = '';
		//支付成功
		$text	= "{$Result}<br/><br/>Your Order Number&nbsp;:&nbsp;{$BillNo}<br/>Amount&nbsp;:&nbsp;{$Amount}&nbsp;{$currencyName}</br>Payment Result:{$description}<br/>";
		$comments = "[ - BillNo: {$BillNo} - Amount: {$Amount}  {$currencyName} - Succeed: {$Succeed} - Result: {$Result} - Description: {$description}";
		if($Succeed == '88'){
			$rorderno 				=  ' - Rorderno: ' . $data['rorderno'];
			$type					= 'success';
			$card_order_id	= MODULE_PAYMENT_CARD_ORDER_STATUS_PAY_SUCCESS_ID;
		}elseif($Succeed == '19'){
			$rorderno 				=  ' - Rorderno: ' . $data['rorderno'];
            $type                   = 'success';
            $card_order_id    = MODULE_PAYMENT_CARD_ORDER_STATUS_PAY_PROCESSING_ID;
        }elseif($Succeed == '0'){
			$type					= 'error';
			$text					= $text."&nbsp;Response Code:{$Succeed}";
			$card_order_id	= MODULE_PAYMENT_CARD_ORDER_STATUS_PAY_FAIL_ID;

			$log = "ordernumber:{$BillNo}---Amount:{$Amount}  {$currencyName}---Result:{$Result}";
		//	logRecord($log);
		}else{
			$type					= 'error';
			$text					= $text."&nbsp;Response Code:{$Succeed}";
			$log = "ordernumber:{$BillNo}---Amount:{$Amount}  {$currencyName}---Result:{$Result}";
			$card_order_id	= MODULE_PAYMENT_CARD_ORDER_STATUS_PAY_CARDDECLINED_ID;
		//	logRecord($log);
		}
		$comments  = $comments . $rorderno .  ' - ]';
		$sqlOrderStatus			= array('orders_status'=>$card_order_id,'orders_date_finished'=>'now()');
		$sqlOrderHistoryStatus	= array('orders_status_id'=>$card_order_id,'date_added'=>'now()', 'comments' => $comments);
		//更新订单状态
		zen_db_perform(TABLE_ORDERS,$sqlOrderStatus,'update','orders_id='.(int)$BillNo);
		zen_db_perform(TABLE_ORDERS_STATUS_HISTORY,$sqlOrderHistoryStatus,'update','orders_id='.(int)$BillNo);
		//发送邮件
		/*
		require_once(DIR_WS_CLASSES.'order.php');
		$order = new order($BillNo);
		$order->send_order_email(intval($BillNo),2);
		*/
	}else
	{
		$text = "pay result:verification failed;&nbsp;response code&nbsp;:&nbsp;{$Succeed}";
		$type = 'error';
		$log  = 'verification failed,please check yout md5key,thank you!';
		/*
		require_once(DIR_WS_CLASSES.'order.php');
		$order = new order($BillNo);
		$order->send_order_email(intval($BillNo),2);
		logRecord($log);
		*/
	}
	//输出错误
	$messageStack->add($title,$text,$type);
	if($MD5info == $md5Sign && ($Succeed == '88' or $Succeed == '19')){
		$_SESSION['cart']->reset(true);
		unset($_SESSION['cartID'],$_SESSION['orders_id'],$_SESSION['order_summary'],$_SESSION['order_number_created'],$_SESSION['sendto'],$_SESSION['billto'],$_SESSION['shipping'],$_SESSION['payment'],$_SESSION['comments']);
	}
?>
<?php 
	/*function logRecord($string)
	{
		$fp = fopen('card_error_log.txt','a');
		flock($fp,LOCK_EX);
		fwrite($fp,'time:'.date('Y-m-d H:i:s')."\t".$string."\r\n");
		flock($fp,LOCK_UN);
		fclose($fp);
	}*/
?>