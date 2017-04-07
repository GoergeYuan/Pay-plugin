<?php
// {{{ requires
require_once realpath(dirname( __FILE__)) . '/LC_Page_Mdl_Fashionpay_Config.php';

/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class LC_Page_Mdl_Fashionpay_Helper extends LC_Page_Ex {

    public function __construct() {
        $this->objFormParam = new SC_FormParam();
    }

    public function init() {
        parent::init();

        // todo これだと問題が発生する..
        //根据语言载入语言模板
        if ($this->arrSiteInfo['base_language'] == 'ja') {
            
            $lang = 'ja';
        } else {
            $lang = 'en';
        }

        $tpl_name = "payment.tpl";
        if ($lang == 'ja') {
            $tpl_dir = '/templates/default/';
        } else {
            $tpl_dir = '/templates/default_en-US/';
        }


        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . $tpl_dir . $tpl_name;
    
        // 月日配列    一个月阵列
        // Year and Month array
        $objDate = new SC_Date();
        $objDate->setEndYear(date("Y") + 10);
        $this->arrYear = $objDate->getZeroYear();
        $this->arrMonth = $objDate->getZeroMonth();
        session_cache_limiter('private-no-expire');
        

        
        // country array
        $countryMap = include  PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . "/copy/Paylib/Country_Map.php";
        $this->arrCountry = $countryMap;
    }
    
    //启动程序
    public function process() {
        $this->action();
        $this->sendResponse();
    }

    public function action() {
        $objSiteSess = new SC_SiteSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCartSess = new SC_CartSession_Ex();

        // 受注情報の取得   订单信息获取
        if (!SC_Utils_Ex::isBlank($_SESSION['order_id'])) {
            $order_id = $_SESSION['order_id'];
        } else if (!SC_Utils_Ex::isBlank($_REQUEST['order_id'])
                     && SC_Utils_Ex::sfIsInt($_REQUEST['order_id'])
                     && $this->lfIsValidToken($_REQUEST['order_id'], $_REQUEST[TRANSACTION_ID_NAME])) {
            $order_id = $_REQUEST['order_id'];
            $_SESSION['order_id'] = $order_id;
        } else {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                t('This procedure is now invalid.'));
        }
        
        //获取订单信息
        $arrOrder = $objPurchase->getOrder($order_id);
        
        //初始化信用卡表单参数
        $this->initParam();  
        //设置表单提交方式
        $this->objFormParam->setParam($_POST);

        switch ($this->getMode()) {
        case 'return':
            // 正常な推移であることを記録しておく
            //保持纪录的是一个正常的过渡   回购物车，订单删除过程
            $objPurchase->rollbackOrder($order_id, ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            exit;
            break;

        case 'next':   //支付页面传输的mode--next
            // 入力値の変換    
            
            //取决于表单的输入参数,递归运行mb_convert_kana功能。
            $this->objFormParam->convParam();
            $this->arrErr = $this->objFormParam->checkError();
            $arrInput = $this->objFormParam->getHashArray();
            
            // 入力エラーなしの場合
            //在没有输入错误的情况下，请求支付并修改状态
            if(count($this->arrErr) == 0) {
                $this->sendPayment($arrOrder);
            }
            break;
        default:
            break;
        }

        $this->arrForm = $this->objFormParam->getFormParamList();
    }
    
    
    /**
     * 币种编码转换
     * @param unknown $symble
     */
    private function getCurrencyCode($symble)
    {
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','CNY'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
        if(!$symble || !isset($CurrencyArray[$symble])){
            trigger_error("[$symble] is not set");
        }
        return $CurrencyArray[$symble];
    }
    
    
    /* パラメータ情報の初期化 */
    /* 初始化信用卡表单参数信息 */
    function initParam() {
        $this->objFormParam->addParam("Card number1", "card_no01", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Card number2", "card_no02", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Card number3", "card_no03", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Card number4", "card_no04", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Card expiration Year", "card_year", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Card expiration Month", "card_month", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("Last Name", "card_name01", 32, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
        $this->objFormParam->addParam("First Name", "card_name02", 32, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
        $this->objFormParam->addParam("Country", "card_country", 32, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Card security code", "security_code", 3, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

    
    // 获取账单参数
    function sendPayment($arrOrder) {
        
      
        // APIのレスポンスも見る  API响应   API响应
        require_once PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . "/copy/Paylib/Http_Request.php";
        //获取信用卡传输信息
        $arrForm = $this->objFormParam->getDbArray();
        //获取订单信息
        $objPurchase = new SC_Helper_Purchase_Ex();
        
        $apiUlr = empty($arrForm['gateway_url']) ? MDL_FASHIONPAY_GATEWAY_RUL : trim($this->arrSiteInfo['gateway_url']);
        $ret = SC_Helper_Request::vpostAPI($apiUlr, $this->sengdBuildParam($arrOrder, $arrForm, $objPurchase), 30);

        parse_str($ret,$myArray);
        
        /* MD5私钥 */
        $MD5key = $this->arrSiteInfo['merchant_md5key'];
        /* 支付流水号 */
        $TradeNo     = $myArray['PaymentOrderNo'];
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
        /* 返回码描述 */
        $Description   = $myArray['description'];
        /* MD5info校验信息 */
        $MD5info    = $myArray['MD5info'];
        	
        /* 校验源字符串 */
        $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
        
        /* 校验结果 */
        $MD5sign    = strtoupper(md5($md5src));
        
        // 受注データに追加
        $sqlval['memo02'] = $Amount;             //TransactionAmount  
        $sqlval['memo03'] = $CurrencyName;       //TransactionCurrency  
        $sqlval['memo04'] = $TradeNo;             //TransactionID  
        $sqlval['memo05'] = $Succeed;             //Return Code  
        $sqlval['memo06'] = $ResultMsg;           //Result     
        $sqlval['memo07'] = $Description;         //Description  
            
        if(in_array($Succeed, array('19','88')) && $MD5sign == $MD5info){

            $order_status = $this->getOrderStatus($this->arrSiteInfo['succee_order']);
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            $objPurchase->sendOrderMail($arrOrder['order_id']);
            SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        }elseif($Succeed == '500'){
            
            $order_status = $this->getOrderStatus($this->arrSiteInfo['new_order']);
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            // エラーページ表示
            //错误页面显示
            $message_log = "[Mcppayment Request Code] " . $Succeed . " [Payment Result] " . $ResultMsg . " [Description] ". $Description;
            $message  = $this->buildResultHtml($Succeed, $ResultMsg, $Description);
            GC_Utils_Ex::gfPrintLog($message_log, ERROR_LOG_REALFILE);   //logs/error.log
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', true, $message); // show error page
        }else{
            
            $order_status = $this->getOrderStatus($this->arrSiteInfo['fail_order']);
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            // エラーページ表示
            //错误页面显示
            $message_log = "[BillNo]" .$BillNo . "[Mcppayment Request Code] " . $Succeed . " [Payment Result] " . $ResultMsg . " [Description] ". $Description;
            $message = $this->buildResultHtml($Succeed, $ResultMsg, $Description);
            GC_Utils_Ex::gfPrintLog($message_log, ERROR_LOG_REALFILE);   //logs/error.log
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', true, $message); // show error page
        } 
   

    }
    
    
    /**
     * 受注がダウンロード商品を含むかどうか
     * 具体它返次模型板通道径到错误页面。
     * @param integer $order_id 受注ID
     * @return boolean ダウンロード商品を含む場合 true
     */
    function hasDownload($order_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrderDetails = $objPurchase->getOrderDetail($order_id, false);
        foreach ($arrOrderDetails as $detail) {
            if ($detail['product_type_id'] == PRODUCT_TYPE_DOWNLOAD) {
                return true;
            }
        }
        return false;
    }
    
    
    /**
     * Payment Info
     * @param unknown $arrOrder
     */
    function sengdBuildParam($arrOrder, $arrForm, $objPurchase)
    {

        $arrOrderTemp = $objPurchase->getOrderTempByOrderId($arrOrder['order_id']);
        $session = unserialize($arrOrderTemp['session']);
        
        
        /* 商户信息 */
        $MerNo         = $this->arrSiteInfo['merchant_id'];
        $MD5key        = $this->arrSiteInfo['merchant_md5key'];
        $Amount        = $arrOrder['payment_total']; // 金額 getCurrencyCode
        $CurrencyCode  = $this->getCurrencyCode($this->arrSiteInfo['base_currency']);
        $OrderNo       = $arrOrder['order_id'];
         
        /* 收货信息 */
        if(!$this->hasDownload($_SESSION['order_id'])){
            $arrShipping = $objPurchase->getShippings($_SESSION['order_id']);
        
            $min = min(array_keys($arrShipping));
            // ダウンロード商品ではない場合, 配列の最初の配送先でPayPal会員登録の初期値を設定
            // 如果下载不是商品，设置贝会员注册的初始值在所述阵列的所述第一目的地
            $arrAddr01 = explode(',', $arrShipping[$min]['shipping_addr01']); // XXX 単純に , で分割できない...
            $ShipFirstName = $arrShipping[$min]['shipping_name01'];
            $ShipLastName  = $arrShipping[$min]['shipping_name02'];
            $ShipAddress = $arrShipping[$min]['shipping_addr02'];
            $ShipCity      = empty($arrAddr01[1]) ? trim($arrAddr01[0]) : trim($arrAddr01[1]);
            $ShipState     = trim($arrAddr01[0]);
            $ShipCountry   = $arrForm['card_country'];
            $ShipZip       = $arrShipping[$min]['shipping_zip01'].$arrShipping[$min]['shipping_zip02'];
            $ShipEmail     = $arrOrder['order_email'];
            $ShipPhone     = $arrShipping[$min]['shipping_tel01'] . '-'
                . $arrShipping[$min]['shipping_tel02'] . '-'
                    . $arrShipping[$min]['shipping_tel03'];
        
        }else{
        
            $arrAddr01 = explode(',', $arrOrder['order_addr01']); // XXX 単純に , で分割できない...
            $ShipFirstName = $arrOrder['order_name01'];
            $ShipLastName  = $arrOrder['order_name02'];
            $ShipAddress   = $arrOrder['order_addr02'];
            $ShipCity      = empty($BillarrAddr[1]) ? trim($BillarrAddr[0]) : trim($BillarrAddr[1]);
            $ShipState     = trim($BillarrAddr[0]);
            $ShipCountry   = $arrForm['card_country'];
            $ShipZip       = $arrOrder['order_zip01'].$arrOrder['order_zip02'];
            $ShipEmail     = $arrOrder['order_email'];
            $ShipPhone     = $arrOrder['order_tel01'] . '-'
                . $arrOrder['order_tel02'] . '-'
                    . $arrOrder['order_tel03'];
        }
        
        /* 账单信息 */
        $BillarrAddr = explode(',', $arrOrder['order_addr01']); // XXX 単純に , で分割できない...
        $BillFirstName = $arrOrder['order_name01'];
        $BillLastName  = $arrOrder['order_name02'];
        $BillAddress   = $arrOrder['order_addr02'];
        $BillCity      = empty($BillarrAddr[1]) ? trim($BillarrAddr[0]) : trim($BillarrAddr[1]);
        $BillState     = trim($BillarrAddr[0]);
        $BillCountry   = $arrForm['card_country'];
        $BillZip       = $arrOrder['order_zip01'].$arrOrder['order_zip02'];
        $BillEmail     = $arrOrder['order_email'];
        $BillPhone     = $arrOrder['order_tel01'] . '-'
            . $arrOrder['order_tel02'] . '-'
                . $arrOrder['order_tel03'];
        
        /* 信息用卡信息 */
        $CardNo        = $arrForm['card_no01'].$arrForm['card_no02'].$arrForm['card_no03'].$arrForm['card_no04'];
        $CardSecurityCode = $arrForm['security_code'];
        $CardExpireMonth  = $arrForm['card_month'];
        $CardExpireYear   = '20'.$arrForm['card_year'];
        $IssuingBank      = '';

        /* 其他信息 */
        $IPAddress = $this->get_client_ip();
        //$Language  = $this->Language_code();
        $Language  = $this->arrSiteInfo['base_language'];
        $ReturnURL = $this->arrSiteInfo['return_url'];
        $GoodListInfo  = $this->getProducts($arrShipping[$min]['shipment_item'], $this->arrSiteInfo['base_currency']);
        $Remark    = $_SERVER['HTTP_HOST'];
         
        //MD5数字加密
        $MD5src         = $MerNo . $OrderNo . $CurrencyCode . $Amount . $Language . $ReturnURL . $MD5key;
        $MD5info        = strtoupper(md5($MD5src));


        $param = array(
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
            'Remark' => $Remark,
            'MD5info' => $MD5info,
            'cardnum' => $CardNo,
            'cvv2' => $CardSecurityCode,
            'month' => $CardExpireMonth,
            'year' => $CardExpireYear,
            'cardbank' => $IssuingBank,
            'ip' => $IPAddress
        );

        return http_build_query($param, '', '&');
    }
    
    
    function buildResultHtml($code, $result, $description)
    {  
        
        if ($this->arrSiteInfo['base_language'] == 'ja') {
          
            $title = '支払いの不履行';
            $codeTitle = 'エラーコード：';
            $resultTitle = '結果： ';
            $desTitle = '説明： ';
            $helpMsgS = 'カード発行者に連絡してください。';
        } else {
            $title = 'Payment failed';
            $codeTitle = 'Error code: ';
            $resultTitle = 'Result: ';
            $desTitle = 'Description：';
            $helpMsg = 'Please contact your card issuer.';
        }
        
        $html = <<<EOF
    
            <font color="red">{$title}</font><br/>
            <label>{$codeTitle}</label>{$code}<br/>
            <label>{$resultTitle}</label>{$result}<br/>
            <label>{$desTitle}</label>{$description}<br/><br/>
            <label>{$helpMsg}</label>
            
      
EOF;
            return $html;
    }
    
    
    /**
     * get products 
     * @param unknown $shipment_item
     * @param unknown $CurrencyName
     * @return string
     */
    function getProducts($shipment_item, $CurrencyName)
    {

        $goodlist = "";
        if ( count($shipment_item) > 0 ) {
            foreach ( $shipment_item as $item ) {
                if ( $item['quantity'] ) {
                    $goodlist = $goodlist."<GoodsName>".$item['product_name'] . "</GoodsName><Qty>".$item['quantity']."</Qty><Price>" .number_format($item['price']/$item['quantity'], 2, '.', '')."</Price><Currency>".$CurrencyName."</Currency>";
                }
            }
        }
        $goodlistinfo = "<Goods>".$goodlist."</Goods>";
        
        
        return $goodlistinfo;
    }
    
    
    //获取浏览器语言
    function Language_code(){
    
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4); //只取前4位，这样只判断最优先的语言。如果取前5位，可能出现en,zh的情况，影响判断。
        if (preg_match("/zh-c/i", $lang))
            return "cn";
            else if (preg_match("/zh/i", $lang))
            return "tw";
            else if (preg_match("/en/i", $lang))
            return "en";
            else if (preg_match("/fr/i", $lang))
            return "fr";
            else if (preg_match("/de/i", $lang))
            return "de";
            else if (preg_match("/jp/i", $lang))
            return "ja";
            else if (preg_match("/ko/i", $lang))
            return "ko";
            else if (preg_match("/es/i", $lang))
            return "es";
            else if (preg_match("/sv/i", $lang))
            return "sv";
            else return "en";
    }
    
    
    /*
     *
     *	获取ip
     *
     */
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
     * get order status constant
     * @param unknown $statusCode
     */
    private function getOrderStatus($statusCode)
    {
        $arrStatus = array(
            '1'=>ORDER_NEW,         /** 新規注文 */
            '2'=>ORDER_PAY_WAIT,    /** 入金待ち */
            '3'=>ORDER_CANCEL,      /** キャンセル */
            '4'=>ORDER_BACK_ORDER,  /** 取り寄せ中 */
            '5'=>ORDER_DELIV,       /** 発送済み */
            '6'=>ORDER_PRE_END,      /** 入金済み */
            '7'=>ORDER_PENDING      /** 決済処理中 */
        );
        
        return $arrStatus[$statusCode];
    }
    
    
}
