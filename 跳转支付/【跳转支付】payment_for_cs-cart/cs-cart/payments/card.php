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


//
// $Id: winbank.php 10229 2010-07-27 14:21:39Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

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
    $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13');
    if(!$Code || !isset($CurrencyArray[$Code])){
        exit("Currency [{$Code}] is not set, Please contact The Web Master");
    }
    return $CurrencyArray[$Code];
}
function func_get_products($items, $symble='')
{
    if(!$items){
        return '';
    }
    $exp        = '###';
    $exa        = '@@@';
    $ProList    = array();
    foreach($items as $key=>$value){
        $pTempList  = array(
                'name'  => $value['product'],
                'qty'   => $value['amount'],
                'price' => $value['price'] . $symble
            );
        if(isset($value['product_options']) && $value['product_options']){
            $attrList[]     = array();
            foreach($value['product_options'] as $k=>$v){
                $attrList[] = $v['option_name'].':'.$v['variant_name'];
            }
            if($attList){
                $pTempList['attr']  = implode('+', $attrList);
            }
        }
        $ProList[]  = implode($exa, $pTempList);
    }
    if($ProList){
        return implode($exp, $ProList);
    }else{
        return null;
    }
}


/*
*   获取商品信息
*
*/
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

    return htmlspecialchars($Products_list_info, ENT_QUOTES, 'UTF-8');
}


if (defined('PAYMENT_NOTIFICATION')) {

	$BillNo     = $_REQUEST['BillNo'];
    $order_id   = intval($BillNo);
    $order_info = fn_get_order_info(intval($BillNo));
    $Succeed    = $_REQUEST['Succeed'];
    $Result     = 'Payment Result :' .$_REQUEST['Result'] . '( Response Code:'.$_REQUEST['Succeed'] . ')';
    $pp_response['reason_text']     = $Result;
    if($Succeed == '88'){
        $pp_response['order_status']    = 'P';
    }elseif($Succeed == '19'){
        $pp_response['order_status']    = 'P';
    }elseif($Succeed == '0'){
        $pp_response['order_status']    = 'F';
    }else{
        $pp_response['order_status']    = 'D';
    }
    if($_REQUEST['roderid'] && ($Succeed =='88' || $Succeed == '19' || $Succeed=='0')){
        $pp_response['transaction_id']  = $_REQUEST['rorderid'];
    }
  
	if (fn_check_payment_script('card.php', $order_id)) {
		fn_finish_payment($order_id, $pp_response, false);
	}
    fn_order_placement_routines($order_id);
} else {
    $baseUrl        = Registry::get('config.current_location');
    if(empty($baseUrl)){
        $baseUrl    = 'http://'.$_SERVER['HTTP_HOST'];
    }
    $baseUrl        = trim($baseUrl, '/');
	/* $local_currency_code        = trim($_SESSION['settings']['secondary_currencyC']['value']); */
    $local_currency_code        = trim($order_info['secondary_currency']);
	 //MD5私钥
     $MD5key    = trim($processor_data['params']['MD5key']);		
     //商户号
     $MerNo     = trim($processor_data['params']['merchant_id']);					
     //[必填]订单号(商户自己产生：要求不重复)
     //$BillNo    = ($order_info['repaid']) ? ($order_id .'_'. $order_info['repaid']) : $order_id;	
     $BillNo    = $order_id;
     //[必填]交易币种1:代表美金2:欧元4:英镑
     $Currency  = getCurrencyByCode($local_currency_code);
     /* $Currency = $processor_data['params']['currency'];		 */
     //[必填]订单金额
     $Amount    = round($order_info['total'], 2);				
     //[必填]语言2:代表英文；1：代表中文     
     $Language  = strtolower($processor_data['params']['language']);
     //[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过   
     $ReturnURL = $baseUrl . "/$index_script?dispatch=payment_notification.notify&payment=card"; 	
     $ReturnURL = $baseUrl . "/return.php";
     $ReturnURL = trim($processor_data['params']['ReturnURL']);
     //通知地址
     $NoticeURL = $baseUrl . "/$index_script?dispatch=payment_notification.notify&payment=card";
     /* 产品信息 */
     $Products  = func_get_products($order_info['items'], $local_currency_code);
     //[选填]填该网站的域名,方便以后维护和升级。
     $Remark        = Registry::get('config.http_location');  
	 $MerWebsite    = Registry::get('config.http_location');
     //校验源字符串
	 $md5src    = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;		
     //MD5检验结果
     $MD5info   = strtoupper(md5($md5src));		
     /* 提交地址 */
     $TransactionURL    = trim($processor_data['params']['transactionurl']);

echo <<<EOT
<html>
<body>
<form method="post" action="{$TransactionURL}" name="process">
	<input type="hidden" name="MerNo" value="{$MerNo}" />
	<input type="hidden" name="BillNo" value="{$BillNo}" />
	<input type="hidden" name="Currency" value="{$Currency}" />
	<input type="hidden" name="Amount" value="{$Amount}" />
	<input type="hidden" name="Language" value="{$Language}" />
	<input type="hidden" name="ReturnURL" value="{$ReturnURL}" />
	<input type="hidden" name="Remark" value="{$Remark}" />
	<input type="hidden" name="MerWebsite" value="{$MerWebsite}" />
	<input type="hidden" name="MD5info" value="{$MD5info}" />
	<input type="hidden" name="DeliveryFirstName" value="{$order_info['s_firstname']}" />
	<input type="hidden" name="DeliveryLastName" value="{$order_info['s_lastname']}" />
	<input type="hidden" name="DeliveryEmail" value="{$order_info['email']}" />
	<input type="hidden" name="DeliveryPhone" value="{$order_info['phone']}" />
	<input type="hidden" name="DeliveryZipCode" value="{$order_info['s_zipcode']}" />
	<input type="hidden" name="DeliveryAddress" value="{$order_info['s_address']}" />
	<input type="hidden" name="DeliveryCity" value="{$order_info['s_city']}" />
	<input type="hidden" name="DeliveryState" value="{$order_info['s_state_descr']}" />
	<input type="hidden" name="DeliveryCountry" value="{$order_info['s_country_descr']}" />
	<input type="hidden" name="FirstName" value="{$order_info['b_firstname']}" />
	<input type="hidden" name="LastName" value="{$order_info['b_lastname']}" />
	<input type="hidden" name="Email" value="{$order_info['email']}" />
	<input type="hidden" name="Phone" value="{$order_info['phone']}" />
	<input type="hidden" name="ZipCode" value="{$order_info['b_zipcode']}" />
	<input type="hidden" name="Address" value="{$order_info['b_address']}" />
	<input type="hidden" name="City" value="{$order_info['b_city']}" />
	<input type="hidden" name="State" value="{$order_info['b_state_descr']}" />
	<input type="hidden" name="Country" value="{$order_info['b_country_descr']}" />
    <input type="hidden" name="NoticeURL" value="{$NoticeURL}"/>
    <input type="hidden" name="Products" value="{$Products}" />
EOT;
$msg = fn_get_lang_var('text_cc_processor_connection');
// $msg = str_replace('[processor]', 'CreditCard server', $msg);
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