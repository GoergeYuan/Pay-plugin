<?php

/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class SC_Mdl_Fashionpay_Util {

    public static function getSubData() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $subdata = $objQuery->get("sub_data", "dtb_module", "module_code = ?", array(MDL_FASHIONPAY_CODE));

        if (isset($subdata)) {
            return unserialize($subdata);
        }
    }




    public function initPage() {
         //initPage
    }


    public static function getCaptureRequest($arrOrder) {
        //getCaptureRequest

    }

    public static function getRefundRequest($arrOrder) {

     //getRefundRequest
    }
    public static function getPartialrefundRequest($arrOrder) {
        //getPartialrefundRequest

    }

    function setAuthorizationAlert($order_id = null) {
        //setAuthorizationAlert

    }

}
