<?php
class ControllerPaymentFirstTeam extends Controller {
	
	public function index() {
		$this -> load->model('checkout/order');
		$this->load->language('payment/FirstTeam');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');
		
		$data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_issue'] = $this->language->get('entry_cc_issue');
		
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');


		//支持的卡种
		$data['allowType'] = $this->config->get('FirstTeam_cardtype');
		$data['allowType_IMG'] = '';
		if(strstr($data['allowType'],"VISA")){

			
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_V');
		}
		
		if(strstr($data['allowType'],"MASTER")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_M');
		}
		if(strstr($data['allowType'],"JCB")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_J');
		}
		if(strstr($data['allowType'],"AE")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_A');
		}
		//$data['logo'] = $data['logo_V'].$data['logo_M'].$data['logo_J'].$data['logo_A'];
		
		$data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 16; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/checkout';
		} else {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		$this->id = 'payment';

		return $this->load->view('payment/FirstTeam', $data);
		//$this->render();
	}

	public function send() {
		$this->load->model('checkout/order');
		$this->language->load('payment/FirstTeam');
		
		$json = array();
		$CardNo_pro = str_replace(' ', '', $this->request->post['FirstTeam_cc_number']);
		$expireMonth=$this->request->post['FirstTeam_expire_date_month'];
		$expireYear=$this->request->post['FirstTeam_expire_date_year'];
		$securityCode= $this->request->post['FirstTeam_cvv'];
		$clientIP=$this->request->post['IPAddress'];
		//$issuringBank=$this->request->post['cc_issue'];
		$issuringBank='null';

		//************   行用卡参数检测提示   *************//


		  $errorMsg = $this->validateCardInfo($CardNo_pro,$securityCode,$expireYear,$expireMonth);
		
        if(!empty($errorMsg) && strlen($errorMsg) > 1) {
			$errorMsg  = $errorMsg;
			$json['info'] =$errorMsg;
			$this->response->setOutput(json_encode($json));
			return;
		}



     //************   行用卡参数检测提示 End  *************//   

		  if($clientIP == ' ' || $this->isOk_ip($clientIP) == 0 || $clientIP == '127.0.0.1' || $clientIP == 'undefined'){

	     	$clientIP = $this->getOnline_ip();
	   }
		
		

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$CardNo       = $CardNo_pro;
		$CardSecurityCode = $securityCode;
		$CardExpireYear   = $expireYear;
		$CardExpireMonth  = $expireMonth;
		$IssuingBank      = $issuringBank;
		$IPAddress = $clientIP;
		//$CardholderLocalTime = $_SESSION['payment_time'];
		$CardholderLocalTime = '';
		
		
		//$raw_post_data = file_get_contents('php://input', 'r');
		
		//var_dump ($raw_post_data);

		// 璁㈠崟淇℃伅
		$OrderNo = $this->session->data['order_id'];
		$MerNo = trim($this->config->get('FirstTeam_account'));
		$MD5key = trim($this->config->get('FirstTeam_secret'));

		// 速汇通开通币种
        //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗  15:卢布
    	$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15' );
    	$Currency   = $order_info['currency_code'];
		$CurrencyCode   = $CurrencyArray[$Currency];
		$Amount = $this->currency->format($order_info['total'], $Currency, $order_info['currency_value'], FALSE);
		//$Freight =$this->currency->format($this->session->data['shipping_method']['cost'], $CurrencyCode, $order_info['currency_value'], FALSE);
		$GoodListInfo = $this->products($order_info);

		// 璐﹀崟淇℃伅
		$BillFirstName = $order_info['payment_firstname'];	
		$BillLastName = $order_info['payment_lastname'];
		$BillAddress = $order_info['payment_address_1'].$order_info['payment_address_2'];
		$BillCity = $order_info['payment_city'];
		$BillState = $order_info['payment_zone'];
		$BillCountry = $order_info['payment_iso_code_2'];
		$BillZip = $order_info['payment_postcode'];
		$BillEmail = $order_info['email'];
		$BillPhone = $order_info['telephone'];

		// 鏀惰揣淇℃伅
		$ShipFirstName = $order_info['shipping_firstname'];
		$ShipLastName = $order_info['shipping_lastname'];
		$ShipAddress = $order_info['shipping_address_1'].$order_info['shipping_address_2'];
		$ShipCity  =$order_info['shipping_city'];
		$ShipState = $order_info['shipping_city'];
		$ShipCountry = $order_info['shipping_iso_code_2'];
		$ShipZip = $order_info['shipping_postcode'];
		$ShipEmail = $order_info['email'];
		$ShipPhone = $order_info['telephone'];

		// 閫氶亾鍙傛暟
		$Language = $this->session->data['language'];
		$ReturnURL = trim($this->config->get('FirstTeam_return_url'));
		$Remark  = $order_info['comment'];

		// 璐х墿淇℃伅
		
		$MD5src         = $MerNo . $OrderNo . $CurrencyCode . $Amount . $Language . $ReturnURL . $MD5key;
   		$MD5info        = strtoupper(md5($MD5src)); 

       // $BroserType =$_SERVER['HTTP_USER_AGENT'];
        $BrowserLang =$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $SessionId =$_SERVER['HTTP_COOKIE'];
		
		//涓嬪崟
		//$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('FirstTeam_order_status_id'));
		
		//缁勮鍙傛暟 

			$post_data = array(
			'MerNo' => $MerNo,
			'BillNo' => $OrderNo,
			'order_token' => $MD5key,
			'products' => $GoodListInfo,
			'Amount' => $Amount,
			'Currency' => $CurrencyCode,
			'firstname' => $BillFirstName,
			'lastname' => $BillLastName,
			'address' => $BillAddress,
			'city' => $BillCity,
			'state' => $BillState,
			'country' => $BillCountry,
			'zipcode' => $BillZip,
			'email' => $BillEmail,
			'phone' => $BillPhone,
			'shippingFirstName' => $ShipFirstName,
			'shippingLastName' => $ShipLastName,
			'shippingAddress' => $ShipAddress,
			'shippingCity' => $ShipCity,
			'shippingSstate' => $ShipState,
			'shippingCountry' => $ShipCountry,
			'shippingZipcode' => $ShipZip,
			'shippingEmail' => $ShipEmail,
			'shippingPhone' => $ShipPhone,
			'Language' => $Language,
			'ReturnURL' => $ReturnURL,
			'Remark' => $Remark,
			'MD5info' => $MD5info,
			'cardnum' => $CardNo,
			'cvv2' => $CardSecurityCode,
			'month' => $CardExpireMonth,
			'year' => $CardExpireYear,
			'cardbank' => $IssuingBank,
			'ip' => $IPAddress
		);

		$post_data = http_build_query($post_data, '', '&');
		
		$Current_PaymentURL = trim($this->config->get('FirstTeam_payment_url'));
		$PayURL = !empty($Current_PaymentURL) ? $Current_PaymentURL :'http://ssl.hpolineshop.com/sslWebsitpayment';

	
	
       //请求支付
	    $result = $this->vpost($PayURL, $post_data);
	    if(is_array($result)){
	        $this->response->setOutput(json_encode($result));
	        return;
	    }
		parse_str($result,$myArray);

	   
	    /* MD5私钥 */
	    $MD5key     = trim($this->config->get('FirstTeam_secret'));
	     /* 流水号 */
	    $PaymentOrderNo     = $myArray['PaymentOrderNo'];
	    /* 订单号 */
	    $BillNo     = $myArray['BillNo'];
	    /* 订单金额 */
	    $Amount     = $myArray['Amount'];
	    /* 支付币种 */
	    $Currency   = $myArray['Currency'];    //返回币种数字
	    /* 支付币种符号 */
	    $CurrencyName   = $myArray['CurrencyName'];    //返回币种名称，如：USD
	    /* 支付状态
	        $Success  88:支付成功  19: 待处理[现在不会返回]  其它状态失败
	    */
	    $Succeed    = $myArray['Succeed'];

	    /* 支付结果 */
	    $Result     = $myArray['Result'];
	    /* MD5info校验信息 */
	    $MD5info    = $myArray['MD5info'];
	    
	    /* 校验源字符串 */
	    $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
	    /* 校验结果 */
	    $MD5sign    = strtoupper(md5($md5src));

	    /*引入返回码说明 */
	    require 'paylib/System_Response.php';
		$payResponseMsg = new System_Response();

		$message_failure = "Payment No:" . $PaymentOrderNo . ", Order No:" . $BillNo .", Amount:" . $Amount . " ".$CurrencyName.", Transaction Message:" . $Result. ", Error Code:" . $Succeed .", Description:".$payResponseMsg->checkinfo($Succeed);
		$message_succeed = "Payment No:" . $PaymentOrderNo . ", Order No:" . $BillNo .", Amount:" . $Amount . " ".$CurrencyName.", Transaction Message:" . $Result;
	

		if($Succeed == '88'){
			$this->model_checkout_order->addOrderHistory($BillNo, $this->config->get('FirstTeam_order_succeed_status_id'), $message_succeed, true);
			$json['success'] = $this->url->link('checkout/checkout_result', '', 'SSL');
			//閿�瘉cart
			$this->session->data['cart'] = array();
			//$json['success'] = HTTPS_SERVER . 'index.php?route=checkout/success'; 

		}else if($Succeed == 99 || $Succeed == '99'){
			$this->model_checkout_order->addOrderHistory($BillNo, $this->config->get('FirstTeam_order_payWait_status_id'), $message_succeed, true);	
			$json['success'] = $this->url->link('checkout/checkout_result', '', 'SSL');
			//閿�瘉cart
			$this->session->data['cart'] = array();
			
		}else{
			$this->model_checkout_order->addOrderHistory($BillNo, $this->config->get('FirstTeam_order_failed_status_id'), $message_failure, false);	
			$json['error'] ='Return Message:'.$Result;
			
		}

		$_SESSION['order_id'] = $this->session->data['order_id'];
		$_SESSION['ResultMessage'] = $Result;
		$_SESSION['Succeed'] = $Succeed;
		$_SESSION['payResponseMsg'] = $payResponseMsg->checkinfo($Succeed);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}


	
	//鏀粯澶辫触璺宠浆
	public function failure() {
			$this->language->load('payment/FirstTeam');
			
			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = HTTP_SERVER;
			} else {
				$data['base'] = HTTPS_SERVER;
			}
		
