<?php

/**
 * ECSHOP CreditCard 支付插件
 * ============================================================================
 * 版权所有 2005-2008 深圳速汇通网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用;
 * ============================================================================
 * $Author: admin_zhang
 */

global $_LANG;
define('MODULE_PAYMENT_CARD_TEXT_IMG','<br/><img src="images/card/card.png"/>');
$_LANG['card']          = 'Visa,Master Credit Card'.MODULE_PAYMENT_CARD_TEXT_IMG;//该支付方式的名称，推荐在后台修改
$_LANG['card_desc']     = 'Visa,Mastercard,Payment online now!' ;
$_LANG['MerNo']    = 'Customer Code';
$_LANG['MD5key']   = 'MD5 Key';
$_LANG['Currency'] = 'Paid Currency';
$_LANG['Currency_range'][1] = '美元';
$_LANG['Currency_range'][2] = '欧元';
$_LANG['Currency_range'][3] = '人民币';
$_LANG['Currency_range'][4] = '英镑';

$_LANG['Currency_range'][5] = '港币';
$_LANG['Currency_range'][6] = '日元';
$_LANG['Currency_range'][7] = '澳元';
$_LANG['Currency_range'][8] = '加元';
$_LANG['Currency_range'][11]= '加元';
$_LANG['Currency_range'][12]= '丹麦克朗';
$_LANG['Currency_range'][13]= '瑞典克朗';
$_LANG['Currency_range'][14]= '新台币';

$_LANG['Rate']   = 'CreditCard币种兑当前默认币种汇率:';

$_LANG['Language'] = 'Language';
$_LANG['language_range']['auto']	= '根据浏览器语言自动选择';
$_LANG['Language_range']['en']		= '英文';
$_LANG['Language_range']['es']		= '西班牙语';
$_LANG['Language_range']['fr']		= '法语';
$_LANG['Language_range']['it']		= '意大利语';
$_LANG['Language_range']['ja']		= '日语';
$_LANG['Language_range']['de']		= '德语';
$_LANG['Language_range']['ko']		= '韩语';
$_LANG['pay_button']			= 'Credit Card Payment online Now';//该值是提交按钮上的文字说明，请您自行修改

$_LANG['TransactionURL']		= '提交地址:';

$_LANG['Returnurl']				= '返回网址:';
$_LANG['Paylog']				= '支付日志:';
$_LANG['Paylog_range'][0]		= 'No';
$_LANG['Paylog_range'][1]		= 'Yes';

?>