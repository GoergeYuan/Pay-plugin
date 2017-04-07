<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_LoadPaymentModule.php';

/*
 * 2.12.x までは dtb_payment.module_path がフルパスとなっていた.
 * 2.13.x より, MODULE_REALDIR からのパスでも対応できるよう修正
 * http://svn.ec-cube.net/open_trac/ticket/2292
 */

/**
 * 決済モジュールの呼び出しを行うクラス(拡張).
 *
 * LC_Page_Shopping_LoadPaymentModule をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author Kentaro Ohkouchi
 * @version $Id$
 */
class LC_Page_Shopping_LoadPaymentModule_Ex extends LC_Page_Shopping_LoadPaymentModule
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
   
    }


    /**
 * 受注IDをキーにして, 決済モジュールのパスを取得する.
 *
 * 決済モジュールが取得できた場合は, require 可能な決済モジュールのパスを返す.
 * 受注IDが無効な場合, 取得したパスにファイルが存在しない場合は false
 *
 * @param integer $order_id 受注ID
 * @return string|boolean 成功した場合は決済モジュールのパス;
 *                        失敗した場合 false
 */
    function getModulePath($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sql = <<< __EOS__
            SELECT module_path
            FROM dtb_payment T1
                JOIN dtb_order T2
                    ON T1.payment_id = T2.payment_id
            WHERE order_id = ?
__EOS__;
        $module_path = $objQuery->getOne($sql, array($order_id));
        if (file_exists($module_path)) {
            return $module_path;
        }
        return false;
    }


}
