<?php

class Company_Card_PaymentController extends Mage_Core_Controller_Front_Action
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
     * When a customer chooses card on Checkout/Payment page
     *
     */
	public function redirectAction()
	{
		$session = Mage::getSingleton('checkout/session');
        // $session->setFashionpayPaymentQuoteId($session->getQuoteId());
		$session->setCardPaymentQuoteId($session->getQuoteId());

		$order = $this->getOrder();

		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}

		$order->addStatusToHistory(
			$order->getStatus(),
			Mage::helper('card')->__('Customer was redirected to CreditCard')
		);
		$order->save();

		$this->getResponse()
			->setBody($this->getLayout()
				->createBlock('card/redirect')
				->setOrder($order)
				->toHtml());
		
		
        $session->unsQuoteId();
    }

	/**
	 *  CreditCard response router
	 *
	 *  @param    none
	 *  @return	  void
	 */
	public function notifyAction()
	{
		$model = Mage::getModel('card/payment');
        
        if ($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
        	$method = 'post';

		} else if ($this->getRequest()->isGet()) {
			$postData = $this->getRequest()->getQuery();
			$method = 'get';

		} else {
			$model->generateErrorResponse();
		}

//		$returnedMAC = $postData['MAC'];
//		$correctMAC = $model->getResponseMAC($postData);


		$order = Mage::getModel('sales/order')
			->loadByIncrementId($postData['reference']);

		if (!$order->getId()) {
			$model->generateErrorResponse();
		}

		if ($returnedMAC == $correctMAC) {
			if (1) {
				$order->addStatusToHistory(
					$model->getConfigData('order_status_payment_accepted'),
					Mage::helper('card')->__('Payment accepted by card')
				);
				
				$order->sendNewOrderEmail();

				if ($this->saveInvoice($order)) {
//                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				}
				
			 } else {
			 	$order->addStatusToHistory(
					$model->getConfigData('order_status_payment_refused'),
					Mage::helper('card')->__('Payment refused by CreditCard')
				);
				
				// TODO: customer notification on payment failure
			 }
				
			$order->save();

        } else {
            $order->addStatusToHistory(
                Mage_Sales_Model_Order::STATE_CANCELED,//$order->getStatus(),
                Mage::helper('card')->__('Returned MAC is invalid. Order cancelled.')
            );
            $order->cancel();
            $order->save();
            $model->generateErrorResponse();
        }
    }

    /**
     *  Save invoice for order
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
	public function returnAction()
	{
	    session_start();
	    $result = $this->getRequest()->getParam('result');
	 
	    if(empty($result) || base64_decode($result) === false){
	        $_SESSION['result'] = 'Succeed=500&Result=Parse error';
	        $_SESSION['description'] = 'parse error';
	        
	        $order->save();
	        
	        $this->_redirect('checkout/onepage/failure');
	    }else{

	        parse_str(base64_decode($result),$myArr);
	        $model = Mage::getModel('card/payment');
	        $order = Mage::getModel('sales/order')->loadByIncrementId($myArr['BillNo']);
 
		    $_SESSION['result'] = $result;
		    $_SESSION['description'] = $this->getRequest()->getParam('description');
		    
	        // $order = $this->getOrder();
			if ($this->_validated()) {
		        $order->addStatusToHistory(
	                Mage_Sales_Model_Order::STATE_PROCESSING,//$order->getStatus(),
	                Mage::helper('card')->__('Payment succeed!')
	            );
				$order->setState($model->getConfigData('order_status_payment_accepted'), true);
		        $order->save();
		        //exit;
				$this->_redirect('checkout/onepage/success');
				//exit;
			} else {
			    
				$order->addStatusToHistory(
	                //Mage_Sales_Model_Order::STATE_P
                    Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,//$order->getStatus(),
	                Mage::helper('card')->__('Payment failed!-response code:'.$myArr["Succeed"].'-result:'.$myArr["Result"].'-description:'.$this->getRequest()->getParam('description'))
	            );
				
				$order->setState($model->getConfigData('order_status_payment_refused'), true);
				
		        $order->save();

				$this->_redirect('checkout/onepage/failure');
				
			}
		
	    }
	}
    
	private function _validated()
	{
		$model = Mage::getModel('card/payment');
		
		$result = $this->getRequest()->getParam('result');

		parse_str(base64_decode($result),$myArr);

		//失败原因说明

		//订单号
		$BillNo = $myArr["BillNo"];
		//币种
		$Currency = $myArr["Currency"];
		//金额
		$Amount = $myArr["Amount"];
		//支付状态
		$Succeed = $myArr["Succeed"];
		//支付结果
		$Result = $myArr["Result"];
		//取得的MD5校验信息
		$MD5info = $myArr["MD5info"];
	    $MD5key = trim($model->getConfigData('md5_key'));
		//校验源字符串
	    $md5src = $BillNo.$Currency.$Amount.$Succeed.$MD5key;
	    //MD5检验结果
		$md5sign = strtoupper(md5($md5src)); 
		if (($MD5info == $md5sign)&&($Succeed == '19' || $Succeed == '88')) {
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
		// $session->setQuoteId($session->getFashionpayPaymentQuoteId());
		// $session->unsFashionpayPaymentQuoteId();
		
        $session->setQuoteId($session->getCardPaymentQuoteId());
        $session->unsCardPaymentQuoteId();

		$order = $this->getOrder();
		$standard = Mage::getModel('card/payment');
		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}

		$order->addStatusToHistory(
			$order->getStatus(),
			Mage::helper('card')->__('Customer successfully returned from CreditCard')
		);
        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
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
        $errorMsg = Mage::helper('card')->__(' There was an error occurred during paying process.');

        $order = $this->getOrder();
		$rData  = array();
        if ($this->getRequest()->isPost()) {
			$rData = $this->getRequest()->getPost();
        	$method = 'post';

		} else if ($this->getRequest()->isGet()) {
			$rData = $this->getRequest()->getQuery();
			$method = 'get';

		} else {
			$model->generateErrorResponse();
		}
        if (!$order->getId()) {
            $this->norouteAction();
            return;
        }
        if ($order instanceof Mage_Sales_Model_Order && $order->getId()) {
            $order->addStatusToHistory(
                Mage_Sales_Model_Order::STATE_CANCELED,//$order->getStatus(),
                Mage::helper('card')->__('Customer returned from CreditCard.') . $errorMsg
            );
            //$order->cancel();
            $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, false);
            $order->save();
        }else{
            if(isset($rData['BillNo'])){
                $order = Mage::getModel('sales/order')->loadByIncrementId($rData['BillNo']);
            $order->addStatusToHistory(
                Mage_Sales_Model_Order::STATE_CANCELED,//$order->getStatus(),
                Mage::helper('card')->__('Customer returned from CreditCard.') . $errorMsg
            );
            //$order->cancel();
            //$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, false);
            $order->save();
            }
        }

        $this->loadLayout();
        $this->renderLayout();
        Mage::getSingleton('checkout/session')->unsLastRealOrderId();
    }
}
