<?php

/**
 * ECSHOP CreditCard 支付插件
 * ============================================================================
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用
 * ============================================================================
 * $Author: admin_zhang
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/card.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'card_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'CreditCard';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.fashionpay.com';

    /* 版本号 */
    $modules[$i]['version'] = '2.0.4';
	$url        = 'http://' . $_SERVER['HTTP_HOST'] . '/submitOrder.php';
	$returnurl  = 'http://'.$_SERVER ['HTTP_HOST'].'/respond.php?code=card';
    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'MerNo', 'type' => 'text', 'value' => '10003'),
        array('name' => 'MD5key', 'type' => 'text', 'value' => '12345678'),
        array('name' => 'Currency', 'type' => 'select', 'value' => '1'),
		//array('name' => 'Rate', 'type' => 'text', 'value' => '1.00000'),
        array('name' => 'Language', 'type' => 'select', 'value' => 'auto'),
		array('name' => 'TransactionURL', 'type' => 'text', 'value' => "$url"),
		array('name' => 'Returnurl', 'type' => 'text', 'value' => "$returnurl"),
        array('name' => 'Paylog', 'type' => 'select', 'value' => '0'));

    return;
}

/**
 * 类
 */
class card
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    private $pay_button_text;
    
    function card()
    {
        //error_reporting(0);
        $this->pay_button_text  = 'Creditcard Payment Online';
    }
 	
    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
         $MD5key		= $payment['MD5key'];                             //MD5私钥
         $MerNo			= trim($payment['MerNo']);                               //商户号
         $BillNo		= trim($order['order_sn']);      //订单号
         $Currency		= trim($payment['Currency']);                         //币种
         // $rate			= $payment['Rate'];
		 $Amount        = round($order['order_amount'], 2);
         /* $Amount		= round($order['order_amount']*$rate,2); */
		// $Amount = number_format($DisAmount * $rate, 2, '.', '');
         $Language		= trim($payment['Language']);                         //语言
         if($Language == 'auto'){
			$Language	= $this->getRequestBrowserLang();
		 }
		 $ReturnURL     = $payment['Returnurl'];      //返回地址
         $Remark		= 'http://'.$_SERVER ['HTTP_HOST']; //备注
		 $MerWebsite	= 'http://'.$_SERVER['HTTP_HOST'];  
         $NoticeURL     = 'http://'.$_SERVER['HTTP_HOST'].'/includes/modules/card.php';
         $md5src = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key; //校验源字符串
         $MD5info = strtoupper(md5($md5src));                                     //MD5检验结

         //是否开启支付日志
         $PayLog        = $payment['Paylog'];
        /* 产品信息 */
        $cart_goods    = $this->get_goodscart($order["order_id"]);

        //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗 15:俄罗斯卢布
        $CurrencyArray  = array('2'=>'EUR', '1'=>'USD', '6'=>'JPY', '4'=>'GBP', '7'=>'AUD', '11'=>'CAD', '8'=>'NOK','3'=>'CNY','12'=>'DKK','5'=>'HKD','13'=>'SEK', '14' => 'TWD','15'=>'RUB');

        foreach($cart_goods as $key=>$value){
            $GoodListInfo_1 .='<GoodsName>'.$value['goods_name'].'</GoodsName><Qty>'.$value['goods_number']."</Qty><Price>".$value['goods_price']."</Price><Currency>".$CurrencyArray[$Currency]."</Currency>";
        }
        $GoodListInfo = "<Goods>".$GoodListInfo_1."</Goods>";
 
		/*
		 *以下九个参数为收货人信息,能收集的数据请尽力收集，,实在收集不到的参数---请赋空值,谢谢
		 */
         /* [选填]---------------收货人的姓 */
		 $DeliveryFirstName		= trim($order['consignee']);
         /* [选填]---------------收货人的名 */
		 $DeliveryLastName		= trim($order['consignee']);
         /* [选填]--------------收货人的Email */
		 $DeliveryEmail			=trim($order['email']);
         /* [选填]--------------收货人的固定电话 */
		 $DeliveryPhone			= !empty($order['tel']) ? trim($order['tel']) : trim($order["mobile"]);
         /* [选填]--------------收货人的邮编 */
		 $DeliveryZipCode		= trim($order['zipcode']);
         /* [选填]-------------收货人具体地址 */
		 $DeliveryAddress		= $order['address'];
         /* [选填]-------------收货人城市 */
         $BillingCity          = trim($this->get_regions2($order['city']));
         $DeliveryCity         = empty($BillingCity) ? (trim($order['city'])) : $BillingCity;
         /* [选填]-------------收货人州省 */
         $BillingState         = trim($this->get_regions2($order['province']));
         $DeliveryState        = empty($BillingState) ? (trim($order['province'])) : $BillingState;
         /* [选填]-------------收货人国家 */
         $DeliveryCountry      = $this->get_regions2($order['country']);
        // $DeliveryCountry =  $this->get_regions2($order['country']['iso_code_2']);   获取国家简写
         
        $sql    = 'SELECT * FROM ' . $GLOBALS['ecs']->table('region') . ' WHERE region_id='. intval($order['city']);
        $row        = $GLOBALS['db']->getRow($sql);
        if($row){
            $DeliveryCity       = $row['region_name'];
        }
		 /* 获取省或者州名称 */
        $sql    = 'SELECT * FROM ' . $GLOBALS['ecs']->table('region') . ' WHERE region_id='. intval($order['province']);
        $row        = $GLOBALS['db']->getRow($sql);
        if($row){
            $DeliveryState  = $row['region_name'];
        }
		 /* 获取省或者州名称 */
        $sql    = 'SELECT * FROM ' . $GLOBALS['ecs']->table('region') . ' WHERE region_id='. intval($order['country']);
        $row        = $GLOBALS['db']->getRow($sql);
        if($row){
            $DeliveryCountry    = $row['region_name'];
        }
        
        $button =  '<form action="'.$payment['TransactionURL'].'" method="post" name="E_FORM">'.
                    "  <input type='hidden' name='MerNo' value='". $MerNo ."'>".
                    "  <input type='hidden' name='Currency' value='". $Currency ."'>".
                    "  <input type='hidden' name='BillNo' value='". $BillNo ."'>".
                    "  <input type='hidden' name='Amount' value='". $Amount ."'>".
                //    "  <input type='hidden' name='DisAmount' value='". $DisAmount ."'>".
                    "  <input type='hidden' name='ReturnURL' value='". $ReturnURL ."'>".
                    "  <input type='hidden' name='Language' value='". $Language ."'>".
                    "  <input type='hidden' name='MD5info' value='". $MD5info ."'>".
                    "  <input type='hidden' name='Remark' value='". $Remark ."'>".
					"  <input type='hidden' name='MerWebsite' value='".$MerWebsite."'>".
					"  <input type='hidden' name='DeliveryFirstName' value='". $DeliveryFirstName ."'>".
					"  <input type='hidden' name='DeliveryLastName' value='". $DeliveryLastName ."'>".
					"  <input type='hidden' name='DeliveryEmail' value='". $DeliveryEmail ."'>".
					"  <input type='hidden' name='DeliveryPhone' value='". $DeliveryPhone ."'>".
					"  <input type='hidden' name='DeliveryZipCode' value='". $DeliveryZipCode ."'>".
					"  <input type='hidden' name='DeliveryAddress' value='". $DeliveryAddress ."'>".
					"  <input type='hidden' name='DeliveryCity' value='". $DeliveryCity ."'>".
					"  <input type='hidden' name='DeliveryState' value='". $DeliveryState ."'>".
					"  <input type='hidden' name='DeliveryCountry' value='". $DeliveryCountry ."'>".

                    "  <input type='hidden' name='FirstName' value='". $DeliveryFirstName ."'>".
                    "  <input type='hidden' name='LastName' value='". $DeliveryLastName ."'>".
                    "  <input type='hidden' name='Email' value='". $DeliveryEmail ."'>".
                    "  <input type='hidden' name='Phone' value='". $DeliveryPhone ."'>".
                    "  <input type='hidden' name='ZipCode' value='". $DeliveryZipCode ."'>".
                    "  <input type='hidden' name='Address' value='". $DeliveryAddress ."'>".
                    "  <input type='hidden' name='City' value='". $DeliveryCity ."'>".
                    "  <input type='hidden' name='State' value='". $DeliveryState ."'>".
                    "  <input type='hidden' name='Country' value='". $DeliveryCountry ."'>".

                    " <input type='hidden' name='Products' value='".$GoodListInfo."'>".
                    " <input type='hidden' name='NoticeURL' value='". $NoticeURL ."'>".
                    " <input type='hidden' name='PayLog' value='". $PayLog ."'>".
                    "  <input type='submit' name='b1' value='" . $this->pay_button_text . "'>".
                    " <h3 align='center'>You will be redirected to creditcard in a few seconds.</h3>".
                    "<script>document.E_FORM.submit();</script> ".
                    "</form>";

        return $button;
    }
   
    private function get_order_id_by_order_sn($order_no)
    {
        $order_id   = get_order_id_by_sn($order_no);
        return intval($order_id);
    }
    /**
     * 响应操作
	 *返回值为：0：失败；1：成功；字符串：正在付款[注意：当该订单状态为'正在付款时，请您务必到我方服务器查看最终的结果，谢谢']
     */
    function respond()
    {
        $payment  = get_payment($_GET['code']);
				
		if(isset($_REQUEST["result"]) && base64_decode($_REQUEST['result']) !== false)
    		{
                parse_str(base64_decode($_REQUEST['result']),$myArr);
    			$BillNo		= $myArr["BillNo"];     //订单号
    	        $Currency	= $myArr["Currency"]; //币种
    	        $Amount		= $myArr["Amount"];     //金额
    	        $Succeed	= $myArr["Succeed"];   //支付状态
    	        // $TradeNo	= $myArr["TradeNo"];   //支付平台流水号
    	        $Result		= $myArr["Result"];     //支付结果
    	        $MD5info	= $myArr["MD5info"];   //取得的MD5校验信息

    	        $MD5key		= $payment['MD5key'];                         //MD5私钥
    	        $md5src		= $BillNo.$Currency.$Amount.$Succeed.$MD5key; //校验源字符串
    	        $md5sign	= strtoupper(md5($md5src));                  //MD5检验结果
    			
    			//$no			= substr($BillNo,2);
    /* 			$zh			= get_order_id_by_sn($BillNo);//substr($BillNo, 14);
    			$zh			= intval(trim($zh)); */
                $log_id         = $this->get_order_id_by_order_no($BillNo);
    			if($MD5info == $md5sign)
    			{
    				if($Succeed == '88'){
    					order_paid($log_id,PS_PAYED);
    					return '1';
    				} else if($Succeed == '19'){
                        order_paid($log_id, PS_PAYING);
                    } else {
                        order_paid($log_id, PS_UNPAYED);
    					return '0';
    				}
    			}else{
    				return '0';
    			}
		}
        
        return FALSE;
	}

     /**
     * 获取区域名称
     * @param    int $region_id  区域id
     * @return   String   
     */
    function get_regions2($region_id) {
        $sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region').
                " WHERE region_id = '$region_id'";
        return $GLOBALS['db']->GetOne($sql);
    }


     /**
     * 获取货物列表
     * @param  int $order_id   订单id
     * @return array   
     */
    function get_goodscart($order_id) {
       $sql = "SELECT goods_name, goods_number,goods_price FROM ".
               $GLOBALS['ecs']->table('order_goods')." WHERE order_id = '$order_id'";
       return $GLOBALS['db']->getAll($sql);   
    } 



    /**
     * 由订单No获取订单id号
     * @param String $order_sn 订单No
     * @return int
     */
    
    function get_order_id_by_order_no($order_sn) {
        $sql ='SELECT log_id  FROM'. $GLOBALS['ecs']->table('order_info').','.$GLOBALS['ecs']->table('pay_log').
        'WHERE '.$GLOBALS['ecs']->table('order_info').".order_sn='$order_sn'"."and".
        $GLOBALS['ecs']->table('order_info').".order_id=".$GLOBALS['ecs']->table('pay_log').".order_id";
        return $GLOBALS['db']->getOne($sql);
    }
    



    //获取浏览器语言
    function getRequestBrowserLang()
    {
 
     $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
     if (preg_match("/zh-c/i", $lang))
         return "zh";
     else if (preg_match("/zh/i", $lang))
         return "zh";
     else if (preg_match("/en/i", $lang))
         return "en";
     else if (preg_match("/fr/i", $lang))
         return "fr";
     else if (preg_match("/de/i", $lang))
         return "de";
     else if (preg_match("/jp/i", $lang))
         return "jp";
     else if (preg_match("/ko/i", $lang))
         return "ko";
     else if (preg_match("/es/i", $lang))
         return "es";
     else if (preg_match("/sv/i", $lang))
         return "sv";
     else if (preg_match("/it/i", $lang))
         return "it";
     else return null;
 
    }

}
?>