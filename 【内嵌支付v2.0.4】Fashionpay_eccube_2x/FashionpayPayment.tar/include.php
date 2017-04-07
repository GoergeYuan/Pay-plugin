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
define("MDL_FASHIONPAY_CODE", "FashionpayPayment");
define("MDL_FASHIONPAY_MODULE_TITLE", "Fashionpay");
//define("MDL_FASHIONPAY_PAYMENT_METHOD", "Fashionpay Payment");
define("MDL_FASHIONPAY_PAYMENT_METHOD", "Credit Card");
define("MDL_FASHIONPAY_GATEWAY_RUL", "http://ssl.hpolineshop.com/sslWebsitpayment");
// オーソリの有効期限   授权的截止日期
define("FASHIONPAY_EXPIRATION_DATE", "20");
// 失効アラートを出す期間    期间发出警报撤销
define("FASHIONPAY_ALERT_DATE", "7");


require_once realpath(dirname(__FILE__)) . '/SC_Mdl_Fashionpay_Util.php';