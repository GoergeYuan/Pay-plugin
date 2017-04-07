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

require_once CLASS_REALDIR . 'helper/SC_Helper_DB.php';

/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class SC_Helper_DB_Ex extends SC_Helper_DB
{
    public function sfGetBasisData($force = false)
    {
        static $arrData = null;

        if ($force || is_null($arrData)) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();

            $arrData = $objQuery->getRow('*', 'dtb_baseinfo');

            require_once PLUGIN_UPLOAD_REALDIR . 'FashionpayPayment/include.php';
            $arrSubData = SC_Mdl_Fashionpay_Util::getSubData();
            $arrData = array_merge($arrData, $arrSubData);
        }

        return $arrData;
    }
}
