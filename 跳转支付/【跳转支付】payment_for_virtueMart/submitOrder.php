<?php

require dirname(__FILE__) . '/vm_Checkout/startup.php';

$data = $_REQUEST;

$requiredField = array('MerNo', 'BillNo', 'Currency', 'Amount', 'ReturnURL', 'MD5info');

foreach($requiredField as $field){
    if(!varGet($data, $field, null)){
        die("request error");
    }
}

$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13');
foreach($CurrencyArray as $key => $value){
    if($value == $data['Currency']){
        $data['CurrencyName'] = $key;
        break;
    }
}


$data['user_ip'] = get_client_ip();
$data['server_ip'] = get_server_ip();

//create token
paymentSubmitCreateToken($data);
// createToken($data);
$data = array_merge($data, $langData);

$viewFile = '';

if(file_exists(DIR_PAY_VIEW . $lang . '/pay.php')){
    $viewFile = DIR_PAY_VIEW . $lang . '/pay.php';
}else if(isset($langMap[$lang]) && $langMap[$lang]){
    $lang3 = strtolower($langMap[$lang]);
    if(file_exists(DIR_PAY_VIEW . $lang3 . '/pay.php')){
        $viewFile = DIR_PAY_VIEW . $lang3 . '/pay.php';
    }
}

if(!$viewFile){
    $viewFile = DIR_PAY_VIEW . 'pay.php';
}

$basePath = str_replace('\\', '/', dirname(__FILE__)) . '/';
$imageFilePath = $basePath . 'vm_Checkout/view/images/type/%s.gif';

$imageList = array('ctv', 'ctm', 'ctj');

foreach($imageList as $imageFileName){
    $imageFile = sprintf($imageFilePath, $imageFileName);
    if(file_exists($imageFile)){
        $data[$imageFileName] = true;
    }
}

if(!file_exists($viewFile)){
    header('HTTP/1.1 404 Not Found');
    header('status: 404 Not Found');
    exit;
}

require $viewFile;


