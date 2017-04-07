<?php
/**
 *
 * @package    Company
 * 
 * @author     xinhaozheng@gmail.com
 */
class Company_Card_Model_Source_Transport
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'https', 'label' => Mage::helper('card')->__('https')),
            array('value' => 'http', 'label' => Mage::helper('card')->__('http')),
        );
    }
}