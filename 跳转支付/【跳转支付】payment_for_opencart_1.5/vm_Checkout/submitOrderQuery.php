<?php
require dirname(__FILE__) . '/startup.php';
$data = $_POST;

$data['MerNo'] = varGet($data, 'account_id');
$data['merchantnoValue'] = varGet($data, 'account_id');
if(varGet($data, 'user_ip')){
    $data['ip'] = varGet($data, 'user_ip');
} else {
    $data['ip'] = get_client_ip();
}

$order_token = varGet($_SESSION, 'order_token');
$isSubmit = paymentSubmitQueryCheckSubmitted($data);

$referer = $_SERVER['HTTP_REFERER'];
$homePageUrl = 'http://' . $_SERVER['HTTP_HOST'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$not_support_info = varGet($langData, 'L_NOT_SUPPORT_INFO');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo varGet($langData, 'L_SUBMIT_TITLE'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <style type="text/css">
            body{text-align: center;}
            p{margin: 0 auto; width: 100%; padding: 8px 0;}
            .err{color: red;}
            .show{display: block;}
            .hiddenB{display: none;}
        </style>
    </head>
    <body>
        <p><?php echo varGet($langData, 'L_SUBMIT_NOTICE_1'); ?></p>
        <p><?php echo varGet($langData, 'L_SUBMIT_NOTICE_2'); ?></p>
        <p><?php echo varGet($langData, 'L_SUBMIT_NOTICE_3'); ?></p>
        <p id="loadingID" class = "show"><img src="./view/images/loading.gif" border="0"/></p>

<?php
if(function_exists('ob_flush')){
    @ob_flush();
}
flush();

if($isSubmit === false){
    $n_str_1 = varGet($langData, 'L_SUBMITED_NOTICE_1');
    $n_str_2 = varGet($langData, 'L_SUBMITED_NOTICE_2');
    $n_str_3 = varGet($langData, 'L_SUBMITED_NOTICE_3');
    $n_str_3 = sprintf($n_str_3, $homePageUrl);
    $str =<<<STR
        <p class="err">{$n_str_1}<p>
        <p class="err">{$n_str_2}</p>
        <p style="color: green;">{$n_str_3}</p>
        {$lhtml}
STR;
    echo $str;
    exit;
}
//validate data
$requiredField2 = array('MerNo', 'BillNo', 'Currency', 'ReturnURL', 'MD5info', 'merchantnoValue', 'Amount');
$requiredField3 = array('firstname', 'lastname', 'address', 'city', 'country', 'zipcode', 'email', 'phone');
$requiredField = array('cardnum', 'cvv2', 'year', 'month', 'cardbank');
$requiredField = array_merge($requiredField, $requiredField2, $requiredField3);

foreach($requiredField as $field){
    if(!isset($data[$field]) or !preg_match('/.+/s', $data[$field])){
        exit("<p class='err'>{$field} error</p>" . $lhtml);
    }
}
//$emailPattern = '/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A';
$emailPattern = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
if(!preg_match($emailPattern, $data['email'])){
    exit("<p class='err'>email error</p>" . $lhtml);
}

if(!preg_match('/^[-\+]?\d+(\.\d+)?$/', $data['Amount'])){
    exit("<p class='err'>Amount error</p>" . $lhtml);
}

if(!preg_match('/^\d{13,}$/', $data['cardnum'])){
    exit("<p class='err'>Card number error</p>" . $lhtml);
}

if(!preg_match('/^\d{4}$/', $data['year'])){
    exit("<p class='err'>Year error</p>" . $lhtml);
}
if(!preg_match('/^\d+$/', $data['month'])){
    exit("<p class='err'>month error</p>" . $lhtml);
}
if(!preg_match('/^\d{3}/', $data['cvv2'])){
    exit("<p class='err'>cvv2 error</p>" . $lhtml);
}
$result = false;
$error = '';
$status = 0;
$type = '';

if(function_exists('curl_init') && function_exists('curl_exec')){
    $type = 'curl';
    $httpCurlQuery = new Http_Curl_Query();

    $headers[] = "Expect: ";
    $status = $httpCurlQuery
        ->setOpt(CURLOPT_URL, GATEWAY_URL)
        ->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE)
        ->setOpt(CURLOPT_SSL_VERIFYHOST, 0)
        ->setOpt(CURLOPT_HTTPHEADER, $headers)
        ->setOpt(CURLOPT_TIMEOUT, HTTP_TIMEOUT)
        ->setOpt(CURLOPT_CONNECTTIMEOUT, HTTP_TIMEOUT)
        ->setOpt(CURLOPT_FRESH_CONNECT, 1)
        ->httpPost($data)->response['http_code'];

    if($status == 200){
        $result = $httpCurlQuery->response['content'];
    }else{
        $error = $httpCurlQuery->response['error'];
    }
}else if(function_exists('fsockopen')){
    $type = 'fsockopen';
    $parts = parse_url(GATEWAY_URL);
    $host = $parts['host'];
    $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
    $path = isset($parts['path']) ? $parts['path'] : '/';

    if(isset($parts['port'])){
        $port = intval($parts['port']);
    }else{
        if($scheme == 'https'){
            $port = 443;
        }else{
            $port = 80;
        }
    }

    $httpClient = new Http_Client($host);
    $httpClient->setDebug(PAY_DEBUG);
    $httpClient->setPersistReferers(false);
    $httpClient->referer = $referer;
    $httpClient->setUserAgent($userAgent);
    $httpClient->timeout = HTTP_TIMEOUT;

    $flag = $httpClient->post($path, $data);
    $status = $httpClient->getStatus();
    if($flag === true){
        $result = $httpClient->getContent();
    }else{
        $error = $httpClient->getError();
    }
}else{
    exit("<P class='err'> {$not_support_info}</P>" . $lhtml);
}

if($status == 200 && $result){
    parse_str($result, $rData);
    if($rData['Succeed'] == "9"){
        $rData['Result'] = 'Payment Failed!';
    }
    $html = createFormField($data['ReturnURL'], $rData, 'resultform', 'POST');
    $color = (in_array($rData['Succeed'], array('19', '88'))) ? 'green;' : 'red;';
    // $html .= "<p style='color:{$color}'>Payment Result: {$rData['Result']}&nbsp;&nbsp;&nbsp;&nbsp;Response Code:{$rData['Succeed']}</p>";
    $html .= jsFormRedirect('resultform', 0);
    $html .= $lhtml;

    $systemResponse = new System_Response();
    $isSuccessSubmit = $systemResponse->check($rData['Succeed']);
    if($isSuccessSubmit === false){
        $str =<<<STR
        <P class="err">Submit failed!, Response Code: {$rData['Succeed']}</P>
        <P class="err">Please check your information or contact the technician</P>
STR;
        exit($str . $lhtml);
    }
    exit($html);
}elseif($status != 200 && $type == 'curl'){
    if(PAY_DEBUG){
        httpDebug('error request', $httpCurlQuery);
    }
}elseif($status != 200 && $type == 'fsockopen'){

}else{
    $_SESSION['order_token'] = $order_token;
    exit('<p class="err">{$not_support_info}</p>' . $lhtml);
}
    $_SESSION['order_token'] = $order_token;
    $parts = parse_url(GATEWAY_URL);
    $host = isset($parts['host']) ? $parts['host'] : '';
    if($host){
        $error = str_replace($host, 'Submit Url', $error);
    }
    $failed_request_1 = varGet($langData, 'L_FAILED_REQUEST_1');
    $failed_request_2 = varGet($langData, 'L_FAILED_REQUEST_2');
    $failed_request_2 = sprintf($failed_request_2, $status, $error);

    $str =<<<STR
        <p class='err'>{$failed_request_1}</p>
        <p class='err'>{$failed_request_2}</p>
        <script type="text/javascript">
            document.getElementById("loadingID").className = "hiddenB";
        </script>
STR;
    echo $str;
?>
    </body>
</html>
