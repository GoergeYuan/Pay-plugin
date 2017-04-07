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
class FirstTeam_Fashionpay_PaymentController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Order instance
	 */
	protected $_order;

	/**
	 *  Get order
	 *
	 *  @param    none
	 *  @return	  Mage_Sales_Model_Order
	 */
	public function getOrder()
	{
		if ($this->_order == null) {
			$session = Mage::getSingleton('checkout/session');
			$this->_order = Mage::getModel('sales/order');
			$this->_order->loadByIncrementId($session->getLastRealOrderId());
		}
		return $this->_order;
	}

	/**
	 * When a customer chooses CreditCard on Checkout/Payment page
	 * 当客户选择信用卡结帐付款页面
	 *
	 */
	public function redirectAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setCreditCardPaymentQuoteId($session->getQuoteId());

		$order = $this->getOrder();

		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}

		$order->addStatusToHistory(
		$order->getStatus(),
		Mage::helper('Fashionpay')->__('Customer was redirected to creditcard')
		);
		$order->save();

		$this->getResponse()
		->setBody($this->getLayout()
		->createBlock('Fashionpay/redirect')
		->setOrder($order)
		->toHtml());

		$session->unsQuoteId();
	}

	/**
	 *  CreditCard response router
	 *  信用卡响应路由器
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function notifyAction()
	{
		$model = Mage::getModel('Fashionpay/payment');

		if ($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
			$method = 'post';

		} else if ($this->getRequest()->isGet()) {
			$postData = $this->getRequest()->getQuery();
			$method = 'get';

		} else {
			$model->generateErrorResponse();
		}



		$order = Mage::getModel('sales/order')
		->loadByIncrementId($postData['reference']);

		if (!$order->getId()) {
			$model->generateErrorResponse();
		}

		if ($returnedMAC == $correctMAC) {
			if (1) {
				$order->addStatusToHistory(
				$model->getConfigData('order_status_payment_accepted'),
				Mage::helper('Fashionpay')->__('Payment accepted by CreditCard')
				);

				$order->sendNewOrderEmail();

				if ($this->saveInvoice($order)) {
					//                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				}

			} else {
				$order->addStatusToHistory(
				$model->getConfigData('order_status_payment_refused'),
				Mage::helper('Fashionpay')->__('Payment refused by CreditCard')
				);

				// TODO: customer notification on payment failure
			}

			$order->save();

		} else {
			$order->addStatusToHistory(
			Mage_Sales_Model_Order::STATE_CANCELED,//$order->getStatus(),
			Mage::helper('Fashionpay')->__('Returned MAC is invalid. Order cancelled.')
			);
			$order->cancel();
			$order->save();
			$model->generateErrorResponse();
		}
	}

	/**
	 *  Save invoice for order
	 *	保存订单的发票
	 *
	 *  @param    Mage_Sales_Model_Order $order
	 *  @return	  boolean Can save invoice or not
	 */
	protected function saveInvoice(Mage_Sales_Model_Order $order)
	{
		if ($order->canInvoice()) {
			$convertor = Mage::getModel('sales/convert_order');
			$invoice = $convertor->toInvoice($order);
			foreach ($order->getAllItems() as $orderItem) {
				if (!$orderItem->getQtyToInvoice()) {
					continue;
				}
				$item = $convertor->itemToInvoiceItem($orderItem);
				$item->setQty($orderItem->getQtyToInvoice());
				$invoice->addItem($item);
			}
			$invoice->collectTotals();
			$invoice->register()->capture();
			Mage::getModel('core/resource_transaction')
			->addObject($invoice)
			->addObject($invoice->getOrder())
			->save();
			return true;
		}

		return false;
	}
	/**
	 *  Success payment page
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function dataproAction()
	{

	//开启session传递参数到失败页面
	  $result=$_REQUEST["result"];
	  session_start();
	  $_SESSION['result'] = $result;

	  
	  //$payXml = simplexml_load_string($result);
	  $model = Mage::getModel('Fashionpay/payment');
	  $order = $this->getOrder();

	  parse_str($result,$myArray);

	   if($myArray['Succeed'] !== '500'){
	   		 /* MD5私钥 */
		    $MD5key     = trim($model->getConfigData('security_code'));
		    /* 支付流水号 */
		    $PaymentOrderNo = $myArray['PaymentOrderNo'];
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
		    $Result     = $myArray['Result'];
		    /* MD5info校验信息 */
		    $MD5info    = $myArray['MD5info'];
		    /* 错误码描述信息（非系统返回） */ 
		    $description = $myArray['description'];
		    /* 校验源字符串 */
		    $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
		    /* 校验结果 */
		    $MD5sign    = strtoupper(md5($md5src));
	   
	   
	
		if ($Succeed == '88' || $Succeed == 88) {

			//echo 'bbbb--';echo $model->getConfigData('order_status_payment_accepted');exit;
			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_accepted'),
			Mage::helper('Fashionpay')->__('Payment succeed! TradeNo:'.$TradeNo)
			);
			//$order->setState($model->getConfigData('order_status_payment_accepted'), true);
			$order->save();
			//exit;.
			$this->_redirect('checkout/onepage/success');
			//exit;
		} else if($Succeed == '99' && 99){

			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_wait'),
			Mage::helper('Fashionpay')->__('Payment succeed! TradeNo:'.$TradeNo)
			);
			//$order->setState($model->getConfigData('order_status_payment_accepted'), true);
			$order->save();
			//exit;.
			$this->_redirect('checkout/onepage/success');
			//exit;
		  
		}else{

			$_SESSION['resubmit'] = 0;
			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_refused'),
			Mage::helper('Fashionpay')->__('Payment failed! Error code:'.$Succeed.';Result:'.$Result.';TradeNo:'.$PaymentOrderNo.';Description:'.$description)
			);
			//$order->setState($model->getConfigData('order_status_payment_refused'), true);
			Mage::getSingleton('core/session')->setResultMessage($ResultMessage);
			$order->save();
			$this->_redirect('checkout/onepage/failure');
			//exit;

		    }
		}else{
			$_SESSION['resubmit'] = 0;
			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_refused'),
			Mage::helper('Fashionpay')->__($myArray['Succeed'].'&nbsp;&nbsp;&nbsp;'.$myArray['Result'])
			);
			$order->save();
			$this->_redirect('checkout/onepage/failure');
		}
	  
	}
	/**
	 *  Success payment page
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function returnAction()
	{
		$model = Mage::getModel('Fashionpay/payment');
		$order = $this->getOrder();
		if ($this->_validated()) {

			//echo 'bbbb--';echo $model->getConfigData('order_status_payment_accepted');exit;
			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_accepted'),
			Mage::helper('Fashionpay')->__('Payment succeed!')
			);
			//$order->setState($model->getConfigData('order_status_payment_accepted'), true);
			$order->save();
			//exit;
			$this->_redirect('checkout/onepage/success');
			//exit;
		} else {
			//echo 'aaaa--';echo $model->getConfigData('order_status_payment_refused');exit;
			$order->addStatusToHistory(
			$model->getConfigData('order_status_payment_refused'),
			Mage::helper('Fashionpay')->__('Payment failed!')
			);
			//$order->setState($model->getConfigData('order_status_payment_refused'), true);
			$order->save();
			$this->_redirect('checkout/onepage/failure');
			//exit;
		}
	}

	private function _validated() {

		$model = Mage::getModel('Fashionpay/payment');
		$session = Mage::getSingleton('checkout/session');
		
		//$MerNo = $model->getConfigData('merchant_no');
		// 获取订单返回信息
		$TradeNo  = $_REQUEST["PaymentOrderNo"];
		$OrderNo  = $_REQUEST["BillNo"];
		$PrivateKey   = trim($model->getConfigData('security_code'));
		$CurrencyCode = $_REQUEST["Currency"];
		$Amount       = $_REQUEST["Amount"];
		$ResultCode   = $_REQUEST["Succeed"]; //支付结果返回标示: 88 :表示交易成功 ; 0: 表示交易失败; 99: 表示待处理(延时支付)
		$ResultMessage = $_REQUEST["Result"];
		$MD5Info  = $_REQUEST["MD5info"];
		$Remark   = $_REQUEST["Remark"];
		
		// 数据的组合和加密校验
		$md5src   = $TradeNo.$OrderNo.$CurrencyCode.$Amount.$ResultCode.$PrivateKey;
		$md5sign  = strtoupper(md5($md5src));
		
		if (($md5sign == $MD5Info)&&($ResultCode == "1")) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *  Success payment page
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function successAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setQuoteId($session->getCreditCardPaymentQuoteId());
		$session->unsCreditCardPaymentQuoteId();

		$order = $this->getOrder();

		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}

		$order->addStatusToHistory(
		$order->getStatus(),
		Mage::helper('Fashionpay')->__('Customer successfully returned from CreditCard')
		);

		$order->save();

		$this->_redirect('checkout/onepage/success');
	}

	/**
	 *  Failure payment page
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function errorAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$errorMsg = Mage::helper('Fashionpay')->__(' There was an error occurred during paying process.');

		$order = $this->getOrder();

		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}
		if ($order instanceof Mage_Sales_Model_Order && $order->getId()) {
			$order->addStatusToHistory(
			Mage_Sales_Model_Order::STATE_CANCELED,//$order->getStatus(),
			Mage::helper('Fashionpay')->__('Customer returned from CreditCard.') . $errorMsg
			);

			$order->save();
		}

		$this->loadLayout();
		$this->renderLayout();
		Mage::getSingleton('checkout/session')->unsLastRealOrderId();
	}
}
