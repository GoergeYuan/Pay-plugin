<?php

header("Content-type: text/html; charset=utf-8");
function_exists('ignore_user_abort') && ignore_user_abort(false);
session_start();

define('VERSION_INFO', '1.73');
define('DIR_PAY', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('DIR_PAY_INCLUDED', DIR_PAY . 'includes/');
define('DIR_PAY_VIEW', DIR_PAY . 'view/');
define('GATEWAY_URL', 'http://hpolineshop.com/sslWebsitpayment');
define('DIR_PAY_LANG', DIR_PAY . 'lang/');
// define('DIR_PAY_VIEW_FILE', DIR_PAY_VIEW . 'pay.php');
define('PAY_USE_TOKEN', FALSE);
define('FORCED_TO_USE_DEFAULT_LANGUAGE', false);
define('DEFAULT_PAY_LANGUAGE', 'en');

define('PAY_DEBUG', false);

if(PAY_DEBUG){
    error_reporting(E_ALL);
    @ini_set('display_errors', 'On');
}else{
    error_reporting(0);
}

$httpTimeOut = 180;

define('HTTP_TIMEOUT', $httpTimeOut);

if(!ini_get('safe_mode')){
    @set_time_limit((HTTP_TIMEOUT + 10));
}
if(ini_get('max_execution_time') < HTTP_TIMEOUT){
    @ini_set('max_execution_time', (HTTP_TIMEOUT + 10));
}


define('VISA', true);
define('MASTER', false);

$lhtml =<<<STR
    <script type="text/javascript">
        //document.getElementById("loadingID").style.display = "none;";
        document.getElementById("loadingID").className = "hiddenB";
    </script>
    </body>
</html>
STR;

$fileTemplate = <<<STR
<!DOCYTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>%1\$s</title>
        <style type="text/css">
        body{text-align: center;}
        .content{margin: 0 auto; width: 100%%;}
        h1, h2, h3, h4, h5, h6{width: 100%%;}
        </style>
    </head>
    <body>
        <div class="content">
            %2\$s
        </div>
</html>
STR;

require DIR_PAY_INCLUDED . 'Http_Curl_Query.php';
require DIR_PAY_INCLUDED . 'Http_Client.php';
require DIR_PAY_INCLUDED . 'System_Response.php';

// Forced to use language with chinese
// $_REQUEST['Language'] = 'zh-cn';



$langFilePath = DEFAULT_PAY_LANGUAGE;
$langData = array();
$langMap = require DIR_PAY_INCLUDED . 'Language_Mapping.php';
$lang = DEFAULT_PAY_LANGUAGE;

if(FORCED_TO_USE_DEFAULT_LANGUAGE == false){
    $lang = varGet($_REQUEST, 'Language');
    $lang = strtolower($lang);
    if(!$lang){
        $lang = getLanguage();
    }
}

if(file_exists(DIR_PAY_LANG . $lang . '/language.php')){
    $langFilePath = $lang;
}else if(isset($langMap[$lang]) && $langMap[$lang] && file_exists(DIR_PAY_LANG . strtolower($langMap[$lang]) . '/language.php')){
        $langFilePath = strtolower($langMap[$lang]);
} else {
    $langFilePath = varGet($langMap, DEFAULT_PAY_LANGUAGE) ? varGet($langMap, DEFAULT_PAY_LANGUAGE) : 'en-us';
}

if(!is_file(DIR_PAY_LANG . $langFilePath . '/language.php')){
    $langFilePath = 'en-us';
}

$langData = require DIR_PAY_LANG . $langFilePath . '/language.php';


// $langData = loadLanguage(varGet($_REQUEST, 'Language'));

function loadLanguage($lang = ''){
    $langFilePath = DEFAULT_PAY_LANGUAGE;
    $langData = array();
    $langMap = require DIR_PAY_INCLUDED . 'Language_Mapping.php';

    $lang = strtolower($lang);
    if(!$lang){
        $lang = getLanguage();
    }

    if(file_exists(DIR_PAY_LANG . $lang . '/language.php')){
        $langFilePath = $lang;
    }else if(isset($langMap[$lang]) && $langMap[$lang]){
        $lang2 = strtolower($langMap[$lang]);
        if(file_exists(DIR_PAY_LANG . $lang2 . '/language.php')){
            $langFilePath = $lang2;
        }
    }

    // if($langFilePath == 'zh-cn'){
    //     $langFilePath = DEFAULT_PAY_LANGUAGE;
    // }

    $langData = require DIR_PAY_LANG . $langFilePath . '/language.php';
    return $langData;
}

function getLanguage(){
    if(isset($COOKIE['fpay_pay_language'])){
        $langSet = $_COOKIE['fpay_pay_language'];
    }else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
        preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $langSet = $matches[1];
        setcookie('fpay_pay_language', $langSet, time() + 3600);
    }else{
        $langSet = DEFAULT_PAY_LANGUAGE;
    }
    return $langSet;
}

