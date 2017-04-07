<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Edit.php';


/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class LC_Page_Admin_Order_Edit_Ex extends LC_Page_Admin_Order_Edit
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . MDL_FASHIONPAY_CODE. "/templates/admin/order_edit.tpl";
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id']: '';
        $this->arrFashionpayOrder = $this->getFashionpayOrder($order_id);
        $this->arrSubdata = SC_Mdl_Fashionpay_Util::getSubData();

        $this->tpl_alert = SC_Mdl_Fashionpay_Util::setAuthorizationAlert($this->arrFashionpayOrder['order_id']);

        switch($this->getMode()) {
            // 実売上
        case 'fashionpay_commit':
            $this->tpl_fashionpay_message = SC_Mdl_Fashionpay_Util::getCaptureRequest($this->arrFashionpayOrder);
            break;
            // 注文キャンセル
        case 'fashionpay_cancel':
            $this->tpl_fashionpay_message = SC_Mdl_Fashionpay_Util::getRefundRequest($this->arrFashionpayOrder);
            break;
            // 金額変更
        case 'fashionpay_change':
            $this->tpl_fashionpay_message = SC_Mdl_Fashionpay_Util::getPartialrefundRequest($this->arrFashionpayOrder);
            $this->arrFashionpayOrder = $this->getFashionpayOrder($order_id);
            break;
        }

        parent::process();
    }

    function getFashionpayOrder($order_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);

        return $arrOrder;
    }

}