			$data['Your_BillNo'] = $this->language->get('Your_BillNo');
			$data['charset'] = $this->language->get('charset');
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
		
			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			
			$data['text_response'] = $this->language->get('text_response');
			$data['text_Error'] = $this->language->get('text_Error');
			$data['text_success'] = $this->language->get('text_success');
			$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
		
			$data['text_failure'] = $this->language->get('text_failure');
			
			$data['text_billno']='<font color="green">'.$this->session->data['order_id'].'</font>';
			
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
			} else {
				$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/footer',
				'common/header'			
			);
			$data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/payment/FirstTeam_fail.tpl' )) {
				$this->template = $this->config->get ( 'config_template' ) . '/template/payment/FirstTeam_fail.tpl';
			} else {
				$this->template = 'default/template/payment/FirstTeam_fail.tpl';
			}
		    $this->response->setOutput( $this->render(TRUE), $this->config->get('config_compression'));
	}


/* 获取客户端ip */
	
	function getOnline_ip(){ 
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$online_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		}
		elseif(isset($_SERVER['HTTP_CLIENT_IP'])){ 
			$online_ip = $_SERVER['HTTP_CLIENT_IP']; 
		}
		elseif(isset($_SERVER['HTTP_X_REAL_IP'])){ 
			$online_ip = $_SERVER['HTTP_X_REAL_IP']; 
		}else{ 
			$online_ip = $_SERVER['REMOTE_ADDR']; 
		}
		$ips = explode(",",$online_ip);
		return $ips[0];  
	}


	
