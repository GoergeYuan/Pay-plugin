<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// | Simplified Chinese version   http://www.zen-cart.cn                  |
// +----------------------------------------------------------------------+
//  $Id: FIRSTTEAM.php v1.0 2014-10-23 Shell·Wang $


  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_ADMIN_TITLE', 'FashionPay Payment(v2.0.4)');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT', 'Checkout with CC');
  define('MODULE_PAYMENT_FIRSTTEAM_ACCEPTANCE_MARK_TEXT', '');
  

  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_01', 'Enable FashionPay Payment(v2.0.4) Module');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_02', 'If you would like to open payment mode.');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_MID_01', 'Merchant ID');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_MID_02', 'FashionPay Merchant No.');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_PRIVATEKEY_01', 'Security Key');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_PRIVATEKEY_02', 'FashionPay Private Key.');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_ZONE_01', 'Payment Zone');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_ZONE_02', 'If a zone is selected, only enable this payment method for that zone.');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_ID_01', 'Order Orginal Status');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_STATUS_ID_02', '<code>Set the status of orders made with this payment module to this value (Please choose Pending).</code>');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_SORT_01', 'Display Sequence');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_SORT_02', 'The Sequence of payment method, the lower rank first.');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_HANDLER_01', 'Payment Gateway URL');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_HANDLER_02', 'FashionPay Payment Gateway URL.<br />Default: <code>hhttp://ssl.hpolineshop.com/sslWebsitpayment</code>');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_RETURNURL_01', 'Payment Return URL');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_RETURNURL_02', 'FashionPay Payment Return URL');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_CARD_TYPE_01', 'Card Type');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CONFIG_CARD_TYPE_02', 'Allow payment card type.');
 //支付页面titel信息 
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CATALOG_TITLE', 'Zahlung per Kreditkarte');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_DESCRIPTION', '<a target="_blank" href="http://manager.mytradeshops.com/newReg.jsp">点这里申请帐号</a><br /><br /><a target="_blank" href="http://manager.mytradeshops.com/merchant/">点击登录速汇通商户页面</a><br /><br /><strong>要求:</strong><br /><hr />*<strong>速汇通商户号</strong> (申请见上面的链接)<br />*<strong>需要服务器开启curl</strong>且必须和SSL编译到PHP中<br />*<strong>速汇通用户名和交易密钥</strong>在商户页面');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_NUMBER', 'Kartennummer:');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_CVV', 'CVV2/CSC:');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_EXPIRES', 'Haltbarkeitsdatum:');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_CREDIT_CARD_ISSUING_BANK', 'Ausstellende Bank:');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_MONTH', 'Monat');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_YEAR', 'Jahr');
 //支付返回结果信息 
  
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_MIDDLE_DIGITS_MESSAGE', 'Bitte leiten Sie diese E-Mail an die Buchhaltung, damit sie zusammen mit der Online-Bestellung, auf die sie sich bezieht, eingereicht werden kann: ' . "\n\n" . 'Auftrag: %s' . "\n\n" . 'Mittlere Ziffern: %s' . "\n\n");
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_SUCCESS_MESSAGE',
	'<div style="background:#ffedde;padding:10px;line-height:15px">
		<div style="font-size: 16px; font-weight: bold;">
			<span style="background-color: green; color: #FFFFFF;">%s</span>
		</div><br/>
		<div style="padding:3px;">Zahlungsbetrag :%s</div>
		<div style="padding:3px;">Zahlungsstatus :%s</div>
		<div style="padding:3px;">Produktname   :%s</div><br/>
	</div>');
 define('SP_PAYRESULT_Error_code','Fehlercode: ');
 define('SP_PAYRESULT_SUCCESS','Herzlichen Glückwunsch, die Zahlung ist erfolgreich!');
 define('SP_PAYRESULT_FAIL','Leider ist die Zahlung Fehler, der Grund ist: ');
 define('SP_PAYRESULT_WARNING','Entschuldigung, die Zahlung ist fehlgeschlagen, der Grund ist, dass die Validierung der Daten fehlgeschlagen ist, wenden Sie sich bitte an den Einkäufer!');
 define('SP_PAYRESULT_PROCESSING','Warten Sie einen Moment, wird der Auftrag verarbeitet, don\'t zahlen später wieder!');


//异常卡信息
  define('FIRSTTEAM_PAYMENT_ERROR_CVV', 'Cvv / Csc-Code ist falsch !');
  define('FIRSTTEAM_PAYMENT_ERROR_CARD', 'Die Kreditkartennummer ist nicht korrekt !');
  define('FIRSTTEAM_PAYMENT_ERROR_CARDTYPE', 'Der Kreditkartentyp wird nicht unterstützt !');
  define('FIRSTTEAM_PAYMENT_ERROR_YEAR', 'Das Verfallsdatum ist nicht korrekt !');
  define('FIRSTTEAM_PAYMENT_ERROR_MONTH', 'Der Monat des Verfalldatums ist falsch !');
  define('FIRSTTEAM_PAYMENT_ERROR_EXPIRE', 'Das Ablaufdatum ist abgelaufen !');
  define('FIRSTTEAM_PAYMENT_ERROR_NONE_CARD', 'Die Zahlung wird nicht unterstützt jede Art von Karte, kontaktieren Sie bitte die Webshop-Kundenservice-Center !\n'); 
  define('FIRSTTEAM_PAYMENT_ERROR_CARD_TYPE', 'Der Kreditkartentyp wird nicht unterstützt !\n');
  define('FIRSTTEAM_PAYMENT_ERROR_CARD_ALLOW','Wir unterstützen den Kartentyp : ');


//JS异常卡信息
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_NUMBER', '* Karten-Nr. Ist Fehler!\n');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_CVV', '* CVV2 / CSC ist Fehler!\n');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_EXPIRES_MONTH', '* Kreditkarte läuft Monat ist ein Fehler!\n');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_EXPIRES_YEAR', '* Kreditkarte läuft Jahr ist Fehler!\n');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_ISSUING_BANK', '* Kreditkarte Ausstellende Bank Ist Fehler!\n');
  define('MODULE_PAYMENT_FIRSTTEAM_TEXT_JS_SP_RESUBMIT', 'Warten Sie einen Moment, der Auftrag wird verarbeitet, don\'t senden Zahlung wieder!');

//JS文件路径
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_CARD_JS_SRC', DIR_WS_MODULES . 'payment/js/sc_card.js');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_CARD_JS', '<script src="'.MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_CARD_JS_SRC.'" type="text/javascript"></script>');

  //图片路径
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_V', DIR_WS_MODULES . 'payment/FirstTeam/img/visa_70.png');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_M', DIR_WS_MODULES . 'payment/FirstTeam/img/mastercard_70.png');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_J', DIR_WS_MODULES . 'payment/FirstTeam/img/jcb_70.png');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_A', DIR_WS_MODULES . 'payment/FirstTeam/img/american_express_70.png');

  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_V', 'visa');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_M', 'mastercard');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_J', 'jcb');
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_A', 'AE');

  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_V_LOGO', '<img src="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_V . '" alt="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_V . '"  /> &nbsp;' );
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_M_LOGO', '<img src="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_M . '" alt="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_M . '"  /> &nbsp;' );
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_J_LOGO', '<img src="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_J . '" alt="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_J . '"  /> &nbsp;' );
  define('MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_A_LOGO', '<img src="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_IMG_A . '" alt="' . MODULE_PAYMENT_FIRSTTEAM_MARK_BUTTON_ALT_A . '"  /> &nbsp;' );
  

?>