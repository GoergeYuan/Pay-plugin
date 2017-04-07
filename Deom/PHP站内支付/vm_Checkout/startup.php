<?php
header("Content-type: text/html; charset=utf-8");
function_exists('ignore_user_abort') && ignore_user_abort(false);
session_start();

define('VERSION_INFO', '2.0.3');
define('DIR_PAY', str_replace('\\', '/', dirname(__FILE__)) . '/');   //vm_Checkout目录
define('DIR_PAY_INCLUDED', DIR_PAY . 'includes/');
define('DIR_PAY_VIEW', DIR_PAY . 'view/');
define('GATEWAY_URL', 'http://ssl.hpolineshop.com/sslWebsitpayment');
define('DIR_PAY_LANG', DIR_PAY . 'lang/');
// define('DIR_PAY_VIEW_FILE', DIR_PAY_VIEW . 'pay.php');
define('PAY_USE_TOKEN', FALSE);
define('FORCED_TO_USE_DEFAULT_LANGUAGE', false);
define('DEFAULT_PAY_LANGUAGE', 'en');

//开启调式模式
define('PAY_DEBUG', false);
//开启日志
define('PAY_DEBUG_LOG', true);

define('VISA', true);
define('MASTER', false);
define('JCB', false);


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

require DIR_PAY_INCLUDED . 'Http_Curl_Query.php';
require DIR_PAY_INCLUDED . 'Http_Client.php';
require DIR_PAY_INCLUDED . 'System_Response.php';


//临时调试
function p($stmt)
{
    echo '<pre>';
    print_r($stmt);
    echo '</pre>';
}



/**
 * 加载语言包
 * @param string $lang
 */

    //默认页面语言en
    $langFilePath = DEFAULT_PAY_LANGUAGE;
    $langMap[$lang] = DEFAULT_PAY_LANGUAGE;
    $langData = array();
    //引入语言数组配置 array()
    $langMap = require DIR_PAY_INCLUDED . 'Language_Mapping.php';
    
    //是否强制关闭语言包
    if(FORCED_TO_USE_DEFAULT_LANGUAGE == false){
        
        $lang = varGet($data, 'Language');
        $lang = strtolower($lang);
        if(!$lang){
            $lang = getLanguage();
        }
    }
    //引入对应的语言包
    if(file_exists(DIR_PAY_LANG . $langMap[$lang] . '/language.php')){
        $langFilePath = $langMap[$lang];
       
    }else if(isset($langMap[$lang]) && $langMap[$lang] && file_exists(DIR_PAY_LANG . strtolower($langMap[$lang]) . '/language.php')){
        $langFilePath = strtolower($langMap[$lang]);
    } else {
        $langFilePath = varGet($langMap, DEFAULT_PAY_LANGUAGE) ? varGet($langMap, DEFAULT_PAY_LANGUAGE) : 'en-us';
    }
    
    if(!is_file(DIR_PAY_LANG . $langFilePath . '/language.php')){
        $langFilePath = 'en-us';
    }
    
    $langPath = DIR_PAY_LANG . $langFilePath . '/language.php';
    $langData = require DIR_PAY_LANG . $langFilePath . '/language.php';
    
    


//获取浏览器语言
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

/**
 * 检测返回已经设置的参数
 * @param $_REQUEST $data  接收数据方式，或者数组
 * @param kay $key
 * @param string $defaultValue
 * @return string  有对应值的数组值
 */
function varGet($data, $key, $defaultValue = ''){
    return (isset($data[$key])) ? $data[$key] : $defaultValue;
}

/**
 * 开启调试模式（暂不用）
 * @param unknown $msg
 * @param string $object
 */
function httpDebug($object = false) {

       
        if ($object) {
            ob_start();
            print_r($object);
            $content = htmlentities(ob_get_contents());
            ob_end_clean();
            print '<pre>'.$content.'</pre>';
        }
} 

/**
 *  创建支付页面数据：年
 * @param number $len
 * @param string $label
 * @return string[]|number[]
 */
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

/**
 * 创建支付页面数据：月
 * @param string $label
 * @return string[]
 */
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

/**
 * 加载支付页面国家数组
 * @param string $label
 */
function getCountryList($label = 'Please Select Country'){
         if (file_exists(DIR_PAY_INCLUDED.'Country_Map.php')) {
             return include DIR_PAY_INCLUDED.'Country_Map.php';
   
         }
         return false;
}

/**
 *  创建支付页面数据：国家
 * @param string $name      下拉框name和id
 * @param array $data       国家列表数组
 * @param string $selected  根据已传输过来的国家，设置为默认被选状态
 */
function dropDownList($name, $data, $selected = ""){

    $body = "";
    $selected = strtoupper($selected);
    if(false !== $data){
        $body .=<<<EOT
    <select name="{$name}" class="textRim" id="{$name}">
EOT;
        foreach($data as $key=> $value){
            $selectStr = "";
            if(strtoupper($key) == $selected){
                $selectStr = ' selected="selected"';
            }
            $body .= sprintf('<option value="%s"%s>%s</option>', $key,$selectStr, $value);
        }
        
        $body .=<<<EOT
    </select>
EOT;
        
    }else{
        $body .= '<input placeholder=" please enter country" name="'.$name.'" id="'.$name.'" type="text">';   
    }
    

    return $body;
}