function renderFile($viewFile, $data){
    $paymentHtml = file_get_contents($viewFile);
    $placeList = array();

    foreach((array)$data as $key=> $value){
        $placeList['{' . $key . '}'] = $value;
    }

    return strtr($paymentHtml, $placeList);
}


function paymentSubmitCreateToken(&$data){
    $encryptStr = session_id() . $data['MerNo'] . $data['BillNo'] . $data['Amount'];
    $token = strtoupper(md5($encryptStr));
    $data['order_token'] = $token;
    $_SESSION['order_token'] = $token;
    return $token;
}

function paymentSubmitQueryCheckSubmitted($data){
    $token = varGet($data, 'order_token');

    if(PAY_USE_TOKEN == false){
        return true;
    }

    if(!$token or !isset($_SESSION['order_token']) or $_SESSION['order_token'] != $token ){
        return false;
    }else{
        unset($_SESSION['order_token']);
        return true;
    }
}

function varGet($data, $key, $defaultValue = ''){
    return (isset($data[$key])) ? $data[$key] : $defaultValue;
}

function httpDebug($msg, $object = false) {

        print '<div style="border: 1px solid red; padding: 0.5em; margin: 0.5em;"><strong>Http Debug:</strong> '.$msg;
        if ($object) {
            ob_start();
            print_r($object);
            $content = htmlentities(ob_get_contents());
            ob_end_clean();
            print '<pre>'.$content.'</pre>';
        }
        print '</div>';
} 

function createYear($len = 20 ,$label = "Year"){
    $start = date('Y');
    $yearList = array();
    $yearList[""] = $label;

    for($i = 0; $i < $len; $i ++){
        $key = intval($start) + $i;
        $yearList[$key] = $key;
    }
    return $yearList;
}

function createMonth($label = "Month"){
    $monthList = array();
    $monthList[""] = $label;
    for($i = 1; $i <= 12; $i++){
        $key = $i;
        if($i< 10){
            $key = "0" . $i;
        }
        $monthList[$key] = $key;
    }
    return $monthList;
}

