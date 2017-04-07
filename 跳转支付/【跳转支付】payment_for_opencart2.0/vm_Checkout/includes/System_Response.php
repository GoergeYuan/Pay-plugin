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
        '33',
        '44'
        );

    public function check($code){
        if(in_array($code, $this->_code)){
            return false;
        }
        return true;
    }
}