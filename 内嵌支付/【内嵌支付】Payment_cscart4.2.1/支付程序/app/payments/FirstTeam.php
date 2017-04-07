<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Registry;


if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

/*
[transactionid] => 4358450
[merchantreference] => 640_2
[responsecode] => 00
[responsedescription] => Approved or completed successfully
[retrievalref] => 000345756257
[approvalcode] => 153152
[errorcode] => 0
[errordescription] =>
[amount] => 0.10
[installments] => 0
[cardtype] =>
[langid] => 1
[parameters] =>

«00», «08», «10», «11» or «16».
*/
function getCurrencyByCode($Code)
{
    $Code           = trim($Code);
    $Code           = strtoupper($Code);
    // 速汇通开通币种
    //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗  15:俄罗斯卢布
    $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
    if(!Code || !isset($CurrencyArray[$Code])){
        var_dump($Code);
        exit("Currency [{$Code}] is not set, Please contact The Web Master");

    }
    return $CurrencyArray[$Code];
}

    function vpost($url, $data,$port,$http) { 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_REFERER, $http);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_PORT, $port);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $tmpInfo = curl_exec($curl);

         if(curl_exec($curl) === false)
        {
            $tmpInfo = 'Curl error: ' . curl_error($curl);
        }

        curl_close($curl);

        return $tmpInfo;
  }



/* 获取客户端ip */


function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
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




function func_get_products($items,$currency_code)
{
    if(!$items){
        return 'no products';
    }


    $Products_list = "";
    foreach($items as $key=>$product){
        $Products_list=$Products_list."<GoodsName>".$product['product']."</GoodsName><Qty>".$product['amount'].
                        "</Qty><Price>".$product['price']."</Price><Currency>".$currency_code."</Currency>";
    }
    $Products_list_info = "<Goods>".$Products_list."</Goods>"; 

    return $Products_list_info;
}



function fn_fpay_finish_payment($order_id, $pp_response, $force_notification = array()){
    fn_update_order_payment_info($order_id, $pp_response);
    if ($pp_response['order_status'] == 'N' && !empty($_SESSION['cart']['placement_action']) && $_SESSION['cart']['placement_action'] == 'repay') {
        $pp_response['order_status'] = 'I';
    }
    fn_set_hook('finish_payment', $order_id, $pp_response, $force_notification);

    fn_change_order_status($order_id, $pp_response['order_status'], '', $force_notification);
}