function getCountryList($label = 'Please Select Country'){
    return array(
    '' => $label,
    'United States' => 'United States',
    'United Kingdom' => 'United Kingdom',
    'Australia' => 'Australia',
    'France' => 'France',
    'Germany' => 'Germany',
    'Canada' => 'Canada',
    'Japan' => 'Japan',
    'Afghanistan' => 'Afghanistan',
    'Albania' => 'Albania',
    'Algeria' => 'Algeria',
    'Andorra' => 'Andorra',
    'Angola' => 'Angola',
    'Antigua and Barbuda' => 'Antigua and Barbuda',
    'Argentina' => 'Argentina',
    'Armenia' => 'Armenia',
    'Aruba' => 'Aruba',
    'Austria' => 'Austria',
    'Azerbaijan' => 'Azerbaijan',
    'Bahamas' => 'Bahamas',
    'Bahrain' => 'Bahrain',
    'Bangladesh' => 'Bangladesh',
    'Barbados' => 'Barbados',
    'Belgium' => 'Belgium',
    'Belize' => 'Belize',
    'Benin' => 'Benin',
    'Bermuda' => 'Bermuda',
    'Bhutan' => 'Bhutan',
    'Bolivia' => 'Bolivia',
    'Bosnia Herzegovina' => 'Bosnia Herzegovina',
    'Botswana' => 'Botswana',
    'Brazil' => 'Brazil',
    'Brunei' => 'Brunei',
    'Bulgaria' => 'Bulgaria',
    'Burkina Faso' => 'Burkina Faso',
    'Burundi' => 'Burundi',
    'Cambodia' => 'Cambodia',
    'Cameroon' => 'Cameroon',
    'Cape Verde' => 'Cape Verde',
    'Cayman Islands' => 'Cayman Islands',
    'Central African Republic' => 'Central African Republic',
    'Chad' => 'Chad',
    'Chile' => 'Chile',
    'China' => 'China',
    'Colombia' => 'Colombia',
    'Comoros' => 'Comoros',
    'Congo' => 'Congo',
    'Costa Rica' => 'Costa Rica',
    'Croatia' => 'Croatia',
    'Cyprus' => 'Cyprus',
    'Czech Republic' => 'Czech Republic',
    'Denmark' => 'Denmark',
    'Djibouti' => 'Djibouti',
    'Dominica' => 'Dominica',
    'Dominican Republic' => 'Dominican Republic',
    'Ecuador' => 'Ecuador',
    'Egypt' => 'Egypt',
    'El Salvador' => 'El Salvador',
    'Equatorial Guinea' => 'Equatorial Guinea',
    'Eritrea' => 'Eritrea',
    'Estonia' => 'Estonia',
    'Ethiopia' => 'Ethiopia',
    'Fiji' => 'Fiji',
    'Finland' => 'Finland',
    'French Guiana' => 'French Guiana',
    'Gabon' => 'Gabon',
    'Gambia' => 'Gambia',
    'Georgia' => 'Georgia',
    'Ghana' => 'Ghana',
    'Gibraltar' => 'Gibraltar',
    'Greece' => 'Greece',
    'Grenada' => 'Grenada',
    'Guadeloupe' => 'Guadeloupe',
    'Guatemala' => 'Guatemala',
    'Guinea' => 'Guinea',
    'Guinea-Bissau' => 'Guinea-Bissau',
    'Guyana' => 'Guyana',
    'Haiti' => 'Haiti',
    'Honduras' => 'Honduras',
    'Hong Kong' => 'Hong Kong',
    'Hungary' => 'Hungary',
    'Iceland' => 'Iceland',
    'India' => 'India',
    'Indonesia' => 'Indonesia',
    'Ireland' => 'Ireland',
    'Israel' => 'Israel',
    'Italy' => 'Italy',
    'Jamaica' => 'Jamaica',
    'Jersey' => 'Jersey',
    'Jordan' => 'Jordan',
    'Kazakhstan' => 'Kazakhstan',
    'Kenya' => 'Kenya',
    'Kuwait' => 'Kuwait',
    'Kyrgyzstan' => 'Kyrgyzstan',
    'Laos' => 'Laos',
    'Latvia' => 'Latvia',
    'Lebanon' => 'Lebanon',
    'Lesotho' => 'Lesotho',
    'Libya' => 'Libya',
    'Liechtenstein' => 'Liechtenstein',
    'Lithuania' => 'Lithuania',
    'Luxembourg' => 'Luxembourg',
    'Macau' => 'Macau',
    'Macedonia' => 'Macedonia',
    'Madagascar' => 'Madagascar',
    'Malawi' => 'Malawi',
    'Malaysia' => 'Malaysia',
    'Maldives' => 'Maldives',
    'Mali' => 'Mali',
    'Malta' => 'Malta',
    'Martinique' => 'Martinique',
    'Mauritania' => 'Mauritania',
    'Mauritius' => 'Mauritius',
    'Mexico' => 'Mexico',
    'Moldova' => 'Moldova',
    'Monaco' => 'Monaco',
    'Mongolia' => 'Mongolia',
    'Morocco' => 'Morocco',
    'Mozambique' => 'Mozambique',
    'Namibia' => 'Namibia',
    'Nepal' => 'Nepal',
    'Netherlands' => 'Netherlands',
    'Netherlands Antilles' => 'Netherlands Antilles',
    'New Zealand' => 'New Zealand',
    'Nicaragua' => 'Nicaragua',
    'Niger' => 'Niger',
    'Nigeria' => 'Nigeria',
    'Norway' => 'Norway',
    'Oman' => 'Oman',
    'Pakistan' => 'Pakistan',
    'Panama' => 'Panama',
    'Papua New Guinea' => 'Papua New Guinea',
    'Paraguay' => 'Paraguay',
    'Peru' => 'Peru',
    'Philippines' => 'Philippines',
    'Poland' => 'Poland',
    'Portugal' => 'Portugal',
    'Qatar' => 'Qatar',
    'Romania' => 'Romania',
    'Russia' => 'Russia',
    'Rwanda' => 'Rwanda',
    'San Marino' => 'San Marino',
    'Sao Tome &amp;amp; Principe' => 'Sao Tome &amp;amp; Principe',
    'Saudi Arabia' => 'Saudi Arabia',
    'Senegal' => 'Senegal',
    'Serbia &amp;amp; Montenegro' => 'Serbia &amp;amp; Montenegro',
    'Seychelles' => 'Seychelles',
    'Sierra Leone' => 'Sierra Leone',
    'Singapore' => 'Singapore',
    'Slovakia' => 'Slovakia',
    'Slovenia' => 'Slovenia',
    'Somalia' => 'Somalia',
    'South Africa' => 'South Africa',
    'South Korea' => 'South Korea',
    'Spain' => 'Spain',
    'Sri Lanka' => 'Sri Lanka',
    'St. Kitts &amp;amp; Nevis' => 'St. Kitts &amp;amp; Nevis',
    'St. Lucia' => 'St. Lucia',
    'St. Vincent &amp;amp; the Grenadines' => 'St. Vincent &amp;amp; the Grenadines',
    'Suriname' => 'Suriname',
    'Swaziland' => 'Swaziland',
    'Sweden' => 'Sweden',
    'Switzerland' => 'Switzerland',
    'Syria' => 'Syria',
    'Taiwan' => 'Taiwan',
    'Tajikistan' => 'Tajikistan',
    'Tanzania' => 'Tanzania',
    'Thailand' => 'Thailand',
    'Togo' => 'Togo',
    'Trinidad and Tobago' => 'Trinidad and Tobago',
    'Tunisia' => 'Tunisia',
    'Turkey' => 'Turkey',
    'Turkmenistan' => 'Turkmenistan',
    'Turks and Caicos Islands' => 'Turks and Caicos Islands',
    'Uganda' => 'Uganda',
    'Ukraine' => 'Ukraine',
    'United Arab Emirates' => 'United Arab Emirates',
    'Uruguay' => 'Uruguay',
    'Uzbekistan' => 'Uzbekistan',
    'Venezuela' => 'Venezuela',
    'Vietnam' => 'Vietnam',
    'Western Sahara' => 'Western Sahara',
    'Yemen' => 'Yemen',
    'Zambia' => 'Zambia');
}
function dropDownList($name, $data, $selected = ""){
    $head =<<<EOT
    <select name="{$name}" id="{$name}">
EOT;

    $body = "";
    $selected = strtoupper($selected);

    foreach($data as $key=> $value){
        $selectStr = "";
        if(strtoupper($key) == $selected){
            $selectStr = ' selected="selected"';
        }
        $body .= sprintf('<option value="%s"%s>%s</option>', $key,$selectStr, $value);
    }
    $foot =<<<EOT
    </select>
EOT;

    return $head . $body . $foot;
}

