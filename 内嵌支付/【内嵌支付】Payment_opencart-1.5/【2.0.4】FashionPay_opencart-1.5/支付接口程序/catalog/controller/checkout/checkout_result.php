<?php
class ControllerCheckoutcheckoutresult extends Controller { 
	public function index() { 	
		
		   
		 $this->language->load('payment/FirstTeam');
		
		 $this->data['title'] = sprintf($this->language->get('Heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['Your_BillNo'] = $this->language->get('Your_BillNo');
			
		
			$this->data['heading_title'] = sprintf($this->language->get('Heading_title'), $this->config->get('config_name'));
			$this->data['Failed_title'] = $this->language->get('Failed_title');
			$this->data['Success_title'] = $this->language->get('Success_title');
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_Result_code'] = $this->language->get('text_Result_code');
			$this->data['text_failure'] = $this->language->get('text_failure');


		    if(!isset($_SESSION)){  
		       session_start();  
		       $_SESSION['ResultMessage'];
		       $_SESSION['Succeed'];
		       $_SESSION['payResponseMsg'];
		    } 

	//如遇到页面刷新，返回页面操作显示 Do not refresh!
		    if(isset($_SESSION['order_id'])){
		    	
			    $order_id = $_SESSION['order_id'];
			    $ResultMessage = !empty($_SESSION['ResultMessage']) ? $_SESSION['ResultMessage'] : 'OK';
			    $Succeed = $_SESSION['Succeed'];
			    $payResponseMsg = $_SESSION['payResponseMsg'];
		    }else{

		    	$order_id = "Do not refresh!";
			    $ResultMessage = "Do not refresh!";
			    $Succeed = "Do not refresh!";
			    $Result_code = 'Do not refresh!';
		    }

	// 判断成功和失败页面的字体颜色和信息
		    if($Succeed == '88' || $Succeed == '99'){

		    	$Result_title = '<font color="#55AA00">'.$ResultMessage.'</font>';
		    	$Result_code  = '<font color="#55AA00">'.$Succeed.'</font>';
		    	$this->data['text_ResultMessage'] ='<font color="$55AA00">'.$payResponseMsg.'</font>';
		    	$text_result = $this->language->get('text_success');

		    }else{

		    	$Result_title = '<font color="red">'.$ResultMessage.'</font>';
		    	$Result_code  = '<font color="red">'.$Succeed.'</font>';
				$this->data['text_ResultMessage'] ='<font color="red">'.$payResponseMsg.'</font>';
		    	$text_result = $this->language->get('text_failure');
		    }

		    $this->data['text_result'] = $text_result;
		    $this->data['Result_title'] = $Result_title;
			$this->data['text_billno']='<font color="green">'.$order_id.'</font>';
			$this->data['text_Error'] = $Result_code;

	 //释放SESSION
			unset($_SESSION['order_id']);
			unset($_SESSION['ResultMessage']);
			unset($_SESSION['Succeed']);
		    unset($_SESSION['payResponseMsg']);

			$this->data ['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';    
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
			} else {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_result.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/checkout_result.tpl';
			} else {
				$this->template = 'default/template/checkout/checkout_result.tpl';
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
}
?>