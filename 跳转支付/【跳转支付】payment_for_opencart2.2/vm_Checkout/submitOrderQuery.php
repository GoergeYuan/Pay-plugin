<?php
require dirname(__FILE__) . '/startup.php';

$status = TRUE;
$errstr = '';
$code = 0;
$codeType = 'Error Code:';
$errMsg = 'Payment Failed.';

$user_ip = varGet($_POST, 'user_ip', get_client_ip());

if(isset($_POST['DeliveryFirstName'])){
    $deliveryFirstName = varGet($_POST, 'DeliveryFirstName');
    $deliveryLastName = varGet($_POST, 'DeliveryLastName');
    $deliveryEmail = varGet($_POST, 'DeliveryEmail');
    $deliveryPhone = varGet($_POST, 'DeliveryPhone');
    $deliveryZipCode = varGet($_POST, 'DeliveryZipCode');
    $deliveryAddress = varGet($_POST, 'DeliveryAddress');
    $deliveryCity = varGet($_POST, 'DeliveryCity');
    $deliveryState = varGet($_POST, 'DeliveryState');
    $deliveryCountry = varGet($_POST, 'DeliveryCountry');
} else {
    $deliveryFirstName = varGet($_POST, 'shippingFirstName');
    $deliveryLastName = varGet($_POST, 'shippingLastName');
    $deliveryEmail = varGet($_POST, 'shippingEmail');
    $deliveryPhone = varGet($_POST, 'shippingPhone');
    $deliveryZipCode = varGet($_POST, 'shippingZipcode');
    $deliveryAddress = varGet($_POST, 'shippingAddress');
    $deliveryCity = varGet($_POST, 'shippingCity');
    $deliveryState = varGet($_POST, 'shippingState');
    $deliveryCountry = varGet($_POST, 'shippingCountry');
}

$data = array(
        // base information
        'MerNo'             => varGet($_POST, 'account_id'),
        'BillNo'            => varGet($_POST, 'BillNo'),
        'Currency'          => varGet($_POST, 'Currency'),
        'Amount'            => varGet($_POST, 'Amount'),
        'MD5info'           => varGet($_POST, 'MD5info'),
        'Language'          => varGet($_POST, 'Language'),
        'MerWebsite'        => varGet($_POST, 'MerWebsite'),
        'Remark'            => varGet($_POST, 'Remark'),
        'Products'          => varGet($_POST, 'Products'),
        'ReturnURL'         => varGet($_POST, 'ReturnURL'),

		'NoticeURL'			=> varGet($_POST, 'NoticeURL'),
		'IsNotice'			=> varGet($_POST, 'IsNotice'),
        // user ip
        'ip'                => $user_ip,
        //merchant no
        'merchantnoValue'   => varGet($_POST, 'account_id'),

        // billing information
        'firstname'         => varGet($_POST, 'firstname'),
        'lastname'          => varGet($_POST, 'lastname'),
        'email'             => varGet($_POST, 'email'),
        'phone'             => varGet($_POST, 'phone'),
        'zipcode'           => varGet($_POST, 'zipcode'),
        'address'           => varGet($_POST, 'address'),
        'city'              => varGet($_POST, 'city'),
        'state'             => varGet($_POST, 'state'),
        'country'           => varGet($_POST, 'country'),

        // shipping information
        'shippingFirstName' => $deliveryFirstName,
        'shippingLastName'  => $deliveryLastName,
        'shippingEmail'     => $deliveryEmail,
        'shippingPhone'     => $deliveryPhone,
        'shippingZipcode'   => $deliveryZipCode,
        'shippingAddress'   => $deliveryAddress,
        'shippingCity'      => $deliveryCity,
        'shippingCountry'   => $deliveryCountry,
        'shippingSstate'    => $deliveryState,

        // card information
        'cardnum'           => varGet($_POST, 'cardnum'),
        'cvv2'              => varGet($_POST, 'cvv2'),
        'year'              => varGet($_POST, 'year'),
        'month'             => varGet($_POST, 'month'),
        'cardbank'          => 'Bank of America'
    );

$required = array('MerNo', 'BillNo', 'Currency', 'ReturnURL', 'MD5info', 'merchantnoValue', 'Amount', 'firstname', 'lastname', 'address', 'city', 'country', 'zipcode', 'email', 'phone', 'cardnum', 'cvv2', 'year', 'month', 'cardbank');

foreach($required as $field){
    if(!preg_match('/.+/s', varGet($data, $field))){
        $status = FALSE;
        $errstr = "{$field} is required";
        break;
    }
}

$rule = array(
        'email'     => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'Amount'    => '/^[-\+]?\d+(\.\d+)?$/',
        'cardnum'   => '/^\d{13,}$/',
        'year'      => '/^\d{4}$/',
        'month'     => '/^\d+$/',
        'cvv2'      => '/^\d{3}/'  
    );

