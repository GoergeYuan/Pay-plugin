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
 
class FirstTeam_Fashionpay_Model_Source_Servicetype
{
    public function toOptionArray()
    {
        return array(
            array('value' => '3', 'label' => Mage::helper('Fashionpay')->__('接口类型3')),
            array('value' => '5', 'label' => Mage::helper('Fashionpay')->__('接口类型5')),
        );
    }
}



