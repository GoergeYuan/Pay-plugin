<?php
class Controllerextensioncheckoutcheckoutresult extends Controller {
	public function index() {
		$this->language->load('extension/payment/FirstTeam');

		 

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = HTTP_SERVER;
			} else {
				$data['base'] = HTTPS_SERVER;
			}
		
			$data['Your_BillNo'] = $this->language->get('Your_BillNo');
			
			$data['title'] = sprintf($this->language->get('Heading_title'), $this->config->get('config_name'));
			$data['Failed_title'] = sprintf($this->language->get('Failed_title'));
			$data['Success_title'] = sprintf($this->language->get('Success_title'));
			$data['text_response'] = $this->language->get('text_response');
			$data['text_Result_code'] = $this->language->get('text_Result_code');
			$data['text_failure'] = $this->language->get('text_failure');


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
		    	$data['text_ResultMessage'] ='<font color="$55AA00">'.$payResponseMsg.'</font>';
		    	$text_result = $this->language->get('text_success');

		    }else{

		    	$Result_title = '<font color="red">'.$ResultMessage.'</font>';
		    	$Result_code  = '<font color="red">'.$Succeed.'</font>';
				$data['text_ResultMessage'] ='<font color="red">'.$payResponseMsg.'</font>';
		    	$text_result = $this->language->get('text_failure');
		    }

		    $data['text_result'] = $text_result;
			$data['Result_title'] = $Result_title;
			$data['text_billno']='<font color="green">'.$order_id.'</font>';
			$data['text_Error'] = $Result_code;
			unset($_SESSION['order_id']);
			unset($_SESSION['ResultMessage']);
			unset($_SESSION['Succeed']);
			unset($_SESSION['payResponseMsg']);

			$data ['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';    
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/cart');
			} else {
				$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}
			

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/checkout/checkout_result', $data));
	}
}