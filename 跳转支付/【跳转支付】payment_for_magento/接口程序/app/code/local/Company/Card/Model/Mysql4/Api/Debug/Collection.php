<?php
/**
 *
 * @package    Company
 * 
 * @author     xinhaozheng@gmail.com
 */
 
class Company_Card_Model_Mysql4_Api_Debug_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('card/api_debug');
    }
}