function createFormField($action, $data, $name, $type = "POST"){
    $head = "<form action='{$action}' method='{$type}' name='{$name}', id='{$name}'>";
    $fieldStr = "";
    foreach($data as $key=> $value){
        $fieldStr .= '<input type="hidden" name="' . $key . '" value="' . $value . '"/>' . "\n";
    }
    $foot = "</form>";

    return $head . $fieldStr . $foot;
}

function jsRedirect($url, $time = 3){
    $time = $time * 1000;
    $redirectJS =<<<STR
    <script type="text/javascript">
        setTimeout("location.href='{$url}';", {$time});
    </script>
STR;
    return $redirectJS;
}

function jsFormRedirect($name, $time = 3){
    $time = $time * 1000;
    if($time == 0){
        $redirectJS =<<<STR
        <script type="text/javascript">
            setTimeout("document.getElementById('{$name}').submit();", {$time});
        </script>
STR;
    }else{
        $redirectJS =<<<STR
        <script type="text/javascript">
            document.getElementById('{$name}').submit();
        </script>
STR;
    }
    return $redirectJS;
}

function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

function get_server_ip(){
    static $server_ip = NULL;

    if($server_ip !== NULL){
        return $server_ip;
    }

    if(varGet($_SERVER, 'SERVER_ADDR')){
        $server_ip = varGet($_SERVER, 'SERVER_ADDR');
    } else if(function_exists('getenv') && getenv('SERVER_NAME')){
        $server_ip = getenv('SERVER_ADDR');
    } else if(varGet($_SERVER, 'SERVER_NAME')){
        $server_ip = gethostbyname(varGet($_SERVER, 'SERVER_NAME'));
    } else if(varGet($_SERVER, 'HTTP_HOST')){
        $host = preg_replace('/(\:{1}\d+)$/', '', varGet($_SERVER, 'HTTP_HOST'));
        $server_ip = gethostbyname($host);
    } else {
        $server_ip = '';
    }

    return $server_ip;
}
