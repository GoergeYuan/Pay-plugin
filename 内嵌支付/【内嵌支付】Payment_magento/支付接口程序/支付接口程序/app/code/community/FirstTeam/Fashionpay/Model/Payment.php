<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category FirstTeam
 * @package     Fashionpay_FirstTeam
 * @copyright   Copyright (c) 2009-2015 FirstTeam.
 */
class FirstTeam_Fashionpay_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'FirstTeam';
    protected $_formBlockType = 'Fashionpay/form';

    // CreditCard return codes of payment 支付信用卡返回代码
    const RETURN_CODE_ACCEPTED      = 'Success';
    const RETURN_CODE_TEST_ACCEPTED = 'Success';
    const RETURN_CODE_ERROR         = 'Fail';

    // Payment configuration 支付模式
    protected $_isGateway               = false;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;

    // 订单实例
    protected $_order = null;
	
	/**
     * Assign data to info model instance
     *  分配数据到信息模型实例
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCcType($data->getCcTypeEk())
            ->setCcOwner($data->getCcOwnerEk())
            ->setCcLast4(substr($data->getCcNumberEk(), -4))
            ->setCcNumber($data->getCcNumberEk())
            ->setCcCid($data->getCcCidEk())
            ->setCcExpMonth($data->getCcExpMonthEk())
            ->setCcExpYear($data->getCcExpYearEk())
            ->setCcSsIssue($data->getCcSsIssueEk())
            ->setCcSsStartMonth($data->getCcSsStartMonthEk())
            ->setCcSsStartYear($data->getCcSsStartYearEk())
			->setCcclientIP($date->getCcclientIP)
			->setCcPaymentTime($date->getCcPaymentTime)
            ;
			$_SESSION['cardNo'] = $data->getCcNumberEk();
			$_SESSION['expMonth'] = $data->getCcExpMonthEk();
			$_SESSION['expYear'] = $data->getCcExpYearEk();
			$_SESSION['cid'] = $data->getCcCidEk();
			$_SESSION['issuing_bank'] = $data->getCcOwnerEk();
			$_SESSION['clientIP'] = $data['cc_clientIP'];
			$_SESSION['payment_time'] = $data['cc_payment_time'];
        return $this;
    }

    /**
     *  Returns Target URL  
     *  返回目标网址
     *
     *  @return	  string Target URL
     */
    public function getEkCreditCardUrl()
    {
        $url = $this->getConfigData('transport_url');
        return $url;
    }

    /**
     *  Return back URL
     *  返回网址
     *
     *  @return	  string URL
     */
	protected function getReturnURL()
	{
		// return Mage::getUrl('Fashionpay/payment/return', array('_secure' => true));
        $Return_url = $this->getConfigData('return_url');
        return $Return_url;
	}

	/**
	 *  Return URL for CreditCard success response
     *  返回URL信用卡成功响应
	 *
	 *  @return	  string URL
	 */
	protected function getSuccessURL()
	{
		return Mage::getUrl('checkout/onepage/success', array('_secure' => true));
	}

    /**
     *  Return URL for CreditCard failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('firstteam/payment/error', array('_secure' => true));
    }

	/**
	 *  Return URL for CreditCard notify response
     *  返回的URL信息通知响应
	 *
	 *  @return	  string URL
	 */
	protected function getNotifyURL()
	{
		return Mage::getUrl('checkout/onepage/success', array('_secure' => true));
	}

    /**
     * Capture payment
     *
     * @param   Varien_Object $orderPayment
     * @return  Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        $payment->setStatus(self::STATUS_APPROVED)
            ->setLastTransId($this->getTransactionId());

        return $this;
    }

    /**
     *  Form block description
     *  模块描述
     *
     *  @return	 object
     */
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('Fashionpay/form_payment', $name);
        $block->setMethod($this->_code);
        $block->setPayment($this->getPayment());

        return $block;
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('Fashionpay/payment/redirect');
    }

    /**
     *  Return Standard Checkout Form Fields for request to Fashionpay
     *
     *  @return	  array Array of hidden form fields
     */
    public function getStandardCheckoutFormFields()
    {
        $session = Mage::getSingleton('checkout/session');

        $order = $this->getOrder();
        if (!($order instanceof Mage_Sales_Model_Order)) {
            Mage::throwException($this->_getHelper()->__('Cannot retrieve order object'));
        }

       // 订单信息
        $MerNo   = trim($this->getConfigData('merchant_no'));
        $OrderNo  = $order->getRealOrderId();
        $MD5key  = trim($this->getConfigData('security_code'));
        $Amount  = sprintf('%.2f', $order->getGrandTotal());
		 // 速汇通开通币种
        //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗  15:俄罗斯卢布
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
        $CurrencyCode   = $CurrencyArray[$order->getOrderCurrencyCode()];
        // 持卡人信息
        $billing     = $order->getBillingAddress();
        $BillFirstName = trim($billing->getFirstname());
        $BillLastName  = trim($billing->getLastname());
        $BillAddress = trim($billing->getStreet(1).$billing->getStreet(2));
        $BillCity    = trim($billing->getCity());
        $BillState   = trim($billing->getRegion());
        $BillCountry = trim($billing->getCountry());
        $BillZip     = trim($billing->getPostcode());
		$BillEmail   = trim($order->getCustomerEmail());
        $BillTelephone = trim($billing->getTelephone());
		$BillFax     = trim($billing->getFax());
        $BillPhone   = !empty($BillTelephone ) ? $BillTelephone  : $BillFax;
        // 收货信息
        $shipping       = $order->getShippingAddress();
        $ShipFirstName_1  = trim($shipping->getFirstname());
        $ShipLastName_1   = trim($shipping->getLastname());
        $ShipAddress_1    = trim($shipping->getStreet(1).$shipping->getStreet(2));
        $ShipCity_1       = trim($shipping->getCity());
        $ShipState_1	    = trim($shipping->getRegion());
        $ShipCountry_1    = trim($shipping->getCountry());
        $ShipZip_1        = trim($shipping->getPostcode());
		$ShipEmail_1      = trim($order->getCustomerEmail());
        $ShipTelephone_1  = trim($shipping->getTelephone());
        $ShipFax          = trim($shipping->getFax());
        $ShipPhone_1      = !empty($ShipTelephone) ? $ShipTelephone : $ShipFax;



        //避免收货为空
        $ShipFirstName = empty($ShipFirstName_1) ? $BillFirstName : $ShipFirstName_1;
        $ShipLastName  = empty($ShipLastName_1) ? $BillLastName : $ShipLastName_1;
        $ShipAddress   = empty($ShipAddress_1) ? $BillAddress : $ShipAddress_1;
        $ShipCity      = empty($ShipCity_1) ? $BillCity : $ShipCity_1;
        $ShipState     = empty($ShipState_1) ? $BillState : $ShipState_1;
        $ShipCountry   = empty($ShipCountry_1) ? $BillCountry : $ShipCountry_1;
        $ShipZip       = empty($ShipZip_1) ? $BillZip : $ShipZip_1;
        $ShipEmail     = empty($ShipEmail_1) ? $BillEmail : $ShipEmail_1;
        $ShipPhone     = empty($ShipPhone_1) ? $BillPhone : $ShipPhone_1;


        // 通道参数信息
        $Current_languae = $this->getBroswerLanguage();
        $Language    = !empty($Current_languae) ? $Current_languae : "en";
        $ReturnURL	     = $this->getReturnURL();
       /*
        if ($_SERVER['HTTPS'] != "on") {
            $http_head =  "http://".$_SERVER['HTTP_HOST'];
         }else{
            $http_head =  "https://".$_SERVER['HTTP_HOST'];
        }
        $ReturnURL  = $http_head.'/index.php/ekcreditcard/payment/return/';*/
        $GoodsListInfo = $this->products($order);
		


		$CardNo           = $_SESSION['cardNo'];
	    $CardExpireYear   = $_SESSION['expYear'];
	    $CardExpireMonth  = $_SESSION['expMonth'];
		$CardExpireMonth  = substr($CardExpireMonth,0,2);
		$CardSecurityCode = $_SESSION['cid'];
        $IssuingBank      = $_SESSION['issuing_bank'];
		$CardholderLocalTime = $_SESSION['payment_time'];
		$clientIP         = $_SESSION['clientIP'];
		$clientIP_tow         = $this->getOnline_ip();
		$IPAddress        = !empty($clientIP) ? $clientIP : $clientIP_tow ;
	    $BroserType  = $_SERVER['HTTP_USER_AGENT'];
	    $BrowserLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	    $SessionId   = $_SERVER['HTTP_COOKIE'];
		
		
		// 参数组合加密校验

        $MD5src         = $MerNo . $OrderNo . $CurrencyCode . $Amount . $Language . $ReturnURL . $MD5key;
        $MD5info        = strtoupper(md5($MD5src)); 
	
	
            $post_data = array(
            'MerNo' => $MerNo,
            'BillNo' => $OrderNo,
            'order_token' => $MD5key,
            'products' => $GoodsListInfo,
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
		//$post_data = http_build_query($post_data, '', '&');
		

		 return $post_data;
    }


	//功能函数。将变量值不为空的参数组成字符串。结束	
	
	/**
	 * Return authorized languages by CreditCard
	 *
	 * @param	none
	 * @return	array
	 */
	protected function _getAuthorizedLanguages()
	{
		$languages = array();
		
        foreach (Mage::getConfig()->getNode('global/payment/FirstTeam/languages')->asArray() as $data)
		{
			$languages[$data['code']] = $data['name'];
		}
		
		return $languages;
	}
	
	/**
	 * Return language code to send to CreditCard
	 *
	 * @param	none
	 * @return	String
	 */
	protected function _getLanguageCode()
	{
		// Store language
		$language = strtoupper(substr(Mage::getStoreConfig('general/locale/code'), 0, 2));

		// Authorized Languages
		$authorized_languages = $this->_getAuthorizedLanguages();

		if (count($authorized_languages) === 1) 
		{
			$codes = array_keys($authorized_languages);
			return $codes[0];
		}
		
		if (array_key_exists($language, $authorized_languages)) 
		{
			return $language;
		}
		
		// By default we use language selected in store admin
		return $this->getConfigData('language');
	}



    /**
     *  Output failure response and stop the script
     *
     *  @param    none
     *  @return	  void
     */
    public function generateErrorResponse()
    {
        die($this->getErrorResponse());
    }

    /**
     *  Return response for CreditCard success payment
     *
     *  @param    none
     *  @return	  string Success response string
     */
    public function getSuccessResponse()
    {
        $response = array(
            'Pragma: no-cache',
            'Content-type : text/plain',
            'Version: 1',
            'OK'
        );
        return implode("\n", $response) . "\n";
    }

    /**
     *  Return response for CreditCard failure payment
     *
     *  @param    none
     *  @return	  string Failure response string
     */
    public function getErrorResponse()
    {
        $response = array(
            'Pragma: no-cache',
            'Content-type : text/plain',
            'Version: 1',
            'Document falsifie'
        );
        return implode("\n", $response) . "\n";
    }

    /**
     * 对特殊字符进行转义
     * @param string string_before
     * @return string string_after
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
	
    /*
    *   商品信息
    *
    */     
    function products($order_id){

        $GoodsList = '';
        foreach($order_id->getAllItems() as $item) {
               $Goods_name = $item->getName();
            }
        if(!empty($Goods_name)){
        foreach($order_id->getAllItems() as $item) {
            if(!strstr($GoodsList,$item->getName())){
                $GoodsList  .= "<GoodsName>".$item->getName()."</GoodsName><Qty>" .
                    ceil($item->getQtyOrdered())."</Qty><Price>".sprintf('%.2f', $item->getPrice())."</Price><Currency>".$order_id->getOrderCurrencyCode()."</Currency>";
            }
        }
        $GoodsListInfo = "<Goods>".$GoodsList."</Goods>";
        }else{

            $GoodsListInfo = "no products";
        }

     return $GoodsListInfo;

    }

    /*
    *
    *   获取客户端ip   
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