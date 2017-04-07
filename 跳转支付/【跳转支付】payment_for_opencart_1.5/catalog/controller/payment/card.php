<?php
class ControllerPaymentCard extends Controller {
/*	public function index(){
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['redirect']=$this->url->link('payment/card/redirect');
		//$this->data['continue'] = $this->url->link('payment/card/redirect');
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/card.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/card.tpl';
		} else {
			$this->template = 'default/template/payment/card.tpl';
		}	
		
		$this->render();
	}
	*/
	public function confirm()
	{
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('card_order_status_id'), 'Redirect To GateWay', FALSE);
	}
	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->load->model('checkout/order');

        $baseUrl        = '';
		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			//$this->data['base'] = HTTP_SERVER;
            $baseUrl        = HTTP_SERVER;
		} else {
            $baseUrl        = HTTPS_SERVER;
			//$this->data['base'] = HTTPS_SERVER;
		}
        if(!$baseUrl){
            $baseUrl        = 'http://'.$_SERVER['HTTP_HOST'];
        }
        if($baseUrl && substr($baseUrl, -1) != '/'){
            $baseUrl        = $baseUrl . '/';
        }
        $this->data['baseUrl']      = $baseUrl;
        
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->load->library('encryption');
		$this->id = 'payment';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/card.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/card.tpl';
		} else {
			$this->template = 'default/template/payment/card.tpl';
		}	
		
		$this->render();
	}
	public function tocard() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('card_order_status_id'), 'Redirect To GateWay', FALSE);
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->load->library('encryption');
		
		$url=$this->config->get('card_transaction_url');
		if($url){
			$this->data['action']=$url;
		}else{
			$this->data['action'] = "http://" . $_SERVER['HTTP_HOST'] . '/submitOrder.php';			
		}	
		$MerNo= $this->config->get('card_merchant');
		$this->data['merchant'] =trim($MerNo);

        /* Notice Url */
		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			//$this->data['base'] = HTTP_SERVER;
            $baseUrl        = HTTP_SERVER;
		} else {
            $baseUrl        = HTTPS_SERVER;
			//$this->data['base'] = HTTPS_SERVER;
		}
        if(!$baseUrl && isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']){      
            $baseUrl    = 'http://'. $_SERVER['HTTP_HOST'];     
         }

        $this->data['NoticeURL']    = $baseUrl . 'index.php?route=payment/card/notice';

        
		$BillNo=$order_info['order_id'];
		$this->data['order_id'] = $BillNo;
		/*
		$Currency=$this->config->get('card_currency');
		switch($Currency)
		{
			case '1' : $sht_code = 'USD'; break;
			case '2' : $sht_code = 'EUR'; break;
			case '4' : $sht_code = 'GBP'; break;
			case '5' : $sht_code = 'HKD'; break;
			case '6' : $sht_code = 'JPY'; break;
			case '7' : $sht_code = 'AUD'; break;
			case '8' : $sht_code = 'KRW'; break;
			default : $sht_code = 'USD';	break;
		}
		*/
		//默认币种
	/*	$Amount=sprintf('%.2f',$order_info['total']);//$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
	*/
		$default_currency = $this->config->get('config_currency');
		//$Amount = $this->currency->convert($order_info['total'],$default_currency,$sht_code);
		//$Amount = $this->currency->convert($order_info['total'],trim($order_info['currency_code']),$sht_code);
		$Amount = $this->currency->convert($order_info['total'],trim($default_currency),$order_info['currency_code']);
		$Amount = round($Amount,2);
		$this->data['amount'] = $Amount;
		$Currency = trim($order_info['currency_code']);
		$Currency = $this->getCurrencyByCode($Currency);
	//	$disAmount = round($order_info['total'],2).' '.$order_info['currency_code'];
		//$this->data['disAmount'] = $disAmount;
		
		$this->data['currency'] = trim($Currency);//$order_info['currency'];
		$Language=$this->config->get('card_language');
		$this->data['language'] = $Language;

        $this->load->model("payment/card");
        //$this->data['Products']     = $this->model_payment_card->getProductsByOrderId($order_info['order_id'], $order_info['currency_code']);
		$this->data['Products'] = $this->products($order_info);
		//$ReturnURL=$this->config->get('config_url').'index.php?route=payment/card/callback';
		$ReturnURL = $this->config->get('card_returnurl');
		$this->data['returnURL'] = $ReturnURL;
		$this->data['remark']       = ($this->config->get('config_url')) ? ($this->config->get('config_url')) : ('http://'.$_SERVER['HTTP_HOST']);
		$this->data['MerWebsite']   = ($this->config->get('config_url')) ? ($this->config->get('config_url')) : ('http://'.$_SERVER['HTTP_HOST']);

		$MD5key=trim($this->config->get('card_md5key'));

		$md5src = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;		//校验源字符串
		$MD5info = strtoupper(md5($md5src));		//[必填]MD5加密数据
		$this->data['MD5info']=$MD5info;

