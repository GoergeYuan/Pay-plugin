<?php
/**
 *
 * @package    Company
 * 
 * @author     xinhaozheng@gmail.com
 */
class Company_Card_Model_Source_Language
{
    public function toOptionArray()
    {
        return array(
			array('value' => ''	,  'label' => Mage::helper('card')->__('auto select')),
            array('value' => 'EN', 'label' => Mage::helper('card')->__('English')),
            array('value' => 'FR', 'label' => Mage::helper('card')->__('French')),
            array('value' => 'DE', 'label' => Mage::helper('card')->__('German')),
            array('value' => 'IT', 'label' => Mage::helper('card')->__('Italian')),
            array('value' => 'ES', 'label' => Mage::helper('card')->__('Spain')),
            array('value' => 'NL', 'label' => Mage::helper('card')->__('Dutch')),
        );
    }
}



