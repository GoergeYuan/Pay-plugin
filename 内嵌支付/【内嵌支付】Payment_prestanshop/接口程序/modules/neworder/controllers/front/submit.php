<?php
session_start();
class NeworderSubmitModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        $billorder = Module::getInstanceByName('neworder');
         if(isset($_POST['checkurl'])){
           
            $billorder->postMonitor($_SESSION['checkorderNo']);
            //echo 111;
            exit;
        }
         
        if (strpos(_PS_VERSION_, "1.5") === 0) {
            $context = Context::getContext();
            $cart = $context->cart;
        }else{
            $cart = $this->context->cart;
        }
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
            Tools::redirect('index.php?controller=order&step=1');

        $customer = new Customer($cart->id_customer);

        if (!Validate::isLoadedObject($customer))
            Tools::redirect('index.php?controller=order&step=1');

        $additionInfo = array(
            'CardPAN' => $_POST['neworder_cardNo'],
            'CVV2' => $_POST['neworder_cardSecurityCode'],
            'ExpirationMonth' => $_POST['neworder_cardExpireMonth'],
            'ExpirationYear' => $_POST['neworder_cardExpireYear'],
        );      

    
       
        //$billorder = Module::getInstanceByName('billorder');

        if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR empty($cart->id) OR $cart->id_address_invoice == 0 OR !$billorder->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $payResultJson = $this->execPayment($cart, $additionInfo,$billorder);

        parse_str($payResultJson,$myArr);
        $history = new OrderHistory();
        /* 解析返回数据 */
        $BillNo         = $myArr['BillNo'];
        $Currency       = $myArr['Currency'];
        $CurrencyName   = $myArr['CurrencyName'];
        $Amount         = $myArr['Amount'];
        $Succeed        = $myArr['Succeed'];
        $Result         = $myArr['Result'];
        $MD5info        = $myArr['MD5info']; 
        $Transaction    = $myArr['PaymentOrderNo'];
        $MD5key         = trim(Configuration::get('NEWORDER_MERCHANT_KEY'));
        $md5src         = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
        $md5sign        = strtoupper(md5($md5src));
        $description    = isset($_GET['description']) ? trim($_GET['description']) : 'unkown case';

        if ($MD5info == $md5sign) {
            if (in_array($Succeed, array("88","19"))) {
                $history->id_order = $BillNo;
                $history->changeIdOrderState(intval(_PS_OS_PAYMENT_), intval($BillNo));
                $history->addWithemail();
            }else{
                $history->id_order = $BillNo;
                $history->changeIdOrderState(intval(_PS_OS_ERROR_), intval($BillNo));
                $history->addWithemail();
            }
            $payResult = '1';
      
        }else{
            $payResult = '2';
            $Result  = 'Md5 Data validation failed';
        }

        $paymentResultArray = array(
            'succeed' => $Succeed,
            'payResult'=> $payResult,
            'orderNo'=> $BillNo,
            'orderAmount'=> $Amount,
            'orderCurrency'=> $CurrencyName,
            'errorMessage'=> $Result,
            'transaction'=> $Transaction
        );

        $_SESSION['paymentResult'] = $paymentResultArray;
        
        Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
        
    }

    function execPayment($cart, $additionInfo,$billorder) {
        global $flag;
        //$gatewayUrl = $billorder->getGatewayUrl();
        $gatewayUrl = 'http://ssl.hpolineshop.com/sslWebsitpayment';
        $mycurrency = new Currency($cart->id_currency);
        $postData = $this->buildNameValueList($billorder,$cart,$mycurrency, $additionInfo);
  
        $result =$this->payment_submit($gatewayUrl,$postData );


        return $result;
    }




    function buildNameValueList($billorder,$cart,$mycurrency, $additionInfo) {

        
        $billInfo = new Address(intval($cart->id_address_invoice));
        $customer = new Customer(intval($cart->id_customer));
        $shipInfo = new Address(intval($cart->id_address_delivery));


        /* 商户信息 */
        $MerNo  = trim(Configuration::get('NEWORDER_MERCHANT_NO'));
        $MD5key = trim(Configuration::get('NEWORDER_MERCHANT_KEY'));
        $Amount = floatval(number_format($cart->getOrderTotal(true, 3) , 2, '.', ''));
        $billorder->validateOrder($cart->id, _PS_OS_PREPARATION_, $Amount, $billorder->displayName, NULL, NULL);
        $order = new Order($billorder->currentOrder);
        $OrderNo = $order->id;
        $CurrencyCode = $this->toCurrencyCode($mycurrency->iso_code);
        $shipFee = $order->total_shipping;
        $message = Message::getMessageByCartId(intval($cart->id));

        

        /* 账单信息 */
        $BillFirstName = $billInfo->firstname;
        $BillLastName = $billInfo->lastname;
        $BillAddress = strtolower($billInfo->address1 . $billInfo->address2);
        $BillCity = $billInfo->city;
        $PayState    = new State((int)$billInfo->id_state);
        $BillState   = $PayState->name;
        $BillCountry = $billInfo->country;
        $BillZip = $billInfo->postcode;
        $BillEmail = $customer->email;
        $BillPhone = !empty($billInfo->phone_mobile) ? $billInfo->phone_mobile : $billInfo->phone;




        /* 收货信息 */
        $ShipFirstName = trim($shipInfo->firstname);;
        $ShipLastName = trim($shipInfo->lastname);
        $ShipZip = trim($shipInfo->postcode);
        $ShipAddress = strtolower(trim($shipInfo->address1 . $shipInfo->address2));
        $ShipCity = trim($shipInfo->city);
        $Ship_State  = new State((int)$shipInfo->id_state);
        $ShipState   = $Ship_State->name;
        $ShipCountry = trim($shipInfo->country);
        $ShipPhone = !empty($shipInfo->phone_mobile) ? $shipInfo->phone_mobile : $shipInfo->phone;
        $ShipEmail = empty($shipInfo->email) ? $customer->email : $shipInfo->email;
       /* if (strpos(_PS_VERSION_, "1.2") !== 0) {
            $countryCode = Country::getIsoById($billInfo->id_country);
        }*/

        /* 卡号信息*/
        $CardNo = $additionInfo["CardPAN"];
        $CardSecurityCode = $additionInfo["CVV2"];
        $CardExpireMonth = $additionInfo["ExpirationMonth"];
        $CardExpireYear = $additionInfo["ExpirationYear"];



        /* 其他信息 */
        $GoodListInfo = $this->products($cart, $mycurrency->iso_code);
        $IPAddress = $this->get_client_ip();
        $Remark = trim($message['message']);
        $Language = $_SESSION['lang'];
        $ReturnURL = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/payment_return/';
        $IssuingBank = 'blank';

        $_shipMethod = new Carrier(intval($cart->id_carrier));
        $shipMethod = $_shipMethod->name;

      
        /* 加密 */
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


    function payment_submit($payUrl, $data) {
        $info = $this->curl_post($payUrl, $data);
        return $info;
    }

    function curl_post($url, $data) {
        $curl = curl_init(); 
        $http_payUrl = strpos($url, 'https');
        $port = '';
        if ($http_payUrl !== false) {
            $port = 443;
        } else {
             $port = 80;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_PORT, $port);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            return false;
        }
        curl_close($curl);
        return $tmpInfo;
    }
    function string_replace($string_before) {
        $string_after = str_replace("\n", " ", $string_before);
        $string_after = str_replace("\r", " ", $string_after);
        $string_after = str_replace("\r\n", " ", $string_after);
        $string_after = str_replace("'", "&#39 ", $string_after);
        $string_after = str_replace('"', "&#34 ", $string_after);
        $string_after = str_replace("(", "&#40 ", $string_after);
        $string_after = str_replace(")", "&#41 ", $string_after);
        return $string_after;
    }
    function get_client_ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $online_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $online_ip = $_SERVER['HTTP_X_REAL_IP'];
        } else {
            $online_ip = $_SERVER['REMOTE_ADDR'];
        }
        $ips = explode(",", $online_ip);
        return $ips[0];
    }
    function getBrowserLang() {
        $acceptLan = '';
        if (isSet($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $acceptLan = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $acceptLan = $acceptLan[0];
        }
        return $acceptLan;
    }


    //转换币种编码
    function toCurrencyCode($Currency){
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'JA'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','CNY'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
        
        return isset($CurrencyArray[$Currency]) ? $CurrencyArray[$Currency] : $Currency;
    }


    /*
    *   商品信息
    *
    */
    function products($cart, $Currency){
  
      $products = $cart->getProducts();
        $GoodList = "";
        if(!empty($products[0]["name"])){
            for ($i = 0; $i < sizeof($products); $i++) {
            $GoodList=$GoodList."<GoodsName>".$products[$i]["name"]."</GoodsName><Qty>".$products[$i]["cart_quantity"]."</Qty><Price>".number_format($products[$i]["price"], 2, '.', '')."</Price><Currency>".$Currency."</Currency>";
            $GoodList = "<Goods>".$GoodList."</Goods>";
          }
        }else{
            $GoodList = "no products";
        }

        return $GoodList;
        

    }


}
