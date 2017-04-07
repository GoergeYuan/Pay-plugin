<?php
// {{{ requires
require_once realpath(dirname(__FILE__)) . '/include.php';

/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class FashionpayPayment extends SC_Plugin_Base {

    /**
     * コンストラクタ
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $module_id = $objQuery->getOne("select max(module_id) + 1 as module_id_max from dtb_module");
        $arrModule = array();
        $arrModule['module_id']   = $module_id;
        $arrModule['module_code'] = MDL_FASHIONPAY_CODE;
        $arrModule['module_name'] = MDL_FASHIONPAY_PAYMENT_METHOD;
        //$arrModule['sub_data']    = '';
        $arrModule['auto_update_flg'] = 0;
        $arrModule['del_flg']         = 0;
        $arrModule['create_date'] = 'CURRENT_TIMESTAMP';
        $arrModule['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->insert('dtb_module', $arrModule);
        copy(PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE . "/logo.png",
             PLUGIN_HTML_REALDIR . MDL_FASHIONPAY_CODE . "/logo.png");
    }

    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function uninstall($arrPlugin) {
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DELETE FROM dtb_module WHERE module_code = ?", array(MDL_FASHIONPAY_CODE));
        $objQuery->query("DELETE FROM dtb_payment WHERE memo03 = ?", array(MDL_FASHIONPAY_CODE));
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {
        // nop
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DELETE FROM dtb_module WHERE module_code = ?", array(MDL_FASHIONPAY_CODE));
        $objQuery->query("DELETE FROM dtb_payment WHERE memo03 = ?", array(MDL_FASHIONPAY_CODE));
    }

    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }
}
