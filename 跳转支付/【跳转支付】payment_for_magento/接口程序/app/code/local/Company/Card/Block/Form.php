<?php
/**
 *
 * @package    Company
 * 
 * @author     xinhaozheng@gmail.com
 */
class Company_Card_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('card/form.phtml');
        parent::_construct();
    }

}