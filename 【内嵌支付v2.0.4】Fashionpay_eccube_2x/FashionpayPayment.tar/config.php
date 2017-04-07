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
require_once realpath(dirname( __FILE__)) . "/include.php";
require_once realpath(dirname( __FILE__)) . "/LC_Page_Mdl_Fashionpay_Config.php";

$objPage = new LC_Page_Mdl_Fashionpay_Config();
$objPage->init();
$objPage->process();