if (defined('PAYMENT_NOTIFICATION')) {

     

         //接收所有提交过来的持卡人信息
  

        $Result = $_REQUEST['result'];
 

        parse_str($Result,$myArray);

         /* MD5私钥 */
        $MD5key     = trim($processor_data['processor_params']['MD5key']);      
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
        $ResultMsg     = $myArray['Result'];
        /* MD5info校验信息 */
        $MD5info    = $myArray['MD5info'];
        
        /* 校验源字符串 */
        $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
        /* 校验结果 */
        $MD5sign    = strtoupper(md5($md5src));


        $order_id   = intval($BillNo);
        $order_info = fn_get_order_info(intval($BillNo));
        $flag = true;
        $keys = array('BillNo', 'Currency', 'Amount', 'Succeed', 'CurrencyName');
        foreach($keys as $key){
            if(!isset($myArray[$key]) or !$myArray[$key]){
                $flag = false;
                break;
            }
        }
        if($flag){

        $Result_error     = 'Thanks for your shop!<br/>';
        $Result_error    .= 'Payment Result :' .$ResultMsg . '( Response Code:'.$Succeed. ')';
        $Result_succeed   = 'Payment Succeed';
        $Result_wait      = 'Payment Wait';

        }else{

            $Result_error = 'Sorry, Request fails';
        }
      
        // $md5Info = '';
        // $keys = array('BillNo', 'Currency', 'Amount', 'Succeed');
        // foreach($keys as $key){
        //     $md5Info .= $_REQUEST[$key];
        // }
        // $md5Info .= trim($processor_data['processor_params']['MD5key']);
        // $md5Info = strtoupper(md5($md5Info));
        
        if($Succeed == '88'){
            $pp_response['order_status']    = 'P';
            $pp_response['reason_text']     = $Result_succeed;
        }elseif($Succeed == '19'){
            $pp_response['order_status']    = 'P';
            $pp_response['reason_text']     = $Result_wait;
        }elseif($Succeed == '0'){
            $pp_response['order_status']    = 'F';
            $pp_response['reason_text']     = $Result_error;
        }else{
            $pp_response['order_status']    = 'D';
            $pp_response['reason_text']    = $Result_error;
        }

        if(isset($_REQUEST['roderid']) && ($Succeed =='88' || $Succeed == '19')){
            $pp_response['transaction_id']  = $_REQUEST['rorderid'];
        }
      
        if (fn_check_payment_script('FirstTeam.php', $order_id)) {
            // exit('success');
            fn_fpay_finish_payment($order_id, $pp_response, false);
        }
        fn_order_placement_routines('route', $order_id, false);

    }else{
   
    $baseUrl        = Registry::get('config.current_location');
    if(empty($baseUrl)){
        $baseUrl    = 'http://'.$_SERVER['HTTP_HOST'];
    }



    $baseUrl        = trim($baseUrl, '/');
	/* $local_currency_code        = trim($_SESSION['settings']['secondary_currencyC']['value']); */
    $local_currency_code        = trim($order_info['secondary_currency']);
    
	 //MD5私钥
     $MD5key    = trim($processor_data['processor_params']['MD5key']);		
     //商户号
     $MerNo     = trim($processor_data['processor_params']['merchant_id']);					
     //[必填]订单号(商户自己产生：要求不重复)
     //$BillNo    = ($order_info['repaid']) ? ($order_id .'_'. $order_info['repaid']) : $order_id;	
     $OrderNo    = $order_id;

     //[必填]交易币种1:代表美金2:欧元4:英镑
     $Currency  = getCurrencyByCode($local_currency_code);
     /* $Currency = $processor_data['params']['currency'];		 */
     //[必填]订单金额
     $Amount    = round($order_info['total'], 2);	
			
     //[必填]语言2:代表英文；1：代表中文     
     $Language  = strtolower($processor_data['processor_params']['language']);
     //[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过   
     $scriptName = Registry::get('config.customer_index');
     $ReturnURL = trim($processor_data['processor_params']['ReturnURL']);
     //$ReturnURL = 'http://www.test.com/result.php';
     //通知地址
     $NoticeURL = $baseUrl . '/' . $scriptName . "?dispatch=payment_notification.notify&payment=FirstTeam";
     /* 产品信息 */
     $Products = func_get_products($order_info['products'], $local_currency_code);
     //[选填]填该网站的域名,方便以后维护和升级。
     $Remark        = Registry::get('config.http_location');  
	 $MerWebsite    = Registry::get('config.http_location');
     //校验源字符串
	 $md5src    = $MerNo.$OrderNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;		
     //MD5检验结果
     $MD5info   = strtoupper(md5($md5src));		

      /* 提交地址 */
     $TransactionURL = trim($processor_data['processor_params']['transactionurl']); 
    


     //账单信息
     $firstname = empty($order_info['b_firstname']) ? $order_info['s_firstname'] : $order_info['b_firstname'];
     $lastname = empty($order_info['b_lastname']) ? $order_info['s_lastname'] : $order_info['b_lastname'];
     $zipcode = empty($order_info['b_zipcode']) ? $order_info['s_zipcode'] : $order_info['b_zipcode'];
     $address = empty($order_info['b_address']) ? $order_info['b_address_2'] : $order_info['b_address'];
     $city = empty($order_info['b_city']) ? $order_info['s_city'] : $order_info['b_city'];
     $state = empty($order_info['b_state_descr']) ? $order_info['s_state_descr'] : $order_info['b_state_descr'];
     //$country = empty($order_info['b_country_descr']) ? $order_info['s_country_descr'] : $order_info['b_country_descr'];
     $country = $order_info['b_country'];
     $email = $order_info['email'];
     $phone = $order_info['phone'];

     //收货信息
     $shippingFirstName = $order_info['s_firstname'];
     $shippingLastName = $order_info['s_lastname'];
     $shippingZipcode = $order_info['s_zipcode'];
     $shippingAddress = empty($order_info['s_address']) ? $order_info['s_address_2'] : $order_info['s_address'];
     $shippingCity = $order_info['s_city'];
     $shippingSstate = $order_info['s_state_descr'];
    // $shippingCountry = $order_info['s_country_descr'];
     $shippingCountry = $order_info['s_country'];

     //卡号信息
     $CardNo = trim($order_info['payment_info']['card_number']);
     $CardExpireMonth = trim($order_info['payment_info']['expiry_month']);
     $CardExpireYear = trim($order_info['payment_info']['expiry_year']);
     $CardSecurityCode = trim($order_info['payment_info']['cvv2']);
     $IssuingBank = getCardTypeByCardNum($CardNo);
     $IPAddress = get_client_ip();


    //组装参数
   
   $post_data = array(
            'MerNo' => $MerNo,
            'BillNo' => $OrderNo,
            'order_token' => $MD5key,
            'product' => $Products_list_info,
            'Amount' => $Amount,
            'Currency' => $Currency,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'zipcode' => $zipcode,
            'email' => $email,
            'phone' => $phone,
            'shippingFirstName' => $shippingFirstName,
            'shippingLastName' => $shippingLastName,
            'shippingAddress' => $shippingAddress,
            'shippingCity' => $shippingCity,
            'shippingSstate' => $shippingSstate,
            'shippingCountry' => $shippingCountry,
            'shippingZipcode' => $shippingZipcode,
            'shippingEmail' => $email,
            'shippingPhone' => $phone,
            'Language' => $Language,
            'ReturnURL' => $ReturnURL,
            'Remark' => $Remark,
            'MD5info' => $MD5info,
            'cardnum' => $CardNo,
            'cvv2' => $CardSecurityCode,
            'month' => $CardExpireMonth,
            'year' => '20'.$CardExpireYear,
            'cardbank' => $IssuingBank,
            'ip' => $IPAddress
        );
        
        $post_data = http_build_query($post_data, '', '&');

        $PayURL = !empty($TransactionURL) ? $TransactionURL :'http://ssl.hpolineshop.com/sslWebsitpayment';
       
        $http_payUrl = strpos($PayURL, 'https');
        $port = '';
        if ($http_payUrl !== false) {
            $port = 443;
        } else {
             $port = 80;
        }
    //获取http://
        if($_SERVER['REQUEST_SCHEME'] == 'https'){

            $http = 'https://'.$_SERVER['HTTP_HOST'];

        }else{

            $http = 'http://'.$_SERVER['HTTP_HOST'];
        }
         
        $result = vpost($PayURL,$post_data,$port,$http);

     echo <<<EOT
<html>
<body>
<form method="post" action="{$NoticeURL}" name="process">
    <input type="hidden" name="result" value="{$result}" />

    
EOT;
$msg = fn_get_lang_var('text_cc_processor_connection');
$msg = str_replace('[processor]', 'Creditcard Payment Gateway Server', $msg);
echo <<<EOT
    </form>
    <script type="text/javascript">
        document.process.submit();
    </script>
    <p><div align=center>{$msg}</div></p>
 </body>
</html>
EOT;

        exit;

}


?>