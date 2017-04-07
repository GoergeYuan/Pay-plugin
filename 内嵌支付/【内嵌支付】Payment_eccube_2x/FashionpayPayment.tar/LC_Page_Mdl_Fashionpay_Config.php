<?php
// {{{ requires
require_once CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php";

/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class LC_Page_Mdl_Fashionpay_Config extends LC_Page_Admin_Ex {

     public function __construct() {
        $this->objFormParam = new SC_FormParam();

        $src = PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . "/copy/";
        if ($this->arrSiteInfo['base_language'] == 'ja') {
            $lang = '/';
        } else {
            $lang = '_en-US/';
        }
        $pc = $src . "templates/default".$lang;
        $sp = $src . "templates/sphone".$lang;
        
        //Update file
         $this->arrUpdateFile = array(
            array("src" => $src. "SC_Helper_DB_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "helper_extends/SC_Helper_DB_Ex.php",
                  "disp" => CLASS_EX_REALDIR . "helper_extends/SC_Helper_DB_Ex.php"),
            array("src" => $src. "LC_Page_Shopping_LoadPaymentModule_Ex.php",  //兼容2.13X重新载入module_path
                  "dst" => CLASS_EX_REALDIR . "page_extends/shopping/LC_Page_Shopping_LoadPaymentModule_Ex.php",
                  "disp" => CLASS_EX_REALDIR . "page_extends/shopping/LC_Page_Shopping_LoadPaymentModule_Ex.php"),
            array("src" => $src. "LC_Page_Admin_Order_Edit_Ex.php",
                  "dst" => CLASS_EX_REALDIR . "page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php",
                  "disp" => CLASS_EX_REALDIR . "page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php"),
            array("src" => $src. "img/cvv_help.gif",
                  "dst" => IMAGE_SAVE_REALDIR . "cvv_help.gif",
                  "disp" => IMAGE_SAVE_REALDIR . "cvv_help.gif"),
            array("src" => $src. "img/cvv_ico.jpg",
                  "dst" => IMAGE_SAVE_REALDIR . "cvv_ico.jpg",
                  "disp" => IMAGE_SAVE_REALDIR . "cvv_ico.jpg"),
            array("src" => $src. "img/cvv_help.html",
                  "dst" => IMAGE_SAVE_REALDIR . "cvv_help.html",
                  "disp" => IMAGE_SAVE_REALDIR . "cvv_help.html"),
            array("src" => $src. "img/logo_firstteam.png",
                  "dst" => IMAGE_SAVE_REALDIR . "logo_firstteam.png",
                  "disp" => IMAGE_SAVE_REALDIR . "logo_firstteam.png"),
            array("src" => $src. "img/securitycode.gif",
                  "dst" => IMAGE_SAVE_REALDIR . "securitycode.gif",
                  "disp" => IMAGE_SAVE_REALDIR . "securitycode.gif")
        );

     }

    public function init() {
        parent::init();
        $this->module_title = MDL_FASHIONPAY_MODULE_TITLE . " Payment Setting";
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE. "/templates/admin/config.tpl";
        $this->tpl_subtitle = $this->module_title;
        

        // currency option
        $this->arrBaseCurrency = array(
            'EUR'=>'EUR', 
            'USD'=>'USD', 
            'JPY'=>'JPY', 
            'GBP'=>'GBP', 
            'AUD'=>'AUD', 
            'CAD'=>'CAD', 
            'NOK'=>'NOK',
            'CNY'=>'CNY',
            'DKK'=>'DKK',
            'HKD'=>'HKD',
            'SEK'=>'SEK', 
            'TWD'=>'TWD',
            'RUB'=>'RUB'
        );
        //base_language
        $this->arrBaseLanguage = array('ja'=>'ja','en'=>'en');
        //order status option
        $this->arrBaseStatus = array(
            ''=>'Please make a selection',
            '1'=>'New order received',
            '2'=>'Waiting for deposit',
            '3'=>'Cancel',
            '4'=>'Being backordered',
            '5'=>'Shipped',
            '6'=>'Deposited',
            '7'=>'Payment being processed'
        ); 
        $this->gatewayUrl = MDL_FASHIONPAY_GATEWAY_RUL;
        $this->returnUrl  = 'http://'.$_SERVER['HTTP_HOST'].'/shopping/load_payment_module.php';
        $this->updateTable();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     * Action
     * @return void
     */
    public function action() {

        $this->initParam();
        $this->objFormParam->setParam($_POST);
        
        //[edit] fashionpay暂时不支持在线更新
        switch ($this->getMode()) {
        case 'edit':
            $this->arrErr = $this->doCheckError();
            if (empty($this->arrErr)) {
               $this->registerData();
                if ($this->updateFile()) {
                    if (constant('LANG_CODE') == 'ja') {
                        $this->tpl_onload .= 'alert("登録完了しました。\n基本情報＞支払方法設定より詳細設定をしてください。"); window.close();';
                    } else {
                        $this->tpl_onload .= 'alert("Save is complete. \n Please do more than the basic configuration information> payment method setting."); window.close();';
                    }
                } else {
                    // javascript実行
                    foreach($this->arrUpdateFile as $array) {
                        if(!is_writable($array['dst'])) {
                            if (constant('LANG_CODE') == 'ja') {
                                $alert = $array['dst'] . "に書き込み権限を与えてください。";
                            } else {
                                $alert = 'Grant write access to file setting ' . $array['dst']; // 書き込み権限を与えてください
                            }
                            $this->tpl_onload.= "alert(\"". $alert. "\");";
                        }
                    }
                } 
               /*  $alert = 'Fashionpay does not support updates';
                $this->tpl_onload.= "alert(\"". $alert. "\");"; */
            }
            break;
        default:
           // setParam admin
            $arrSubData = SC_Mdl_Fashionpay_Util::getSubData();
            $this->objFormParam->setParam($arrSubData);
            break;
        }

        $this->arrForm = $this->objFormParam->getFormParamList();
        $this->setTemplate($this->tpl_mainpage);
    }

    
    /**
     * admin option 
     */
    private function initParam() {
        $this->objFormParam->addParam("Merchant ID", "merchant_id", 20, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Merchant Md5key", "merchant_md5key", 20, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Base Currency", "base_currency", 3, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Language", "base_language", 2, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Status of New Order", "new_order", 25, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Status of Checkout Completion", "succss_order", 25, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("Status of Checkout Fail", "fail_order", 25, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("GateWay Url", "gateway_url", 100, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("Return Url", "return_url", 255, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "URL_CHECK"));
    }

    
    /** 
     * Plug-in installation（save data）
     */
    public function registerData() {

        $objSess = new SC_Session_Ex();
        $arrForm = $this->objFormParam->getDbArray();

        $objQuery =& SC_Query::getSingletonInstance();
        $objQuery->begin();
        // sub_dataの保存
        $sqlval['sub_data'] = serialize($arrForm);
        $objQuery->update("dtb_module", $sqlval, "module_code = ?", array(MDL_FASHIONPAY_CODE));

        // dtb_paymentの登録
        $arrData = array();
        $arrData['payment_method'] = MDL_FASHIONPAY_PAYMENT_METHOD;
        $arrData['memo03'] = MDL_FASHIONPAY_CODE; // 値は多分なんでもいい
        $arrData['fix'] = 3;
        $arrData['creator_id'] = $objSess->member_id;
        $arrData['update_date'] = 'CURRENT_TIMESTAMP';
        $arrData['module_path'] = PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . '/payment.php';
        $arrData['module_code'] = MDL_FASHIONPAY_CODE;
        $arrData['del_flg'] = '0';
        $arrData['payment_image'] = 'logo_firstteam.png';
        $arrData['payment_id'] = $objQuery->get('payment_id', 'dtb_payment', 'module_code = ?', array(MDL_FASHIONPAY_CODE));
        SC_Helper_Payment_Ex::save($arrData);

        
        $objQuery->commit();
    }

    private function updateTable() {
        $objDB = new SC_Helper_DB_Ex();
        $objDB->sfColumnExists("dtb_payment", "module_code", "text", "", true);
    }


    /**
     * ファイルを更新
     * Update file
     */
    private function updateFile(){
        foreach ($this->arrUpdateFile as $array) {
            $dst_file = $array['dst'];
            $src_file = $array['src'];
            // ファイルが異なる場合
            if (!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                SC_Utils_Ex::sfMakeDir($dst_file);
                if (is_writable($dst_file) || is_writable(dirname($dst_file))) {
                    copy($src_file, $dst_file);
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    
    /**
     * admin option check error
     */
    public function doCheckError()
    {

       // $arrForm = $this->objFormParam->getHashArray();ss
        return $this->objFormParam->checkError();
    }
    

}