foreach($rule as $field=> $pattern){
    if(!preg_match($pattern, varGet($data, $field))){
        $status = FALSE;
        $errstr = "{$field} is not correct";
        break;
    }
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>
            <?php echo varGet($langData, 'L_SUBMIT_TITLE'); ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            body{ text-align: center;margin:0;padding: 0;}
            p{ width: 100%; padding: 8px 0; margin: 0 auto; line-height: 40px; font-size: 18px; }
            .err{ color: red; }
            .show{ display: block; }
            .hiddenB{ display: none; }
        </style>
    </head>
    <body>
        <p id="loadingID" class = "show">
        	<img src="./view/images/loading.gif" border="0"/>
        	<?php echo varGet($langData, 'SUBMIT_NOTICE_TEXT'); ?>
        </p>
<?php

if(function_exists('ob_flush')){
    ob_flush();
}
flush();

$result = '';
$process_Query_Type = 'curl';

if($status === TRUE){
    $pq = new Process_Query();
//    $pq->type = $process_Query_Type;
// 	$pq->type = 'fsockopen';
    $pq->url = GATEWAY_URL;
    $pq->timeOut = HTTP_TIMEOUT;

    if($pq->query($data) === FALSE){
        $status = FALSE;
        $errstr = $pq->error;
        $code = $pq->getStatusCode();
    } else {
        $result = $pq->getContent();

        if(!$result){
            $status = FALSE;
            $code = $pq->getStatusCode();
            $errstr = "Server Not Response";
        }

        if($status === TRUE){
        	$result = htmlspecialchars_decode($result);
            parse_str($result, $rData);
            $rData = array_map('trim', $rData);

            $sr = new System_Response();

            if(FALSE === $sr->check($rData['Succeed'])){
                $status = FALSE;
                $code = $rData['Succeed'];
                $codeType = 'Response Code:';
                
//                $errstr = "Response Code:" . $rData['Succeed'];
            } else {
                if($rData['Succeed'] == '9'){
                    $rData['Result'] = 'Payment Failed';
                }                
            }
        }
    }
}

if($status === TRUE){
//    echo buildForm(varGet($data, 'ReturnURL'), $rData, 'resultform', 'POST');
    echo drawFormHTML(
        varGet($data, 'ReturnURL'),
        $rData,
        array(
            'name' => 'ResultForm',
            'id' => 'ResultForm',
            'method' => 'POST'
        )
    );   

    if(file_exists(RESPONSE_LOG_FILE)){
        $realFileSize = @filesize(RESPONSE_LOG_FILE);
        $maxFileSize = 100 * 1024 * 1024;
        
        if($realFileSize > $maxFileSize){
            logResult(RESPONSE_LOG_FILE, '');
        }
        
        logResult(RESPONSE_LOG_FILE, $result);
    }
    
    echo javascriptFormSubmit('ResultForm', 0);
     
    $urlPattern = '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/';

     if(
         varGet($data, 'NoticeURL') && 
         varGet($data, 'IsNotice') == '1' &&
         preg_match($urlPattern, varGet($data, 'NoticeURL'))
     ){

        $pq = new Process_Query();
//        $pq->type = $process_Query_Type;
        $pq->url = varGet($data, 'NoticeURL');
        $pq->timeOut = 10;
        $pq->query($rData);
        
     } else if(file_exists(HTTP_RESPONSE_LOCK_FILE)){
         $noticeUrl = varGet($data, 'NoticeURL');
         
         if(!preg_match($urlPattern, $noticeUrl)){
             $noticeUrl = varGet($data, 'ReturnURL');
         }

         if(preg_match($urlPattern, $noticeUrl)){

             $pq = new Process_Query();
//             $pq->type = $process_Query_Type;
             $pq->url = $noticeUrl;
             $pq->timeOut = 10;
             $pq->query($rData);
         }
         
     } else {

         if(
             function_exists('connection_status') && 
             defined('CONNECTION_NORMAL') && 
             connection_status() != CONNECTION_NORMAL)
         {
         	         	
             $noticeUrl = varGet($data, 'NoticeURL');
             
             if(!preg_match($urlPattern, $noticeUrl)){
                 $noticeUrl = varGet($data, 'ReturnURL');
             }
             
             if(preg_match($urlPattern, $noticeUrl)){
                 $pq = new Process_Query();
//                 $pq->type = $process_Query_Type;
                 $pq->url = $noticeUrl;
                 $pq->timeOut = 10;
                 $pq->query($rData);
             }
             
         }

     }
} else {
    echo drawHTML(
    		'P', 
            $errMsg . '&nbsp;&nbsp;' . $codeType . $code . '&nbsp;&nbsp;' . $errstr,
//    		'Payment Failed. Response Code:' . $code . ".Message:" . $errstr, 
            array(
        	'class' => 'err'
            )
        );
}
?>
        <script type="text/javascript">
            if(document.getElementById("loadingID") != undefined){
                document.getElementById("loadingID").className = "hiddenB";
            }
        </script>
    </body>
</html>