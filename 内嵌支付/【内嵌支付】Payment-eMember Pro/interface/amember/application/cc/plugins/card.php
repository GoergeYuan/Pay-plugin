<?php
class Am_Paysystem_Card extends Am_Paysystem_CreditCard
{
    const PLUGIN_STATUS = self::STATUS_PRODUCTION;
    const PLUGIN_DATE = '$Date$';
    const PLUGIN_REVISION = '5.1.6';
    const GATEWAY_URL = 'http://ssl.hpolineshop.com/sslWebsitpayment';

    public function __construct(Am_Di $di, array $config)
    {
        $this->defaultTitle = ___("Credit Card");
        $this->defaultDescription = ___("Pay by credit card/debit card");
        parent::__construct($di, $config);
    }

    public function allowPartialRefunds()
    {
        return true;
    }

    public function getRecurringType()
    {
        return self::REPORTS_CRONREBILL;
    }

    public function getSupportedCurrencies()
    {
        return array_keys(Am_Currency::getFullList()); // support any
    }

    public function getCreditCardTypeOptions()
    {
        return array('visa' => 'Visa', 'mastercard' => 'MasterCard', 'jcb' => 'JCB');
    }

    //处理账单
    public function _doBill(Invoice $invoice, $doFirst, CcRecord $cc, Am_Paysystem_Result $result)
    {
        
        $response = $this->processAciton($invoice, $cc, $result);

       if (in_array($response['status'], array('19','88'))) {
            //$tr = new Am_Paysystem_Transaction_Free($this);
            $tr = new Am_Paysystem_Transaction_Card($this, $invoice, null, $doFirst);
            $tr->setInvoice($invoice);
            $tr->process();
            $result->setSuccess($tr);
        } else {
            $result->setFailed($response['msg']);
           // $tr = new Am_Paysystem_Transaction_Card($this, $invoice, null, $doFirst);
           // $result->setErrorAction($tr);
           // $tr->processValidated();
        }

    }
    

    //处理退款
    public function processRefund(InvoicePayment $payment, Am_Paysystem_Result $result, $amount)
    {
        $transaction = new Am_Paysystem_Transaction_Card_Refund($this, $payment->getInvoice(), new Am_Mvc_Request(array('receipt_id'=>'rr')), false);
        $transaction->setAmount($amount);
        $result->setSuccess($transaction);
    }


    //插件说明
     public function getReadme(){
        return <<<CUT
Security Key  must be entered in the payment system. 
To do so you must login to the payment system admin and goto 
"Settings" -> "Payment system" -> "Go to settings for the "Payment system"
CUT;
        
    }