/**************  卡号验证     ***************/

     /**
     * 校验信用卡信息是否有效
     * @param $year
     * @param $month
     * @return $msg
     */
    function validateCardInfo($cardNum,$cvv,$year,$month) {
		$errorMsg = "";
		$errorMsg = $this->validateCardNum($cardNum);
		
		if(!empty($errorMsg) && strlen($errorMsg)>1) {
			return $errorMsg;
		}

        $errorMsg = $this->validateCardType($cardNum);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }

        $errorMsg = $this->validateCVV($cvv);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }

        $errorMsg = $this->validateExpiresDate($year,$month);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }
        return "";
    }

	/**
	 * 校验信用卡卡号是否有效
	 * @param $cardNum
	 * @return String
	 */
	function validateCardNum($cardNum) {
		$msg = "";
		if(empty($cardNum) || !is_numeric($cardNum) || strlen($cardNum)<13 || strlen($cardNum)>16 ||
			 !$this->card_check_by_luhn($cardNum)) {
			 $msg = $this->language->get('Empty_cc_number');
		}
		return $msg;
	}



    /**
     * 通过Luhn算法校验信用卡卡号是否有效
     * @param $cardNum
     * @return bool
     */
    function card_check_by_luhn($cardNum){
        $str = '';
        foreach(array_reverse(str_split($cardNum)) as $i => $c) $str .= ($i % 2 ? $c * 2 : $c);
        return array_sum(str_split($str)) % 10 == 0;
    }

    /**
     * 校验信用卡卡种是否有效
     * @param $cardNum
     * @return String
     */
    function validateCardType($cardNum) {
        $msg = "";
        //支持的卡种
        $allowType = $this->config->get('FirstTeam_cardtype');
		
		if($allowType == " ") {
			$allowType_msg = $this->language->get('Type_cc_none');
			return $allowType_msg;
		}

        $cardType = $this->getCardTypeByCardNum($cardNum);
        if(empty($cardType) || strlen($cardType) < 1 || !strstr($allowType,$cardType)) {
            $msg = $this->language->get('Type_cc_NotcardType');
            if(!empty($allowType) && strlen($allowType) > 1){
                $msg .= $this->language->get('Type_cc_CardType'). $this->config->get('FirstTeam_cardtype');
            }
        }
        return $msg;
    }


    

    /**
     * 校验信用卡卡号是否有效
     * @param $cardNum
     * @return String
     */
    function getCardTypeByCardNum($cardNum) {
        $cardType = "";
        $left = substr($cardNum, 0, 2);
        if($left >= 40 && $left <= 49){
            $cardType = "VISA";
        }else if($left >= 50 && $left <=59) {
            $cardType = "MASTER";
        }else if($left == 35) {
            $cardType = "JCB";
        }else if($left == 34 || $left == 37) {
            $cardType = "AE";
        }
        return $cardType;
    }

    /**
     * 校验信用卡CVV是否有效
     * @param $cvv
     * @return $msg
     */
    function validateCVV($cvv) {
        $msg = "";
        if(empty($cvv) || !is_numeric($cvv) || strlen($cvv)!=3) {
			
            $msg = $this->language->get('Empty_cc_cvv');
        }
        return $msg;
    }

    /**
     * 校验信用卡有效期是否有效
     * @param $year
     * @param $month
     * @return $msg
     */
    function validateExpiresDate($year,$month) {
        $msg = "";
        if(empty($year) || !is_numeric($year) || strlen($year) !=4) {
            $msg = $this->language->get('Empty_cc_year');
        } else if(empty($month) || !is_numeric($month) || strlen($month) !=2 || $month < 1 || $month>12) {
            $msg = $this->language->get('Empty_cc_month');
        } else {
            $currentDate  = new DateTime(date("Y-m",time()));
            $inputDate    = new DateTime($year."-".$month);
            if($year<date("Y",time()) || $inputDate->format('U') < $currentDate->format('U')) {
                $msg = $this->language->get('Empty_cc_date');
            }
        }
        return $msg;
    }