/*		$this->data['DeliveryFirstName'] = $order_info['shipping_firstname'];
		$this->data['DeliveryLastName'] = $order_info['shipping_lastname'];
		$this->data['DeliveryEmail'] =$order_info['email'];
		$this->data['DeliveryPhone'] =$order_info['telephone'];
		$this->data['DeliveryZipCode'] = $order_info['shipping_postcode'];

		print_r($order_info);
		exit;
		
		if (!$order_info['shipping_address_2']) {
			$this->data['DeliveryAddress'] = $order_info['shipping_address_1'] ;
		} else {
			$this->data['DeliveryAddress'] = $order_info['shipping_address_1'] . ', ' . $order_info['shipping_address_2'];
		}
		$this->data['DeliveryCity'] = $order_info['shipping_city'];
		$this->data['DeliveryState'] = $order_info['shipping_zone'];
		$this->data['DeliveryCountry'] =$order_info['shipping_country'];*/

		$this->data['DeliveryFirstName'] = $this->getUserInfo($order_info, 'firstname');
		$this->data['DeliveryLastName'] = $this->getUserInfo($order_info, 'lastname');
		$this->data['DeliveryEmail'] = $this->getUserInfo($order_info, 'email');
		$this->data['DeliveryPhone'] = $this->getUserInfo($order_info, 'telephone');
		$this->data['DeliveryZipCode'] = $this->getUserInfo($order_info, 'postcode');
		$this->data['DeliveryCity'] = $this->getUserInfo($order_info, 'city');
		$this->data['DeliveryState'] = $this->getUserInfo($order_info, 'zone');
		$this->data['DeliveryCountry'] = $this->getUserInfo($order_info, 'country');
		$address_1 = $this->getUserInfo($order_info, 'address_1');
		$address_2 = $this->getUserInfo($order_info, 'address_2');

		$this->data['DeliveryAddress'] = $address_1 ? $address_1 : $address_2;
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/tocard.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/tocard.tpl';
		} else {
			$this->template = 'default/template/payment/tocard.tpl';
		}	
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
		);
				
		$this->response->setOutput($this->render());
	}

	private function getUserInfo($data, $userKey){
		$shippingKey = 'shipping_' . $userKey;
		$paymentKey = 'payment_' . $userKey;

		if(isset($data[$shippingKey]) && $data[$shippingKey]){
			return $data[$shippingKey];
		} else if(isset($data[$paymentKey]) && $data[$paymentKey]){
			return $data[$paymentKey];
		} else if(isset($data[$userKey])){
			return $data[$userKey];
		} else {
			return '';
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
			return htmlspecialchars($GoodListInfo, ENT_QUOTES, 'UTF-8');
		}



	protected function getCurrencyByCode($code)
	{
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13');
        if(!$code || !isset($CurrencyArray[$code])){
            exit("Currency [$code] is not set");
        }
        return $CurrencyArray[$code];
	}
	public function callback() {
		if (isset($this->request->post['BillNo']) && !(empty($this->request->post['BillNo']))) {
			$this->language->load('payment/card');
		
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
		
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
			$this->data['text_failure'] = $this->language->get('text_failure');
			
			$this->data['text_billno']='<font color="green">'.$this->request->post['BillNo'].'</font>';
			$this->data['text_result']='<font color="green">'.$this->request->post['Result'].'</font>';
			
			
			
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
			} else {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}
			
			$BillNo = $this->request->post['BillNo'];	
			//币种
			$Currency =$this->request->post['Currency'];// $_POST["Currency"];
			//金额
			$Amount =$this->request->post['Amount'];// $_POST["Amount"];
			//支付状态
			$Succeed =$this->request->post['Succeed'];//返回码:该值说明见于word说明文档
			//支付结果
			$Result =$this->request->post['Result'];// $_POST["Result"];//支付状态的文字说明,这些说明请展示给顾客看，同订单号，订单金额一起
			//取得的MD5校验信息
			$MD5info = $this->request->post['MD5info'];//$_POST["MD5info"]; 
			$MD5key=$this->config->get('card_md5key');
			//校验源字符串
			$md5src = $BillNo.$Currency.$Amount.$Succeed.$MD5key;
			//MD5检验结果
			$md5sign = strtoupper(md5($md5src));
	
			if ($MD5info==$md5sign && ($Succeed=='88' || $Succeed == '19')) { 
				$this->load->model('checkout/order');

				//$this->model_checkout_order->confirm($this->request->post['BillNo'], $this->config->get('card_order_status_id'));
		
				$message = '';

				if (isset($this->request->post['BillNo'])) {
					$message .= 'OrderNo: ' . $this->request->post['BillNo'] . "\n";
				}
				
				if (isset($this->request->post['Amount'])) {
					$message .= 'Amount: ' . $this->request->post['Amount'] . "\n";
				}
				if (isset($this->request->post['Succeed'])) {
					$message .= 'ResponseCode: ' . $this->request->post['Succeed'] . "\n";
				}
			
				if (isset($this->request->post['Result'])) {
					$message .= 'PayResult: ' . $this->request->post['Result'] . "\n";
				}
				if($Succeed == '88'){
                $this->model_checkout_order->update($this->request->post['BillNo'], $this->config->get('card_success_order_status_id'), $message, FALSE);
				}else{
                $this->model_checkout_order->update($this->request->post['BillNo'], $this->config->get('card_processing_order_status_id'), $message, FALSE);
                }
                $this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/card_success.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/card_success.tpl';
				} else {
					$this->template = 'default/template/payment/card_success.tpl';
				}	
		
	  			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));				
			} else {
				$this->load->model('checkout/order');
				
				$message = '';

				if (isset($this->request->post['BillNo'])) {
					$message .= 'OrderNo: ' . $this->request->post['BillNo'] . "\n";
				}
				
				if (isset($this->request->post['Amount'])) {
					$message .= 'Amount: ' . $this->request->post['Amount'] . "\n";
				}
				if (isset($this->request->post['Succeed'])) {
					$message .= 'ResponseCode: ' . $this->request->post['Succeed'] . "\n";
				}
			
				if (isset($this->request->post['Result'])) {
					$message .= 'PayResult: ' . $this->request->post['Result'] . "\n";
				}

				$this->model_checkout_order->update($this->request->post['BillNo'], $this->config->get('card_failed_order_status_id'), $message, FALSE);
    			
				$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/card_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/card_failure.tpl';
				} else {
					$this->template = 'default/template/payment/card_failure.tpl';
				}
				
	  			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));					
			}
		}
	}
}
?>