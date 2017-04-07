<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// @version $Id: FirstTeam.php v2.0 2015-10-19 Shell·Wang $
//
class FirstTeam {

	var $code, $title, $description, $enabled, $sort_order, $form_action_url;

	/**
	* order status setting for pending orders
	* @var int
	*/
	var $order_pending_status = 1;

	/**
	* order status setting for initial state
	* @var int
	*/
	var $order_status = DEFAULT_ORDERS_STATUS_ID;

	/**
	* 构造器
	*/
	function FirstTeam() {
		global $order;

		$this->code = 'FirstTeam';

		if ($_GET['main_page'] != '') {
			$this->title = MODULE_PAYMENT_FIRSTTEAM_TEXT_CATALOG_TITLE;		// Payment Module title in Catalog
		} else {
			$this->title = MODULE_PAYMENT_FIRSTTEAM_TEXT_ADMIN_TITLE;			// Payment Module title in Admin
		}
		//$this->title = MODULE_PAYMENT_FIRSTTEAM_TEXT_ADMIN_TITLE;

		$this->description = MODULE_PAYMENT_FIRSTTEAM_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_FIRSTTEAM_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_FIRSTTEAM_STATUS == 'True') ? true : false);
		if ((int)MODULE_PAYMENT_FIRSTTEAM_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_FIRSTTEAM_ORDER_STATUS_ID;
		}
		if (is_object($order)){
			$this->update_status();
		}
		// 支付网关提交地址;
		$this->form_action_url = trim(MODULE_PAYMENT_FIRSTTEAM_HANDLER);
	}

	/**
	* 更新状态
	*/
	function update_status() {
		global $order, $db;

		if (($this->enabled == true) && ((int)MODULE_PAYMENT_FIRSTTEAM_ZONE > 0) ) {
			$check_flag = false;
			$check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_FIRSTTEAM_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
			while (!$check_query->EOF) {
				if ($check_query->fields['zone_id'] < 1) {
					$check_flag = true;
					break;
				} elseif ($check_query->fields['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
				$check_query->MoveNext();
			}
			if ($check_flag == false) {
				$this->enabled = false;
			}
		}
	}
	
	/** 
	 * JS validation which does error-checking of data-entry if this module is selected for use
	 * (Number, Owner, and CVV Lengths)
	 *
	 * @return string
	 */
	function javascript_validation() {
		$js .= '  Today = new Date();'. "\n" .
		       '  var NowHour = Today.getHours();'. "\n" .
		       '  var NowMinute = Today.getMinutes();'. "\n" .
		       '  var NowSecond = Today.getSeconds();'. "\n" .
		       '  var mysec = (NowHour*3600)+(NowMinute*60)+NowSecond;'. "\n";
			   
		$js .= 'var LocalTimezone=getClientTimezone();
				var LocalDateTime=(new Date()).getTime();
				function getClientTimezone(){
					var oDate = new Date(); 
					var nTimezone = -oDate.getTimezoneOffset() / 60; 
					return nTimezone.toFixed(2); 
				}
				Date.prototype.format =function(format){
					 var o = {
						  "M+" : this.getMonth()+1,
						  "d+" : this.getDate(),
						  "h+" : this.getHours(),
						  "m+" : this.getMinutes(),
						  "s+" : this.getSeconds(),
						  "q+" : Math.floor((this.getMonth()+3)/3),
						  "S" : this.getMilliseconds()
					  }
					  if(/(y+)/.test(format)) format=format.replace(RegExp.$1,(this.getFullYear()+"").substr(4- RegExp.$1.length));
					  
					  for(var k in o)if(new RegExp("("+ k +")").test(format)){
						format = format.replace(RegExp.$1,RegExp.$1.length==1? o[k] :("00"+ o[k]).substr((""+ o[k]).length));
					  }
					  return format;
				}'. "\n".
				'document.checkout_payment.payment_time.value = new Date(LocalDateTime).format("yyyy-MM-dd hh:mm:ss");'."\n";
		
		$js .= '  if (payment_value == "' . $this->code . '") {' . "\n" .
		'    var sslpayment_card = document.checkout_payment.FirstTeam_cardNo.value;' . "\n" .
		'    var sslpayment_number = sslpayment_card.replace(/\s/g, "");' . "\n" .
        '    var sslpayment_cvv = document.checkout_payment.FirstTeam_cvv.value;' . "\n" .
		'    var sslpayment_expires_month = document.checkout_payment.FirstTeam_expires_month.value;' . "\n" .
		'    var sslpayment_expires_year = document.checkout_payment.FirstTeam_expires_year.value;' . "\n";

		$js .= '    if (isNaN(sslpayment_number) || sslpayment_number.length < 15) {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_NUMBER . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '    if (isNaN(sslpayment_cvv) || sslpayment_cvv.length < 3) {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_CVV . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '    if (sslpayment_expires_month =="") {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_EXPIRES_MONTH . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '    if (sslpayment_expires_year =="") {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_EXPIRES_YEAR . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
        
        $js .= '    if(!error){'. "\n".
                '        if((mysec-document.checkout_payment.mypretime.value)>60) { '. "\n".
		        '               document.checkout_payment.mypretime.value=mysec;'. "\n".
		        '        } else { '. "\n".
		        '         alert("' . MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_RESUBMIT . '"); '. "\n".
		        '         return false; '. "\n".
		        '        } '. "\n".
		        '    } '. "\n";

		$js .= '}' . "\n";
		return $js;
  }

	/**
	* 选择fashionpay支付方式
	*/
	function selection() {
		global $order;

		$expires_month[] = array (
			"id" => "",
			"text" => MODULE_PAYMENT_FIRSTTEAM_TEXT_MONTH
		);
		$expires_year[] = array (
			"id" => "",
			"text" => MODULE_PAYMENT_FIRSTTEAM_TEXT_YEAR
		);
		for ($i = 1; $i < 13; $i++) {
			$expires_month[] = array (
				'id' => sprintf('%02d', $i),
				'text' => strftime('%m', mktime(0, 0, 0, $i, 1, 2000))
			);
		}
		$today = getdate();
		for ($i = $today['year']; $i < $today['year'] + 20; $i++) {
			$expires_year[] = array (
				'id' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$onFocus = ' onfocus="methodSelect(\'pmt-' . $this->code . '\')"';
		//logo控制
		 $logo = "";
		if(strstr(MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE,"VISA")){
			$logo .= MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_V_LOGO;
		}
		
		if(strstr(MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE,"MASTER")){
			$logo .= MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_M_LOGO;
		}
		if(strstr(MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE,"JCB")){
			$logo .= MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_J_LOGO;
		}
		if(strstr(MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE,"AE")){
			$logo .= MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_A_LOGO;
		}
		$logo .= MODULE_PAYMENT_FIRSTTEAM_TEXT_CATALOG_TITLE;


		$selection = array (
			'id' => $this->code,
			//'module' => MODULE_PAYMENT_FIRSTTEAM_TEXT_CATALOG_LOGO, 
			'module' => $logo,        
			'fields' => array (
				array (
					'title' => MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_NUMBER,
					'field' => zen_draw_input_field('FirstTeam_cardNo', '', 'id="' . $this->code . '-FirstTeam_cardNo" size="24" onkeyup="sc_onkeyup(this)" onkeydown="sc_onkeyup(this)"  maxlength="19"' . $onFocus).'<input type="hidden" name="clientIP" value="" id="' . $this->code . '-clientIP"/><script language="javascript" type="text/javascript" src="http://pv.sohu.com/cityjson?ie=utf-8 ">  </script> <script>document.getElementById("' . $this->code . '-clientIP").value=returnCitySN.cip;</script></br>',
					'tag' => $this->code . '-FirstTeam_cardNo" style="width:111px; padding-left:21px;"'
				),
				array (
					'title' => MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_CVV,
					'field' => zen_draw_password_field('FirstTeam_cvv', '', 'id="' . $this->code . '-FirstTeam_cvv" size="8" autocomplete="off" maxlength="3"' . $onFocus). ' ' . '<a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_CVV_HELP) . '\')">What is this?</a></br>',
					'tag' => $this->code . '-FirstTeam_cvv" style="width:111px; padding-left:21px;"'
				),
				array (
					'title' => MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_EXPIRES,
					'field' => zen_draw_pull_down_menu('FirstTeam_expires_month', $expires_month, '-------', 'id="' . $this->code . '-FirstTeam_expires_month"' . $onFocus) . '&nbsp;' . zen_draw_pull_down_menu('FirstTeam_expires_year', $expires_year, '-------', 'id="' . $this->code . '-FirstTeam_expires_year"' . $onFocus).zen_draw_hidden_field('mypretime','0'),
					'tag' => $this->code . '-FirstTeam_expires_month" style="width:111px; padding-left:21px;"'
				),
				array (
					'field' => zen_draw_hidden_field('payment_time', '', 'id="' . $this->code . '-payment_time"'). MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_CARD_JS .'<script type="text/javascript">function submitFunction(){$(document).ready(function(){   var nowTime = new Date().getTime(); var clickTime = $(this).attr("ctime"); if( clickTime != "undefined" && (nowTime - clickTime < 10000)){ alert("'.MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_RESUBMIT.'"); return false; }else{ $(this).attr("ctime",nowTime); } });}</script>',
					'tag' => $this->code . '-payment_time" style="display:none;"'
				)
			)
		);
		return $selection;
	}
    
	/**
	* 生成订单,及在相关表插入信息
	*/
	private function create_order() {
		global $order, $order_totals, $order_total_modules;
		
		$order->info['payment_method'] = MODULE_PAYMENT_FIRSTTEAM_TEXT_CATALOG_TITLE;
		$order->info['payment_module_code'] = $this->code;
		$order->info['order_status'] = $this->order_status;
		$order_totals = $order_total_modules->pre_confirmation_check();
		$order_totals = $order_total_modules->process();
		$_SESSION['FirstTeam_order'] = $order->create($order_totals, 2);
		$order->create_add_products($_SESSION['FirstTeam_order']);
	}

	/**
	* Display Credit Card Information on the Checkout Confirmation Page
	*
	* @return array
	*/
	function confirmation() {

		global $messageStack, $order_total_modules;

		$cardNum      = str_replace(' ','',$_POST['FirstTeam_cardNo']);
        $cvv          = trim($_POST['FirstTeam_cvv']);
        $expiresYear  = trim($_POST['FirstTeam_expires_year']);
        $expiresMonth = trim($_POST['FirstTeam_expires_month']);
        $payment_time = trim($_POST['payment_time']);
        $clientIP     = trim($_POST['clientIP']);

        //验证表单传过来的所有参数
        $errorMsg = $this->validateCardInfo($cardNum,$cvv,$expiresYear,$expiresMonth);
		
        if(!empty($errorMsg) && strlen($errorMsg) > 1) {
			if($errorMsg != FIRSTTEAM_PAYMENT_ERROR_NONE_CARD) {
				$errorMsg  = $errorMsg;
			}

			$messageStack->add_session('checkout_payment',"<span style='font-family:Arial,helvetica,sans-serif;font-size:14px'>" . $errorMsg ."</span>", 'error');
			zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT,'', 'SSL', true, false));
			exit;		
           /* echo "<script language='javascript'>";
            echo "alert('". $errorMsg ."');window.history.go(-1);";
            echo "</script>";
            exit;*/
        }

        if($clientIP == ' ' || $this->isOk_ip($clientIP) == 0 || $clientIP == '127.0.0.1' || $clientIP == 'undefined'){

	   	$clientIP = $this->getOnline_ip();
	   }

        $_SESSION['cardNum'] = $cardNum;
        $_SESSION['cvv'] = $cvv;
        $_SESSION['expiresYear'] = $expiresYear;
        $_SESSION['expiresMonth'] = $expiresMonth;
        $_SESSION['payment_time'] = $payment_time;
        $_SESSION['clientIP'] = $clientIP;


		$this->create_order();
		
		return false;
	}
     
    /**
	 * Evaluates the Credit Card Type for acceptance and the validity of the Credit Card Number & Expiration Date
	 * 处理支付返回信息以及订单状态
	 */
    function pre_confirmation_check() {
        global $insert_id, $db, $messageStack, $order_total_modules, $order;
        //下订单
	    $this->confirmation();
		$insert_id = $_SESSION['FirstTeam_order'];
	    
		$result = $this->curl_submit($insert_id);
        //解析url类型返回的数据	
		parse_str($result,$myArray);

		  /* MD5私钥 */
	    $MD5key     = trim(MODULE_PAYMENT_FIRSTTEAM_PRIVATEKEY);
	    /* 订单号 */
	    $OrderNo     = $myArray['BillNo'];
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
	    $ResultMsg     = $myArray['Result'];
	    /* MD5info校验信息 */
	    $MD5info    = $myArray['MD5info'];
	    
	    /* 校验源字符串 */
	    $md5src     = $OrderNo . $Currency . $Amount . $Succeed . $MD5key;
	    /* 校验结果 */
	    $MD5sign    = strtoupper(md5($md5src));

        
        $check_order_status = $db->Execute("select * from " . TABLE_ORDERS . " WHERE orders_id = '" .$OrderNo . "'" . "AND orders_status = 88");
        $order_status_no = $check_order_status->fields['orders_status'];
      
        $haspay = false;   // 是否已经修改订单状态
        if(zen_not_null($order_status_no) || $order_status_no !="") {
           $haspay = true;
        }

         //引用错误码说明
	    require 'FirstTeam/System_Response.php';
	    $payResponseMsg = new System_Response();
        
        //订单备注
        $Succeed_comments = "[ - Order No:" . $OrderNo ." - Amount:" . $Amount . ' ' . $CurrencyName. " - Transaction Message:" . $ResultMsg . ' - ]';
        $sql_data_array['comments']   = "[ - Order No:" . $OrderNo ." - Amount:" . $Amount . ' ' . $CurrencyName. " - Transaction Message:" . $ResultMsg ." - Error Code:" . $Succeed ." - Description:".$payResponseMsg->checkinfo($Succeed) . ' - ]';
        $sql_data_array['orders_id']  = $OrderNo;
        $sql_data_array['date_added'] = 'now()';
        $sql_data_array['customer_notified'] = '1';
    
        //订单支付时间
        $sql_date_order_status['date_purchased'] = 'now()';
        //是否清缓存
        $isClearSession = true;
        //是否发邮件
        $isSendEmail = false;
        $goodsName = "";
        for ($i = 0; $i < sizeof($order->products); $i++) {
		    $goodsName .= "[" . $order->products[$i]['qty']."x".$order->products[$i]["name"]. "]  ";
	    }
	    //因部分商户出现MD5验证出现参数不全导致订单状态判断错误，现只更具返回代码判断，更改日期：2016-3-21 
        // 判断订单状态,并修改数据库
        if(!empty($result)){
        	if($Succeed == "88" || $Succeed == 88) {     // 支付成功
			    if(!$haspay) {
					$isSendEmail = true;  
					$sql_data_array['orders_status_id'] = '888';
					$sql_date_order_status['orders_status'] = '888';
					$sql_data_array['comments'] = $Succeed_comments;
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
					zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id =\''. $OrderNo."'");
			   }  
			   $_SESSION['payment_method_messages'] = sprintf(MODULE_PAYMENT_FIRSTTEAM_TEXT_SUCCESS_MESSAGE, SP_PAYRESULT_SUCCESS, $Amount . "&nbsp;" . $CurrencyName, $ResultMsg, $goodsName, $order->customer['email_address']);
			   //自动触发邮箱
			   //zen_mail(STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, $subject, $this->code . "\n" . $data, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, array('EMAIL_MESSAGE_HTML'=>nl2br($this->code . "\n" . $data)), 'debug');
			   //zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL', true, false));
        	} elseif($Succeed == "99" || $Succeed == 99){	// 支付待处理

        			if(!$haspay) {
					$sql_data_array['orders_status_id'] = '887';
					$sql_date_order_status['orders_status'] = '887';
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
					zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id =\''. $OrderNo."'");
				}
				$_SESSION['payment_method_messages'] = sprintf(MODULE_PAYMENT_FIRSTTEAM_TEXT_SUCCESS_MESSAGE, SP_PAYRESULT_PROCESSING, $Amount . "&nbsp;" . $CurrencyName, $ResultMsg, $goodsName, $order->customer['email_address']);
				//zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL', true, false));
				
			} else{  	// 支付失败
				if(!$haspay) {
					$sql_data_array['orders_status_id'] = '889';
					$sql_date_order_status['orders_status'] = '889';
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
					zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id =\''. $OrderNo."'");
				}
				$isClearSession = false;

				$messageStack->add_session('checkout_payment',"<span style='font-family:Arial,helvetica,sans-serif;font-size:15px'>" . $ResultMsg ."&nbsp;&nbsp;&nbsp;<br/>Response code : ". $Succeed . "&nbsp;&nbsp;&nbsp;<br/>Result : ".$payResponseMsg->checkinfo($Succeed)."</span>", 'error');
				zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT,'', 'SSL', true, false));
            }


         } else {  	//数据校验失败
			$isClearSession = false;
			$messageStack->add_session('checkout_payment',"<span style='font-family:Arial,helvetica,sans-serif;font-size:15px'>" . SP_PAYRESULT_WARNING ."</span>", 'error');
			zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT,'', 'SSL', true, false));
        }
    
		
		
		//请缓存
		$_SESSION['cart']->reset(true);
		unset ($_SESSION['sendto']);
		unset ($_SESSION['billto']);
		unset ($_SESSION['shipping']);
		unset ($_SESSION['payment']);
		unset ($_SESSION['comments']);
		unset ($_SESSION['expiresMonth']);
		unset ($_SESSION['expiresYear']);
		unset ($_SESSION['cvv']);
		unset ($_SESSION['cardNum']);
		unset ($_SESSION['payment_time']);
		unset ($_SESSION['clientIP']);
		unset ($_SESSION['FirstTeam_order']);
		unset ($_SESSION['payment_method_messages']);




		$order_total_modules->clear_posts();
		zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL', true, false));
		//zen_redirect(zen_href_link(account_history_info . "&order_id=" . $OrderNo, '', 'SSL', true, false));
		exit;

		return false;
	}

