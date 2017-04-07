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
 * @category	FirstTeam
 * @package 	Fashionpay_FirstTeam
 * @copyright	Copyright (c) 2009-2015 FirstTeam.
 */
class FirstTeam_Fashionpay_Block_Redirect extends Mage_Core_Block_Abstract
{

	protected function _toHtml()
	{
	    if($_SESSION['resubmit'] != 1){
			$standard = Mage::getModel('Fashionpay/payment');
			$form = new Varien_Data_Form();
			$form->setAction($this->getUrl('Fashionpay/payment/datapro'))
				->setId('FirstTeam_checkout')
				->setName('FirstTeam_payment_checkout')
				->setMethod('POST')
				->setUseContainer(true);
			//foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
				//$form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
			//}
			
			$result = $this->vpost($standard->getEkCreditCardUrl(), $standard->setOrder($this->getOrder())->getStandardCheckoutFormFields());

			$post_data = $standard->setOrder($this->getOrder())->getStandardCheckoutFormFields();
			
			$form->addField($field, 'hidden', array('name' => "result", 'value' => $result));

			$formHTML = $form->toHtml();
			
			$html = '<html><body>';
			$html.= $this->__('You will be redirected to creditcard in a few seconds.');
			$html.= $formHTML;
			$html.= '<script type="text/javascript">document.getElementById("FirstTeam_checkout").submit();</script>';
			$html.= '</body></html>';
		}else{
			$html = '<html><body><script>alert("please do not refresh the page!");window.history.back(-1);</script>';
			$html.= '</body></html>';
		}

	    $_SESSION['resubmit'] = 1;
        return $html;
    }
	

	
/**
 * 支付请求网关
 * @param URL $url          请求网关
 * @param string $data      请求数据
 * @param number $timeout   默认请求时间
 */
	function vpost($url, $data ,$timeout = 30) {

		if(function_exists('curl_init') && function_exists('curl_exec')){  //curl

			require 'Paylib/Http_Client_Curl.php';
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
			require 'Paylib/Http_Client_Socket.php';
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
		require 'Paylib/System_Response.php';
		$isErrResponse = new System_Response();
		$errResponse = $isErrResponse->checkinfo($myArray['Succeed']);

		return $payResult.'&description='.$errResponse;

	}


}