/**
 * 创建支付页面数据：表单
 * @param unknown $action
 * @param unknown $data
 * @param unknown $name
 * @param string $type
 */
function createFormField($action, $data, $name, $type = "POST"){
    $head = "<form action='{$action}' method='{$type}' name='{$name}', id='{$name}'>";
    $fieldStr = "";
    foreach($data as $key=> $value){
        $fieldStr .= '<input type="hidden" name="' . $key . '" value="' . $value . '"/>' . "\n";
    }
    $foot = "</form>";

    return $head . $fieldStr . $foot;
}



/**
 * 通过Luhn算法校验信用卡卡号是否有效
 * @param $cardNum
 * @return bool
 */
function card_check_by_luhn($cardNum){
    $str = '';
    foreach(array_reverse(str_split($cardNum)) as $i => $c) $str .= ($i % 2 ? $c * 2 : $c);
    return array_sum(str_split($str)) % 10 == 0;
}

/**
 * 校验信用卡CVV是否有效
 * @param $cvv
 * @return $msg
 */
function validateCVV($cvv) {
    if(empty($cvv) || !is_numeric($cvv) || strlen($cvv)!=3) return false;
    return true;
}

/**
 * 校验信用卡有效期是否有效
 * @param $year
 * @param $month
 * @return $msg
 */
function validateExpiresDate($year,$month) {
    $msg = "";
    if(empty($year) || !is_numeric($year) || strlen($year) !=4) {
        $msg = 'The year of expiry date is incorrect!';
    } else if(empty($month) || !is_numeric($month) || strlen($month) !=2 || $month < 1 || $month>12) {
        $msg = 'The month of expiry date is incorrect!';
    } else {
        $currentDate  = new DateTime(date("Y-m",time()));
        $inputDate    = new DateTime($year."-".$month);
        if($year<date("Y",time()) || $inputDate->format('U') < $currentDate->format('U')) {
            $msg = 'The expire date is expired !';
        }
    }
    return $msg;
}



/**
 * 1、是否开启记录错误日志
 * 2、保存的路径
 * 3、文件夹名（Ym） 文件名（Ymd） 记录内容（Y-m-d H:i:s  订单编号，流水编号，支付返回码，支付返回结果，返回网址）
 * 
 */
/**
 * 
 * @param array $dataArr   记录数据
 * @param bool $isMkdir    是否记录
 * @param string $filePath 存放文件夹命名
 */
function saveErrmsg($dataArr,$isMkdir = true, $filePath = '')
{   
   
    if(!$isMkdir) return false;

    if(is_dir($filePath)){
        $filePath  = $filePath.'log/'.date('Ym').'/';
    }else{
        $filePath  = DIR_PAY.'log/'.date('Ym').'/';
    }
    mkDirs($filePath);
    $msgdata = date('Y-m-d H:i:s').'   '.json_encode($dataArr);
    file_put_contents($filePath.date('Ymd').'.txt', $msgdata.PHP_EOL, FILE_APPEND);
    return true;
   
}

/**
 * 递归创建文件夹
 * @param unknown $dir
 */
function mkDirs($dir){
    $dir = str_replace('//', DIRECTORY_SEPARATOR, $dir);
    if(!is_dir($dir)){
        if(!mkDirs(dirname($dir))){
            return false;
        }
        if(!mkdir($dir,0777)){
            return false;
        }
    }
    return true;
}

/**
 * 解析json数据
 * @param int  $errno        支付返回码
 * @param string $errmsg     支付返回码说明
 * @param bool $flag         是否弹出警示框
 * @param string $resultPay         支付返回结果
 * @param string $returnUrl  返回网址
 * @param array $data       支付返回数据
 * 
 */
function jsonMsg($errno = '0', $errmsg = 'Fail',$flag = true, $resultPay='Request Fails', $returnUrl = '', $data = '')
{
    //获取默认返回网址
    if(is_null($returnUrl)){
        if ($_SERVER['HTTPS'] != "on") {
            $returnUrl =  "http://".$_SERVER['HTTP_HOST'].'result.php';
        }else{
            $returnUrl =  "https://".$_SERVER['HTTP_HOST'].'result.php';
        }
    }
    $response = array(
        'errno'=> $errno,
        'resultPay'=>$resultPay,
        'errmsg'=>$errmsg,  
        'flag' => $flag,
        'returnUrl'=>$returnUrl,
        'data'=> base64_encode($data)
    );
    
    return json_encode($response);
}
/**
 * js定重向跳转：根据url
 * @param unknown $url
 * @param number $time
 * @return string
 */
function jsRedirect($url, $time = 3){
    $time = $time * 1000;
    $redirectJS =<<<STR
    <script type="text/javascript">
        setTimeout("location.href='{$url}';", {$time});
    </script>
STR;
    return $redirectJS;
}

/**
 * js定重向叫转：根据name
 * @param unknown $name
 * @param number $time
 * @return string
 */
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

/**
 * 获取客户端ip
 * @return NULL|string|unknown
 */
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


/**
 * 获取服务器ip
 */
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