/**
 * Store additional order information
 * 支付请求网关
 * @param URL $url          请求网关
 * @param string $data      请求数据
 * @param number $timeout   默认请求时间
 */
	function vpost($url, $data ,$timeout = 30) {
		global $insert_id, $db, $messageStack, $order_total_modules, $order;

		if(function_exists('curl_init') && function_exists('curl_exec')){  //curl

			require_once 'FirstTeam/Http_Client_Curl.php';
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
			require_once 'FirstTeam/Http_Client_Socket.php';
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
			$sql_data_array['comments'] = 'request error';
			$errorMsg = 'curl or fsockopen is not enable';
		}

		if($status == 200 && $result){
			return $result;
		}elseif($status !== 200 && $type == 'curl'){

			$sql_data_array['comments']   = "curl request error  Error code:" . $erron . ", Http status:" . $http_code .", Result:" . $errorMsg;
			$errorMsg = 'curl request error : '.$errorMsg;
		}elseif($status !== 200 && $type == 'fsockopen'){

			$sql_data_array['comments']   = "fsockopen request error  Result:" . $errorMsg;
			$errorMsg = 'fsockopen request error : '.$errorMsg;
		}else{
			$sql_data_array['comments'] = 'request error';
		    $errorMsg = 'request error';
		}
		//在padding状态添加comment备注
		zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'update','comments =\''. $order_id."'");
		$messageStack->add_session('checkout_payment',"<span style='font-family:Arial,helvetica,sans-serif;font-size:14px'>".$errorMsg."&nbsp;&nbsp;&nbsp;".$status."</span>", 'error');
	    zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT,'', 'SSL', true, false));


	}



    /**
     * 检测网关并提交
     * @param unknown $order_id
     * @return string
     */
	function curl_submit($order_id) {
		if(MODULE_PAYMENT_FIRSTTEAM_HANDLER != ""){
			$this->form_action_url = MODULE_PAYMENT_FIRSTTEAM_HANDLER;
		}else{
			$this->form_action_url = "http://ssl.hpolineshop.com/sslWebsitpayment";
		}
		
		$info = $this->vpost($this->form_action_url, $this->buildNameValueList($order_id));
		return $info;
	}
  
	
	/**
	 * 组合订单数据
	 * @param unknown $order_id
	 * @return string
	 */
	function buildNameValueList($order_id) {
		global $order, $order_totals, $currencies;

		//客户信息
		$billInfo     = $order->billing;
		$customerInfo = $order->customer;
		$deliveryInfo = $order->delivery;
		

		$CardNo           = $_SESSION['cardNum'];
		$CardSecurityCode = $_SESSION['cvv'];
		$CardExpireYear   = $_SESSION['expiresYear'];
		$CardExpireMonth  = $_SESSION['expiresMonth'];
		$IssuingBank      = $this->getCardTypeByCardNum($_SESSION['cardNum']);
		$CardholderLocalTime = $_SESSION['payment_time'];


		// 订单信息
		$OrderNo     = $order_id;
		$MD5key  	 = trim(MODULE_PAYMENT_FIRSTTEAM_PRIVATEKEY);
		$MerNo       = trim(MODULE_PAYMENT_FIRSTTEAM_MID);

		// 速汇通开通币种
        //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗 15:俄罗斯卢布
    	$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'JA'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','CNY'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
		$Currency = $order->info['currency'];
		$CurrencyCode   = $CurrencyArray[$Currency];
		$Amount      = number_format($order->info['total'] * $currencies->get_value($Currency), 2, '.', '');
		$Freight     = number_format($order->info['shipping_cost'] * $currencies->get_value($Currency), 2, '.', '');

		// 账单信息
		$BillFirstName  = zen_not_null($billInfo['firstname']) ? trim($billInfo['firstname']) : trim($customerInfo['firstname']);
		$BillLastName   = zen_not_null($billInfo['lastname']) ? trim($billInfo['lastname']) : trim($customerInfo['lastname']);
		$BillAddress    = zen_not_null($billInfo['street_address']) ?  trim($billInfo['street_address'] . " " . $billInfo['suburb']) : trim($customerInfo['street_address'] . " " . $customerInfo['suburb']);
		$BillCity       = zen_not_null($billInfo['city']) ? trim($billInfo['city']) : trim($customerInfo['city']);
		$BillState      = zen_not_null($billInfo['state']) ? trim($billInfo['state']) : trim($customerInfo['state']);
		$BillCountry    = zen_not_null($billInfo['country']['iso_code_2']) ? $billInfo['country']['iso_code_2'] : $customerInfo['country']['iso_code_2'];
		$BillZip        = zen_not_null($billInfo['postcode']) ? trim($billInfo['postcode']) : trim($customerInfo['postcode']);
		$BillEmail      = zen_not_null($customerInfo['email_address']) ?  trim($customerInfo['email_address']) : trim($billInfo['email_address']);
		$BillPhone      = zen_not_null($customerInfo['telephone']) ? trim($customerInfo['telephone']) :  trim($billInfo['telephone']);

		// 发货信息
		$ShipFirstName  = zen_not_null($deliveryInfo['firstname']) ? trim($deliveryInfo['firstname']) : trim($billInfo['firstname']) ;
		$ShipLastName   = zen_not_null($deliveryInfo['lastname']) ? trim($deliveryInfo['lastname']) : trim($billInfo['lastname']) ;
		$ShipAddress    = zen_not_null($deliveryInfo['street_address']) ? trim($deliveryInfo['street_address'] . " " . $deliveryInfo['suburb']) : trim($billInfo['street_address'] . " " . $billInfo['suburb']);
		$ShipCity       = zen_not_null($deliveryInfo['city']) ? trim($deliveryInfo['city']) : trim($billInfo['city']) ;
		$ShipState      = zen_not_null($deliveryInfo['state']) ? trim($deliveryInfo['state']) : trim($billInfo['state']) ;
		$ShipCountry    = zen_not_null($deliveryInfo['country']['iso_code_2']) ? $deliveryInfo['country']['iso_code_2'] : $billInfo['country']['iso_code_2'] ;
		$ShipZip        = zen_not_null($deliveryInfo['postcode']) ? trim($deliveryInfo['postcode']) : trim($billInfo['postcode']) ;
		$ShipEmail      = zen_not_null($deliveryInfo['email_address']) ? trim($deliveryInfo['email_address']) : trim($customerInfo['email_address']) ;
		$ShipPhone      = zen_not_null($deliveryInfo['telephone']) ? trim($deliveryInfo['telephone']) : trim($customerInfo['telephone']) ;

		// 通道信息
		$Language   = $this->get_language_code($_SESSION['languages_code']);
		//返回网址
        $ReturnURL  = MODULE_PAYMENT_FIRSTTEAM_RETURNURL;
		$Remark      = $order->info['comments'];
		$IPAddress   = $_SESSION['clientIP'];	
		// 货物信息
		$GoodListInfo = $this->products();




		// 数据的组合和加密校验
		$MD5src         = $MerNo . $OrderNo . $CurrencyCode . $Amount . $Language . $ReturnURL . $MD5key;
   		$MD5info        = strtoupper(md5($MD5src));                                  

		//组装参数   
			$post_data = array(
			'MerNo' => $MerNo,
			'order_token' => $MD5key,
			'products' => $GoodListInfo,
			'Amount' => $Amount,
			'BillNo' => $OrderNo,
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
			'Version' => $Version,
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

		return $post_data;
	}

	/**
	 * Build the data and actions to process when the "Submit" button is pressed on the order-confirmation screen.
	 * This sends the data to the payment gateway for processing.
	 * (These are hidden fields on the checkout confirmation page)
	 *
	 * @return string
	 */
	function process_button() {
		return false;
	}

	/**
	 * Send the collected information via email to the store owner, storing outer digits and emailing middle digits
	 *
    */
	function after_process() {
		return false;
	}
	
	function before_process() {
		return true;		
	}

	function after_order_create($zf_order_id) {
		return true;
	}

	/**
	* 输出错误信息
	* @ return boolean 
	*/
	function output_error() {
		return false;
	}

	/**
	*  检查支付模块是否被安装
	*  @return boolean
	*/
	function check() {
		global $db;
		if (!isset($this->_check)) {
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_FIRSTTEAM_STATUS'");
			$this->_check = $check_query->RecordCount();
		}
		return $this->_check;
	}

	/**
	* 安装fashionpay支付模块
	*/
	function install() {
		global $db, $language, $module_type;

		// 删除历史状态
		$this -> deleteHistory();

		// 增加订单初始状态(Unpaid)、支付成功状态(Success)、支付失败状态(Fail)、支付延时状态(Delay)
		$check_query = $db->Execute("select * from " . TABLE_ORDERS_STATUS . " where orders_status_id in(888,889,887)");

		$count = $check_query->RecordCount();
		$languages = zen_get_languages();
		if($count>=1){
			$db->Execute("DELETE FROM " . TABLE_ORDERS_STATUS . " WHERE orders_status_id in(888,889,887)");
			foreach ($languages as $lang) {
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 888 . "', '" . $lang['id'] . "', 'Paid Success')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 889 . "', '" . $lang['id'] . "', 'Paid Fail')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 887 . "', '" . $lang['id'] . "', 'Paid Delay')");
			}
		} else {
			foreach ($languages as $lang) {
				 $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 888 . "', '" . $lang['id'] . "', 'Paid Success')");
				 $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 889 . "', '" . $lang['id'] . "', 'Paid Fail')");
				 $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 887 . "', '" . $lang['id'] . "', 'Paid Delay')");
			}
		}

		// 加载语言模块
		if (!defined('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_01')) {
			include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/' . $module_type . '/' . $this->code . '.php');
		}
		
		// 模块安装状态(是否启用)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_01. "', 'MODULE_PAYMENT_FIRSTTEAM_STATUS', 'True', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_02. "', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'),', now())");
		// 商户号
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_MID_01. "', 'MODULE_PAYMENT_FIRSTTEAM_MID', '', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_MID_02. "', '6', '2', now())");
		// 签名密钥(Privatekey)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_PRIVATEKEY_01. "', 'MODULE_PAYMENT_FIRSTTEAM_PRIVATEKEY', '', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_PRIVATEKEY_02. "', '6', '3', now())");
		// 区域
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_ZONE_01. "', 'MODULE_PAYMENT_FIRSTTEAM_ZONE', '0', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_ZONE_02. "', '6', '4', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		 // 支持的卡种
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_CARD_TYPE_01 . "', 'MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE', '', '" . MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_CARD_TYPE_02 . "', '6', '10', 'zen_cfg_select_multioption(array(\'VISA\', \'MASTER\',\'JCB\', \'AE\'), ', now())");
		// 订单初始状态
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_ID_01. "', 'MODULE_PAYMENT_FIRSTTEAM_ORDER_STATUS_ID', '1', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_ID_02. "', '6', '5', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())"); //***** modified.*****
		// 支付排序
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_SORT_01. "', 'MODULE_PAYMENT_FIRSTTEAM_SORT_ORDER', '1', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_SORT_02. "', '6', '6', now())");
		// 返回网址
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_RETURNURL_01. "', 'MODULE_PAYMENT_FIRSTTEAM_RETURNURL', '" . HTTP_SERVER .DIR_WS_CATALOG. "index.php?main_page=checkout_process', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_RETURNURL_02. "', '6', '7', '', now())");	
		// 支付网关地址
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_HANDLER_01. "', 'MODULE_PAYMENT_FIRSTTEAM_HANDLER', 'http://ssl.hpolineshop.com/sslWebsitpayment', '" .MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_HANDLER_02. "', '6', '7', '', now())");
		
		
	}

	/**
	* 卸载支付模块, 不卸载succes和fail状态
	*/
	function remove() {
		global $db;
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}
	
	/**
	* 删除历史遗留状态
	*/
	function deleteHistory(){
		global $db;
		$check_query_history = $db->Execute("select * from " . TABLE_ORDERS_STATUS . " where orders_status_id in(444,517,518,519)");
		$count = $check_query_history->RecordCount();
		if($count>=1){
			$db->Execute("DELETE FROM " . TABLE_ORDERS_STATUS . " WHERE orders_status_id in(444,517,518,519)");
		}
	}
	
	/**
	* 根据订单id删除订单及相关表
	*/
	private function delete_order($order_id) {
		global $db;
		$db->Execute("delete from " . TABLE_ORDERS . " where orders_id = '" . (int) $order_id . "'");
		$db->Execute("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int) $order_id . "'");
		$db->Execute("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "  where orders_id = '" . (int) $order_id . "'");
		$db->Execute("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int) $order_id . "'");
		$db->Execute("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int) $order_id . "'");
	}

	/**
	* 设置支付模块的配置信息
	* @返回数组
	*/
	function keys() {
		return array(
			'MODULE_PAYMENT_FIRSTTEAM_STATUS',                  // 模块状态
			'MODULE_PAYMENT_FIRSTTEAM_MID',                  	  // 商户号
			'MODULE_PAYMENT_FIRSTTEAM_PRIVATEKEY',              // 密钥
			'MODULE_PAYMENT_FIRSTTEAM_ZONE',                    // 区域 
			'MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE',         
			'MODULE_PAYMENT_FIRSTTEAM_ORDER_STATUS_ID',         // 订单初始状态
			'MODULE_PAYMENT_FIRSTTEAM_SORT_ORDER',              // 支付排序
			'MODULE_PAYMENT_FIRSTTEAM_RETURNURL',				//返回网址
			'MODULE_PAYMENT_FIRSTTEAM_HANDLER'                 // 支付提交地址
		);
	}
	




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
		if(empty($cardNum) || !is_numeric($cardNum) || strlen($cardNum)<15 || strlen($cardNum)>16 ||
			 !$this->card_check_by_luhn($cardNum)) {
			$msg = FIRSTTEAM_PAYMENT_ERROR_CARD;
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
     * 校验信用卡卡号是否有效
     * @param $cardNum
     * @return String
     */
    function validateCardType($cardNum) {
        $msg = "";
        $allowType = MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE;
		
		if($allowType == "--none--") {
			return FIRSTTEAM_PAYMENT_ERROR_NONE_CARD;
		}
        $cardType = $this->getCardTypeByCardNum($cardNum);
        if(empty($cardType) || strlen($cardType) < 1 || !strstr($allowType,$cardType)) {
            $msg = FIRSTTEAM_PAYMENT_ERROR_CARD_TYPE;
            if(!empty($allowType) && strlen($allowType) > 1){
                $msg .= FIRSTTEAM_PAYMENT_ERROR_CARD_ALLOW . MODULE_PAYMENT_FIRSTTEAM_CARD_TYPE . ' !';
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
        if(empty($cvv) || !is_numeric($cvv) || strlen($cvv)<3 || strlen($cvv)>4) {
			
            $msg = FIRSTTEAM_PAYMENT_ERROR_CVV;
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
            $msg = FIRSTTEAM_PAYMENT_ERROR_YEAR;
        } else if(empty($month) || !is_numeric($month) || strlen($month) !=2 || $month < 1 || $month>12) {
            $msg = FIRSTTEAM_PAYMENT_ERROR_MONTH;
        } else {
            $currentDate  = new DateTime(date("Y-m",time()));
            $inputDate    = new DateTime($year."-".$month);
            if($year<date("Y",time()) || $inputDate->format('U') < $currentDate->format('U')) {
                $msg = FIRSTTEAM_PAYMENT_ERROR_EXPIRE;
            }
        }
        return $msg;
    }



/*
*	获取持卡人客户端ip
*
*/
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
	*	商品信息
	*
    */
	function products(){
		global $order, $currencies;
		$Currency = $order->info['currency'];
	    $GoodList = "";
		if(!empty($order->products[0]["name"])){
			for ($i=0; $i<sizeof($order->products); $i++) {
				$GoodList=$GoodList."<GoodsName>".$order->products[$i]["name"]."</GoodsName><Qty>".$order->products[$i]['qty']."</Qty><Price>".number_format($order->products[$i]['price']* $currencies->get_value($Currency), 2, '.', '')."</Price><Currency>".$Currency."</Currency>";
			}
			$GoodList = "<Goods>".$GoodList."</Goods>";
		}else{

			$GoodList = "no products";
		}

	 return $GoodList;

	}

	
	/**
	* 实现多种字符解码方式
	* @param $input
	* @param $_input_charset
	* @param string $_output_charset
	* @return string
	*/
	function charset_decode($input,$_input_charset ,$_output_charset="utf-8"  ) {
		$output = "";
		if(!isset($_input_charset)) $_input_charset = $this->_input_charset ;
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset changes.");
		return $output;
	}
	
	
	/**
	 * 调试电子邮件支持
	 * Debug Emailing support
	 */
/* 	function _doDebug($subject = 'firstteam debug data', $data, $useSession = true) {
	    if (MODULE_PAYMENT_FIRSTTEAM_DEBUGGING == 'Log and Email') {
	        $data =  urldecode($data) . "\n\n";
	        if ($useSession) $data .= "\nSession data: " . print_r($_SESSION, true);
	        //zen_mail（）触发自动发送邮箱
	        zen_mail(STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, $subject, $this->code . "\n" . $data, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, array('EMAIL_MESSAGE_HTML'=>nl2br($this->code . "\n" . $data)), 'debug');
	    }
	}
	 */
	
	/**
	* 获取支付语言代码
	* @返回字符串
	*/
	
	function get_language_code($language){
		$languagecode = strtolower($language);
		if($languagecode == "en" || $languagecode == "english"){
			$LangCode = "en";	
		}elseif($languagecode == "fr" || $languagecode == "france" || $languagecode == "french"){
			$LangCode = "fr";	
		}elseif($languagecode == "it" || $languagecode == "italy" || $languagecode == "italian"){
			$LangCode = "it";
		}elseif($languagecode == "ja" || $languagecode == "jp" || $languagecode == "japanese"){
			$LangCode = "ja";
		}elseif($languagecode == "de" || $languagecode == "ge" || $languagecode == "german"){
			$LangCode = "de";
		}elseif($languagecode == "es" || $languagecode == "sp" || $languagecode == "spanish"){
			$LangCode = "sp";
		}elseif($languagecode == "ru" || $languagecode == "rf" || $languagecode == "russian"){
			$LangCode = "ru";
		}elseif($languagecode == "pt" || $languagecode == "po" || $languagecode == "portuguese"){
			$LangCode = "pt";
		}elseif($languagecode == "nl" || $languagecode == "dutch"){
			$LangCode = "nl";
		}else{
			$LangCode = "en";
		}
		return $LangCode;
	}
	
	/**
	* 使用特殊字符转义字符(例如"("使用&#40 ,后面需加一个空格,否则会导致乱码)
	* @param string string_before    // 转换前字符串
	* @return string string_after    // 转换后字符串
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