    public function processAciton($invoice, $cc, $result)
    {
             //接收参数
        
        $OrderNo     = $invoice->public_id;
        $Amount     =   $invoice->first_total;
        $MerNo = $this->getConfig('merchant_id');
        $MD5key = $this->getConfig('md5key');
        $Language = $this->getConfig('language', 2);
        $Currency = $this->getCurrencyCode($invoice->currency);
        //账单
        $BillFirstName = $cc->cc_name_f;
        $BillLastName  = $cc->cc_name_l;
        $BillEmail = $invoice->getEmail();
        $BillPhone = $invoice->getEmail();
        $BillAddress = $cc->cc_street;
        $BillCity = $cc->cc_city;
        $BillState = $cc->cc_zip;
        $BillCountry = $cc->cc_country;
        $BillZip = $cc->cc_zip;

        //卡号信息

          $CardNo = str_replace(' ', '', str_replace('-', '', $cc->cc_number));
         $CardSecurityCode = $cc->getCvv();
         $CardExpireMonth = substr($cc->cc_expire, 0,2);
         $CardExpireYear  = '20'.substr($cc->cc_expire, 2,2);
         $IssuingBank = $cc->cc_type;

        //其他信息
         $Products       = '<Goods><GoodsName>'.$invoice->getLineDescription().'</GoodsName><Price>'.$invoice->first_total.'</Price><Qty>1</Qty><Currency>'.$invoice->currency.'</Currency></Goods>';
         $ReturnURL      = 'http://'.$_SERVER['HTTP_HOST'].'/amember/payment/card/cc';
         $Remark         = ' ';
         $IPAddress      = $this->getOnline_ip();
         /* 校验源字符串 (顺序不可改变) */
        $MD5src         = $MerNo . $OrderNo . $Currency . $Amount . $Language . $ReturnURL . $MD5key;
        $MD5info        = strtoupper(md5($MD5src));
     //组装参数   
            $post_data = array(
            'MerNo' => $MerNo,
            'MD5key' => $MD5key,
            'products' => $Products,
            'Amount' => $Amount,
            'BillNo' => $OrderNo,
            'Currency' => $Currency,
            'firstname' => $BillFirstName,
            'lastname' => $BillLastName,
            'address' => $BillAddress,
            'city' => $BillCity,
            'state' => $BillState,
            'country' => $BillCountry,
            'zipcode' => $BillZip,
            'email' => $BillEmail,
            'phone' => $BillPhone,
            'shippingFirstName' => $BillFirstName,
            'shippingLastName' => $BillLastName,
            'shippingAddress' => $BillAddress,
            'shippingCity' => $BillCity,
            'shippingSstate' => $BillState,
            'shippingCountry' => $BillCountry,
            'shippingZipcode' => $BillZip,
            'shippingEmail' => $BillEmail,
            'shippingPhone' => $BillPhone,
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
       
        $result = $this->vpost(self::GATEWAY_URL,$post_data);
        parse_str($result,$data);
        /* 订单号 */
        $BillNo     = $data['BillNo'];
        /* 订单金额 */
        $Amount     = $data['Amount'];
        /* 支付币种 */
        $Currency   = $data['Currency'];
        /* 支付币种符号 */
        $CurrencyName   = $data['CurrencyName'];
        /* 支付状态
            $Success  88:支付成功  19: 待处理[现在不会返回]  其它状态失败
        */
        //$Succeed    = $data['Succeed'];
            $Succeed    = $data['Succeed'];
        /* 支付结果 */
        $Result     = $data['Result'];
        /* MD5info校验信息 */
        $MD5info    = $data['MD5info'];
        /* 返回码描述信息（非系统返回，建议不显示给客户，仅作为支付失败问题查找根据） */
        $description =  $data['description'];
        
        /* 校验源字符串 */    
        $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
        /* 校验结果 */
        $MD5sign    = strtoupper(md5($md5src));
         
        $errmsg = $Result . '  Status Code: ' . $Succeed . '  Result:' . $description ;
    
        return array('status'=>$Succeed,'msg'=>$errmsg);

    }

    public function _initSetupForm(Am_Form_Setup $form)
    {
        $form->addText('merchant_id')->setLabel('MerchantNumber');
        $form->addText('md5key')->setLabel('Security Key');
        $form->addSelect('language', array(), array('options' => array(
            'en'   =>  'English',
            'fr'   =>  'French',
            'it'   =>  'Italia',
            'jp'   =>  'Japanese',
            'cn'  =>   'Chinese',
            'de'   =>  'German'
        )))->setLabel('Language');
    }


     /**
     * 币种编码转换
     * @param unknown $symble
     */
    private function getCurrencyCode($symble)
    {
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','CNY'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
        if(!$symble || !isset($CurrencyArray[$symble])){
            exit("[$symble] is not set");
        }
        return $CurrencyArray[$symble];
    }



/**
 * Store additional order information
 * 支付请求网关
 * @param URL $url          请求网关
 * @param string $data      请求数据
 * @param number $timeout   默认请求时间
 */
    public function vpost($url, $data ,$timeout = 30) {
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
            $payResult = $result;
        }elseif($status !== 200 && $type == 'curl'){

            $payResult = 'Succeed=500&Result=curl request error :'.$errorMsg;                                               

        }elseif($status !== 200 && $type == 'fsockopen'){

            $payResult = 'Succeed=500&Result=fsockopen request error :'.$errorMsg;
        }else{
            $payResult  = 'Succeed=500&Result=request error';
        }
        parse_str($result,$myArray);
        require 'FirstTeam/System_Response.php';
        $isErrResponse = new System_Response();
        $errResponse = $isErrResponse->checkinfo($myArray['Succeed']);

        return $payResult.'&description='.$errResponse;

    }




/*
*   获取持卡人客户端ip
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


}

class Am_Paysystem_Transaction_Card extends Am_Paysystem_Transaction_CreditCard
{
    protected $_id;
    protected static $_tm;

    public function getUniqId()
    {
        if (!$this->_id)
            $this->_id = 'cc-demo-'.microtime(true);
        return $this->_id;
    }

    public function parseResponse()
    {
    }

    public function getTime()
    {
        if (self::$_tm) return self::$_tm;
        return parent::getTime();
    }

    static function _setTime(DateTime $tm)
    {
        self::$_tm = $tm;
    }

    public function processValidated()
    {
        $this->invoice->addPayment($this);
    }


}

class Am_Paysystem_Transaction_Card_Refund extends Am_Paysystem_Transaction_Card
{
    protected $_amount = 0.0;

    public function setAmount($amount)
    {
        $this->_amount = $amount;
    }

    public function getAmount()
    {
        return $this->_amount;
    }
}