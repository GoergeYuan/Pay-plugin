<?php
$data = $_REQUEST;
//引入启动文件
require dirname(__FILE__) . '/vm_Checkout/startup.php';

//商户号，订单号，币种编码，金额，返回网址，MD5加密为必须传输参数
$requiredField = array('MerNo', 'BillNo', 'Currency', 'Amount', 'ReturnURL', 'MD5info');

foreach($requiredField as $field){
    if(!varGet($data, $field, null)){
        die("request error");
    }
}

// /转换币种
$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13');
foreach($CurrencyArray as $key => $value){
    if($value == $data['Currency']){
        $data['CurrencyName'] = $key;
        break;
    }
}

//客户端ip，服务器ip
$data['user_ip'] = get_client_ip();
$data['server_ip'] = get_server_ip();

//转义商品信息
$data['Products'] = htmlspecialchars($data['Products'], ENT_QUOTES, 'UTF-8');

//create token
paymentSubmitCreateToken($data);
// createToken($data);
$data = array_merge($data, $langData);


$basePath = str_replace('\\', '/', dirname(__FILE__)) . '/';
$imageFilePath = $basePath . 'vm_Checkout/view/images/type/%s.gif';

$imageList = array('ctv', 'ctm', 'ctj');

foreach($imageList as $imageFileName){
    $imageFile = sprintf($imageFilePath, $imageFileName);
    if(file_exists($imageFile)){
        $data[$imageFileName] = true;
    }
}

//引入支付页面
$viewFile = DIR_PAY_VIEW . 'pay.php';
if(file_exists($viewFile)){
    require $viewFile;
}else{
     header('HTTP/1.1 404 Not Found');
    header('status: 404 Not Found');
    exit;
}





