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




//validate data
$requiredField3 = array('firstname', 'lastname', 'address', 'city', 'country', 'zipcode', 'email', 'phone');
$requiredField2 = array('MerNo', 'BillNo', 'Currency', 'ReturnURL', 'MD5info', 'Amount','Language','products');
$requiredField = array('cardnum', 'cvv2', 'year', 'month', 'cardbank');
$requiredField = array_merge($requiredField, $requiredField2, $requiredField3);
foreach($requiredField as $field){
    $data[$field] = trim($data[$field]);
    if(!isset($data[$field]) or !preg_match('/.+/s', $data[$field])){
        echo jsonMsg(500, $field.' error', false);exit();   
    }
}
$emailPattern = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
if(!preg_match($emailPattern, $data['email'])){
    echo jsonMsg(500, 'Your email is incorrect!', false);exit();
    
}

if(!preg_match('/^[-\+]?\d+(\.\d+)?$/', $data['Amount'])){
    echo jsonMsg(500, 'amount error', false);exit();
    
}

if(!preg_match('/^\d{13,}$/', $data['cardnum']) or !card_check_by_luhn($data['cardnum'])){
   echo jsonMsg(500, 'Your card number is incorrect!', false);exit();

}

if(validateExpiresDate($data['year'],$data['month'])){
  echo  jsonMsg(500, validateExpiresDate($data['year'],$data['month']), false); exit();

}

if(!preg_match('/^\d{3}/', $data['cvv2']) or !validateCVV($data['cvv2'])){
   echo jsonMsg(500, 'Your cvv is incorrect!', false); exit();

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
        $http_code = $httpCurlQuery->response['http_code'];
        $errno = $httpCurlQuery->response['errno'];
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
   echo jsonMsg(500,varGet($data, L_NOT_SUPPORT_INFO),false);
}

/**
 * $result  支付返回
 * $status  请求状态
 */
//解析数据
if($status == 200 && $result){
    $flag = true;
    parse_str($result, $rData);
    $Succeed = $rData['Succeed'];
    $returnUrl = $rData['ReturnURL'];
    $resultPay = $rData['Result'];
    $color = (in_array($rData['Succeed'], array('19', '88'))) ? 'green;' : 'red;';

    $systemResponse = new System_Response();
    $isSuccessSubmit = $systemResponse->check($rData['Succeed']);
    if($isSuccessSubmit === false){
      //弹出DIV提示框，否则就跳转结果页面  
        $flag = false;
     }
     //记录日志
     saveErrmsg($rData,PAY_DEBUG_LOG,DIR_PAY);
     //返回信息   
    echo jsonMsg($Succeed, $systemResponse->checkinfo($Succeed), $flag, $resultPay, $returnUrl, $result);
    
}elseif ($status !== 200 && $type == 'curl'){
     $httpCurlErr = array(
        'type'=>'curl',
        'orderNo'=>$data['BillNo'],
        'errno' => $errno,
        'http_code' => $http_code,
        'error' => $error
    );
    saveErrmsg($httpCurlErr,PAY_DEBUG_LOG,DIR_PAY);
    echo jsonMsg(500,'curl request error,'.$error,false);
}elseif ($status !== 200 && $type == 'fsockopen') {
    $httpSocketErr = array(
        'type'=>'fsockopen',
        'orderNo'=>$data['BillNo'],
        'fsockopen'=>$error
    );
    //记录日志
     saveErrmsg($httpSocketErr,PAY_DEBUG_LOG,DIR_PAY);
     //返回信息
     echo jsonMsg(500,'fsockopen request error,'.$error,false);

}else{
    echo jsonMsg(500,'request error',false);
}



   
?>
 
