<?php
header("Content-Type: text/html; charset=utf-8");

define('PAY_DEBUG', FALSE);

if(defined('PAY_DEBUG') && PAY_DEBUG){
	error_reporting(E_ALL);
	if(function_exists('ini_set')){
		@ini_set('display_errors', 'On');
	}
} else {
	error_reporting(0);
}

if(version_compare(phpversion(), '5.3.0', '<') == true){
	
	if(ini_get('magic_quotes_gpc')){
		function clean($data){
			if(is_array($data)){
				foreach($data as $key=> $value){
					$data[clean($key)] = clean($value);
				}
			} else {
				$data = stripslashes($data);
			}
			
			return $data;
		}

		$_POST = clean($_POST);
	}
}

date_default_timezone_set('PRC');

define('VERSION_INFO', '2.0');
define('DIR_PAY', str_replace('\\', '/', dirname(__FILE__)) .'/');
define('DIR_PAY_INCLUDES', DIR_PAY . 'includes/');
define('DIR_PAY_VIEW', DIR_PAY . 'view/');
define('DIR_PAY_LANG', DIR_PAY . 'lang/');
define('DEFAULT_PAY_LANG', 'en');
define('HTTP_RESPONSE_LOCK_FILE', DIR_PAY . 'lock');
define('RESPONSE_LOG_FILE', DIR_PAY . 'log.txt');
define('GATEWAY_URL', 'http://ssl.hpolineshop.com/sslWebsitpayment');
define('HTTP_TIMEOUT', 180);

if(function_exists('ignore_user_abort')){
	ignore_user_abort(TRUE);
}

if (function_exists('ini_get')){
	
	if(!ini_get('safe_mode') && function_exists('set_time_limit')){
		set_time_limit(intval(HTTP_TIMEOUT) + 10);
	}
	
	if(intval(ini_get('max_execution_time')) < HTTP_TIMEOUT){
		if(function_exists('ini_set')){
			@ini_set('max_execution_time', intval(HTTP_TIMEOUT) + 10);
		}
	}
}

require DIR_PAY_INCLUDES . 'functions.php';
require DIR_PAY_INCLUDES . 'Process_Query.php';
require DIR_PAY_INCLUDES . 'Http_Client.php';
require DIR_PAY_INCLUDES . 'Http_Curl_Query.php';
require DIR_PAY_INCLUDES . 'Mobile_Detect.php';
require DIR_PAY_INCLUDES . 'System_Response.php';

$lang2 = varGet($_REQUEST, 'Language');
$langMap = require DIR_PAY_INCLUDES . 'Language_Mapping.php';
$langData = array();

if(!$lang2){
	$lang2 = getLanguage();
}

$lang2 = strtolower($lang2);
$setDefaultLanguage = varGet($langMap, DEFAULT_PAY_LANG);
$setLanguage = trim(varGet($langMap, $lang2));
$setLanguage = strtolower($setLanguage);

$langFileName = '';

$langList = array($setLanguage, $lang2, $setDefaultLanguage, 'en-us');

foreach($langList as $langName){
	if($langName){
		$langName .= '/';
	}
	
	$langFileNameTemp = DIR_PAY_LANG . $langName . 'language.php';

	if (file_exists($langFileNameTemp)) {
		$langFileName = $langFileNameTemp;
		break;
	}
}

if($langFileName && file_exists($langFileName)){
	$langData = require $langFileName;
}

function getResource($fileName){
	return './vm_Checkout/' . ltrim($fileName, '/');
}