/***************  卡号验证 End    ***************/


 /*
  *	ip验证正则
  *
  */

	 function isOk_ip($ip){

 if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))

	{

	return 1;

	}else{

	return 0;

	}

}


/*
*
*	商品信息
*
*/
	function products($order_info){

		$products = $this->cart->getProducts();
		$GoodList = "";
		if($products){		
			foreach($products as $product){
				$GoodList=$GoodList."<GoodsName>".$product['name']."</GoodsName><Qty>".$product['quantity'].
								"</Qty><Price>".$this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], FALSE)."</Price><Currency>".$order_info['currency_code']."</Currency>";
			}
			$GoodListInfo = "<Goods>".$GoodList."</Goods>";
			}else{

				$GoodListInfo = "no products";
			}
			return $GoodListInfo;
		}
	


/**
 * Store additional order information
 * 支付请求网关
 * @param URL $url          请求网关
 * @param string $data      请求数据
 * @param number $timeout   默认请求时间
 */
	function vpost($url, $data ,$timeout = 30) {
	   
	    $errorMsg = array();
		if(function_exists('curl_init') && function_exists('curl_exec')){  //curl

		    require 'paylib/Http_Client_Curl.php';
		    $type = 'curl';
		    $httpCurlQuery = new Http_Curl_Query();
		    $headers[] = "Expect: ";
		    $status = $httpCurlQuery
		        ->setOpt(CURLOPT_URL, $url)
		        ->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE)
		        ->setOpt(CURLOPT_SSL_VERIFYHOST, 0)
		        ->setOpt(CURLOPT_HTTPHEADER, $headers)
		        ->setOpt(CURLOPT_TIMEOUT, $timeout)
		        ->setOpt(CURLOPT_CONNECTTIMEOUT, $timeout)
		        ->setOpt(CURLOPT_FRESH_CONNECT, 1)
		        ->httpPost($data)->response['http_code'];

		    if($status == 200){
		        $result = $httpCurlQuery->response['content'];
		    }else{
		    	$erron = $httpCurlQuery->response['errno'];
		    	$http_code = $httpCurlQuery->response['http_code'];
		        $errorMsg = $httpCurlQuery->response['error'];
		        
			}

		}elseif (function_exists('fsockopen')) {     //fsockopen
            $parts = parse_url($url);
		    $host = $parts['host'];
		    $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
		    $path = isset($parts['path']) ? $parts['path'] : '/';
		    if(isset($parts['port'])){
		        $port = intval($parts['port']);
		    }else{
		        if($scheme == 'https'){
		            $port = 443;
		        }else{
		            $port = 80;
		        }
		    }
			require 'paylib/Http_Client_Socket.php';
			$type = 'fsockopen';
		    $httpClient = new Http_Client($host);
		    $httpClient->setDebug(false);         //是否开启调试模式
		    $httpClient->setPersistReferers(false);
		    $httpClient->referer = $_SERVER['HTTP_REFERER'];
		    $httpClient->setUserAgent($_SERVER['HTTP_USER_AGENT']);
		    $httpClient->timeout = $timeout;

		    $flag = $httpClient->post($path, $data);
		    $status = $httpClient->getStatus();
		    if($flag === true){
		        $result = $httpClient->getContent();
		    }else{
		        $errorMsg = $httpClient->getError();
		    }

		}else{
			$request_failure = 'curl or fsockopen is not enable';
			$errorMsg = array('info'=>'curl or fsockopen is not enable');
		}

		if($status == 200 && $result){
			$request_failure = '';
			$errorMsg = $result;
		}elseif($status !== 200 && $type == 'curl'){

			$request_failure   = "curl request error  Error code:".$erron."Http status:".$http_code."Result:".$errorMsg;
			$errorMsg =  array('info'=> 'curl request error :'.$errorMsg);
		}elseif($status !== 200 && $type == 'fsockopen'){

			$request_failure   = "fsockopen request error  Result:" . $errorMsg;
			$errorMsg = array('info'=> 'fsockopen request error : '.$errorMsg);
		}else{
			$request_failure = 'request error';
		    $errorMsg = array('info'=>'request error');
		}
		//在failed状态添加comment备注
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('FirstTeam_order_failed_status_id'), $request_failure, false);	
	   
	    return $errorMsg;
	}
	
	/**
	 * 瀵圭壒娈婂瓧绗﹀瓧绗﹁繘琛岃浆涔�
	 * @param String string_before
	 * @return String string_after
	 */
	function string_replace($string_before) {
		$string_after = str_replace("\n"," ",$string_before);
        $string_after = str_replace("\r"," ",$string_after);
        $string_after = str_replace("\r\n"," ",$string_after);
		$string_after = str_replace("'","&#39 ",$string_after);
		$string_after = str_replace('"',"&#34 ",$string_after);
		$string_after = str_replace("(","&#40 ",$string_after);
		$string_after = str_replace(")","&#41 ",$string_after);
		return $string_after;
	}
}

?>