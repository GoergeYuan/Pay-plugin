<?php
 /**
 * CosmoCommerce
 *
 * NOTICE OF LICENSE
 * CosmoCommerce Commercial License 
 * support@cosmocommerce.com
 *
 * @category   CosmoCommerce
 * @package    CosmoCommerce_fashionpay
 * @copyright  Copyright (c) 2009 CosmoCommerce,LLC. (http://www.cosmocommerce.com)
 * @license	     CosmoCommerce Commercial License(http://www.cosmocommerce.com/cosmocommerce_commercial_license.txt)
 */

/**
 * Redirect to fashionpay
 *
 * @category   Mage
 * @package    CosmoCommerce_card
 * @author     CosmoCommerce  <sales@cosmocommerce.com>
 */
class Company_Card_Block_Redirect extends Mage_Core_Block_Abstract
{

	protected function _toHtml()
	{
		$standard = Mage::getModel('card/payment');
        $form = new Varien_Data_Form();
        $form->setAction($standard->getCardUrl())
            ->setId('card_payment_checkout')
            ->setName('card_payment_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }

        $formHTML = $form->toHtml();

        $html = '<html><body><h1 align="center">';
        $html.= $this->__('You will be redirected to pay in a few seconds.');
		$html.= '</h1>';
        $html.= $formHTML;
        $html.= '<script type="text/javascript">document.getElementById("card_payment_checkout").submit();</script>';
        $html.= '</body></html>';


        return $html;
    }
}