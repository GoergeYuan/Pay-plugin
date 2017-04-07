<?php
include (dirname(CDDFEBAFBAA) . '/../../config/config.inc.php');
include (dirname(CDDFEBAFBAA) . '/../../header.php');
session_start();
if (strpos(_PS_VERSION_, "1.2") !== 0) {
    include (dirname(CDDFEBAFBAA) . '/../../config/defines.inc.php');
}
function szComputeMD5Hash($input){
	  $md5hex=md5($input); 
	  $len=strlen($md5hex)/2; 
      $md5raw=""; 
      for($i=0;$i<$len;$i++) { $md5raw=$md5raw . chr(hexdec(substr($md5hex,$i*2,2))); } 
      $keyMd5=base64_encode($md5raw); 
	  return $keyMd5;
}
$acctNo = $_POST['Par1'];
$orderNo=$_POST["Par2"];
$siteOrderNo = substr($orderNo,8); 
$pkid = $_POST['Par3'];
$currCode   = $_POST["Par6"];
$succeed  = $_POST["Par4"];
$result   = $_POST["Par5"];
$signInfo  = $_POST["HashValue"];
$signkey = trim(Configuration::get('NEWORDER_MERCHANT_KEY'));
$md5src=$signkey.$acctNo.$orderNo.$pkid.$succeed.$result.$currCode;
$md5sign  = szComputeMD5Hash($md5src); 
$history  = new OrderHistory();
if ($signInfo != $md5sign){
	$history->id_order = $siteOrderNo;
	$history->changeIdOrderState(intval(_PS_OS_ERROR_), intval($siteOrderNo));
	$history->addWithemail();
}else{
	if($succeed == '00'){
		$history->id_order = $siteOrderNo;
		$history->changeIdOrderState(intval(_PS_OS_PAYMENT_), intval($siteOrderNo));
	  $history->addWithemail();
	}else{
		$history->id_order = $siteOrderNo;
		$history->changeIdOrderState(intval(_PS_OS_ERROR_), intval($siteOrderNo));
	    $history->addWithemail();
	}
}
exit; 
?>

