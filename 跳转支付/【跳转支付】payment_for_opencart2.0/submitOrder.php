<?php

require dirname(__FILE__) . '/vm_Checkout/startup.php';

$data = $_POST;

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

$data['cardType'] = array();
$imgName = array('ctv', 'ctm', 'ctj');

foreach($imgName as $img){
	$fileName = getResource("view/images/type/{$img}.gif");
	
	if(file_exists($fileName)){
		$data['cardType'][] = $fileName;
	}
}

$viewFile = '';
foreach($langList as $langName){
	if($langName){
		$langName .= '/';
	}
	
	$viewFileName = DIR_PAY_VIEW . $langName . 'pay.php';
	
	if(file_exists($viewFileName)){
		$viewFile = $viewFileName;
		break;
	}
}

$firstname = varGet($data, 'DeliveryFirstName');
$lastname = varGet($data, 'DeliveryLastName');
$phone = varGet($data, 'DeliveryPhone');
$city = varGet($data, 'DeliveryCity');
$zipcode = varGet($data, 'DeliveryZipCode');
$address = varGet($data, 'DeliveryAddress');
$state = varGet($data, 'DeliveryState');
$email = varGet($data, 'DeliveryEmail');
$country = varGet($data, 'DeliveryCountry');
$Products = varGet($data, 'Products');

if(varGet($data, 'firstname')){
    $firstname = varGet($data, 'firstname');
 }
if(varGet($data, 'lastname')){
    $lastname = varGet($data, 'lastname');
 }
if(varGet($data, 'phone')){
    $phone = varGet($data, 'phone');
}
if(varGet($data, 'city')){
    $city = varGet($data, 'city');
}
if(varGet($data, 'zipcode')){
    $zipcode = varGet($data, 'zipcode');
}
if(varGet($data, 'address')){
    $address = varGet($data, 'address');
}
if(varGet($data, 'state')){
    $state = varGet($data, 'state');
}
if(varGet($data, 'email')){
    $email = varGet($data, 'email');
}
if(varGet($data, 'country')){
    $country = varGet($data, 'country');
}
$country = ucwords($country);

$detect = new Mobile_Detect();

if(($detect->isMobile() || $detect->isTablet() || $detect->is('AndroidOS')) && 
	($firstname && $lastname && $city && $phone && $zipcode && $address)	
)
{
	foreach($langList as $langName){
		$wapFileName = DIR_PAY_VIEW . $langName . '/wap.php';

		if(file_exists($wapFileName)){
			$viewFile = $wapFileName;
			$comefrome = '->Wap';
			break;
		}
	}	
}

$data = array_merge($data, $langData);

if($viewFile && file_exists($viewFile)){
	require $viewFile;
	exit;
} else {
	echo $viewFile, '<br/>';
	die("<span style='color: red;'>Page Not Found</span>");
}



