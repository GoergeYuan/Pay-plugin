<?php
/**
 *
 * @package    Company
 * 
 * @author     xinhaozheng@gmail.com
 */

class Company_Card_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	
    protected $_code  = 'card_payment';
    protected $_formBlockType = 'card/form';

    // CreditCard return codes of payment
    const RETURN_CODE_ACCEPTED      = 'Success';
    const RETURN_CODE_TEST_ACCEPTED = 'Success';
    const RETURN_CODE_ERROR         = 'Fail';

    // Payment configuration
    protected $_isGateway               = false;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;

    // Order instance
    protected $_order = null;

    /**
     *  Returns Target URL
     *
     *  @return	  string Target URL
     */
    public function getCardUrl()
    {
        $url = $this->getConfigData('transport').'://'.$this->getConfigData('gateway');
		//$this->s('paymenturl:'.$url);
        return $url;
    }

    /**
     *  Return back URL
     *
     *  @return	  string URL
     */
	protected function getReturnURL()
	{
		$returnUrl=Mage::getUrl('card/payment/return', array('_secure' => true));
		//$this->s('returnurl:'.$returnUrl);
		return $returnUrl;
	}

	/**
	 *  Return URL for CreditCard success response
	 *
	 *  @return	  string URL
	 */
	protected function getSuccessURL()
	{
		return Mage::getUrl('checkout/onepage/success', array('_secure' => true));
	}

    /**
     *  Return URL for card failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('card/payment/error', array('_secure' => true));
    }

	/**
	 *  Return URL for CreditCard notify response
	 *
	 *  @return	  string URL
	 */
	protected function getNotifyURL()
	{
		return Mage::getUrl('checkout/onepage/notice', array('_secure' => true));
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
     *
     *  @return	 object
     */
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('card/form_payment', $name);
        $block->setMethod($this->_code);
        $block->setPayment($this->getPayment());

        return $block;
    }

    /**
     *  Return Standard Checkout Form Fields for request to CreditCard
     *
     *  @return	  array Array of hidden form fields
     */
	public function getStandardCheckoutFormFields()
	{
		$session = Mage::getSingleton('checkout/session');

		$order = $this->getOrder();
		if(!($order instanceof Mage_Sales_Model_Order)){
			Mage::throwException($this->_getHelper()->_('Cannot retrieve order object'));
		}
		//汇率
		/*
		print_r($currencyObject->getRate($CurrencyCode));
		print_r($allows);
		print_r($currencyObject->getCurrencyRates('EUR',$allows));
		*/
		//商户号 
		$MerNo			= trim($this->getConfigData('merchant_no'));
		//订单号
		$BillNo			= $order->getRealOrderId();
		//当前使用的币种 
		$CurrencyCode	= Mage::app()->getStore()->getCurrentCurrencyCode();
		$CurrencyCode	= strtoupper($CurrencyCode);
		$Currency		= $this->getCurrencyByCode($CurrencyCode);
		//支付页面语言
		/* $Language		= $this->_getLanguageCode(); */
        $Language           = !empty($this->getConfigData('language')) ? strtolower($this->getConfigData('language')) : $this->getRequestBrowserLang();
		//订单金额
		$Amount			= $order->getBaseGrandTotal();
		//$DisAmount		= $CurrencyCode
		$Amount			= Mage::app()->getStore()->convertPrice($Amount);
        $Amount         = round($Amount, 2);
		//返回地址 
		$ReturnURL		= empty($this->getConfigData('return_url')) ? trim($this->getReturnURL()) :trim($this->getConfigData('return_url'));
		//备注 
		$Remark			= Mage::getBaseUrl();
		//交易网址参数 
		$MerWebsite		= Mage::getBaseUrl();
		//MD5key
		$MD5key			= trim($this->getConfigData('md5_key'));

		$md5str			= $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;
		$MD5info		= strtoupper(md5($md5str));
		//是否启用支付日志（默认目录/vmCheckout/log/）
		$PayLog         = $this->getConfigData('pay_log');
		/*
		 *账单信息************************************************************
		 */
		$billingAddress		= $order->getBillingAddress();
		$FirstName			= $billingAddress->getFirstname();
		$LastName			= $billingAddress->getLastname();
		$Email				= $order->getData('customer_email');
		$Phone				= $billingAddress->getTelephone();
		$ZipCode			= $billingAddress->getPostcode();
		$Address			= $billingAddress->getStreetFull();
		$City				= $billingAddress->getCity();
		$State				= $billingAddress->getRegion();
		$Country			= $billingAddress->getCountry();
        
        $NoticeURL          = $this->getNotifyURL();
		/*
		 *收货信息**********************************************************
		 */
		$shippingAddress	= $order->getShippingAddress();
		$DeliveryFirstName	= $shippingAddress->getFirstname();
		$DeliveryLastName	= $shippingAddress->getLastname();
		$DeliveryEmail		= $order->getData('customer_email');
		$DeliveryPhone		= $shippingAddress->getTelephone();
		$DeliveryZipCode	= $shippingAddress->getPostcode();
		$DeliveryAddress	= $shippingAddress->getStreetFull();
		$DeliveryCity		= $shippingAddress->getCity();
		$DeliveryState		= $shippingAddress->getRegion();
		$DeliveryCountry	= $shippingAddress->getCountry();
        /* ------------------------------------------------------------------------------------- */
        /* Add Products Detail */
        // $Products       = '';
        // $items          = $order->getAllVisibleItems();
        // $exp            = '###';
        // $exa            = '@@@';
        // $cTemp          = array();
        // foreach($items as $key=>$item){
        //     $pTempList  = array(
        //             'name'      => $item->getName(),
        //             'price'     => $item->getPrice(),
        //             'sku'       => $item->getSku(),
        //             /* 'description'   => $item->getDescription() */
        //             /* 'ids'       => $item->getProductId(), */
        //             /* 'qty'       => $item->getQtyToInvoice() */
        //         );
        //     $cTemp[]        = implode($exa, $pTempList);
        // } 
       // $Products           = implode($exp, $cTemp);
        $Products = $this->products($order);
        /* ------------------------------------------------------------------------------------- */
		$parameter = array(
				'MerNo'				=>$MerNo,
				'BillNo'			=>$BillNo,
				'Amount'			=>$Amount,
				'Currency'			=>$Currency,
				'ReturnURL'			=>$ReturnURL,
				'Language'			=>$Language,
				'MD5info'			=>$MD5info,
				'MerWebsite'		=>$MerWebsite,
				'Remark'			=>$Remark,
				'FirstName'			=>$FirstName,
				'LastName'			=>$LastName,
				'Email'				=>$Email,
				'Phone'				=>$Phone,
				'ZipCode'			=>$ZipCode,
				'Address'			=>$Address,
				'City'				=>$City,
				'State'				=>$State,
				'Country'			=>$Country,
				'DeliveryFirstName'	=>$DeliveryFirstName,
				'DeliveryLastName'	=>$DeliveryLastName,
				'DeliveryEmail'		=>$DeliveryEmail,
				'DeliveryPhone'		=>$DeliveryPhone,
				'DeliveryZipCode'	=>$DeliveryZipCode,
				'DeliveryAddress'	=>$DeliveryAddress,
				'DeliveryCity'		=>$DeliveryCity,
				'DeliveryState'		=>$DeliveryState,
				'DeliveryCountry'	=>$DeliveryCountry,
                'Products'          =>$Products,
                'Notice'            =>$NoticeURL,
                'PayLog'            =>$PayLog
			);
		$fields				= array();
		$sort_array			= array();
		$arg				= '';
		$sort_array			= $this->arg_sort($parameter);
		while(list($key,$val)=each($sort_array)){
			$fields[$key]	= $this->charset_encode($val,'utf-8');
		}
		return $fields;
	}
	private function getCurrencyByCode($code)
	{
		$Currency   = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13','TWD' => '14','RUB'=>'15');
		$code = strtoupper(trim($code));
        if(!$code || !isset($Currency[$code])){
            exit("Currency [$code] is not set");
        }
        return $Currency[$code];
	}
    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('card/payment/redirect');
    }
	public function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;
	}

	public function charset_encode($input,$_output_charset ,$_input_charset ="GBK" ) {
		$output = "";
		if($_input_charset == $_output_charset || $input ==null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
   
	/**
	 * Return authorized languages by CreditCard
	 *
	 * @param	none
	 * @return	array
	 */
	protected function _getAuthorizedLanguages()
	{
		$languages = array();
		
        foreach (Mage::getConfig()->getNode('global/payment/card_payment/languages')->asArray() as $data) 
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

    //获取浏览器语言
     public function getRequestBrowserLang()
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