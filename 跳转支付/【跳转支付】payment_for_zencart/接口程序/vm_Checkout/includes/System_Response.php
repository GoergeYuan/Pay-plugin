<?php
class System_Response{
    
    private $_code = array(
        '-1',
        '10',
        '11',
        '12',
        '13',
        '14',
        '15',
        '16',
        '22',
        '25',
        '26',
        '27',
        '28',
        '38',
        '44'
        );
    

   private $_codeinfo = array(
            '-1' =>'Order Number Error',
            '0' => 'Payment Fail',
            '1' => 'High Risk',
            '2' => 'Black Card',
            '3' => 'More than single limit',
            '4' => 'Exceeding monthly transaction limit',
            '5' => 'IP repeat business',
            '6' => 'Email repeat business',
            '7' => 'Card repeat business',
            '8' => 'COOKIE repeat business',
            '9' => 'Intermediate Risk',
            '10' => 'Merchant number  does not exist',
            '11' => 'Merchant MD5KEY does not exist',
            '12' => 'Money is not set',
            '13' => 'MD5 Validation Error',
            '14' => 'Return URL is not registered',
            '15' => 'Merchant not open',
            '16' => 'Channel not open',
            '17' => 'Black Card',
            '19' => 'Abnormal Order',
            '22' => 'Website not registered',
            '24' => 'Transactional number repeat',
            '25' => 'Amount error',
            '26' => 'CVV2 , card number or expiration date of the error',
            '27' => 'Payment number does not exist',
            '28' => 'Transactional number repeat',
            '30' => 'Phone number repeat business',
            '31' => 'Prohibit trading area',
            '32' => 'Transactional number repeat',
            '34' => 'Black email',
            '35' => 'Black IP',
            '38' => 'Product information can not be empty',
            '44' => 'Black site',
            '88'   => 'payment successful'
    
        );
  
    /**
     * 检测可弹窗的警告粗错误码
     * @param unknown $code
     */
    public function check($code){
        if(in_array($code, $this->_code)){
            return false;
        }
        return true;
    }
    
    /**
     * 支付失败说明
     * @param unknown $code
     * @param string $defaultInfo
     */
    public function checkinfo($code,$defaultInfo = 'Unknown cause')
    {
        return (array_key_exists($code, $this->_codeinfo)) ? $this->_codeinfo[$code] : $defaultInfo;
    }


}