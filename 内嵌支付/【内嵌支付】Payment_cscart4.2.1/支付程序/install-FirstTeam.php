<?php
define('AREA', 'INSTALL');
require dirname(__FILE__) . '/init.php';

$action = isset($_GET['action']) ? trim($_GET['action']) : null;
if($action == 'delete'){
    db_query("DELETE FROM ?:payment_processors WHERE processor_script=?s", 'FirstTeam.php');
    $result = db_get_row("SELECT * FROM ?:payment_processors WHERE processor_script=?s", 'FirstTeam.php');
    if(!$result){
        exit('<b>FirstTeam</b> uninstalled Succeed!');
    }else{
        exit('<b>FirstTeam</b> uninstalled Failed!');
    }
}
$result = db_get_row("SELECT * FROM ?:payment_processors WHERE processor_script=?s", 'FirstTeam.php');
// print_r($result);
if(!$result){
    $data['processor_id'] = '';
    $data['processor'] = 'FirstTeam';
    $data['processor_script'] = 'FirstTeam.php';
    $data['processor_template'] = 'views/orders/components/payments/FirstTeam.tpl';
    $data['admin_template'] = 'FirstTeam.tpl';
    $data['callback'] = 'N';
    $data['type'] = 'P';
    db_query("INSERT INTO ?:payment_processors ?e", $data);
    $result = db_get_row("SELECT * FROM ?:payment_processors WHERE processor_script=?s", 'FirstTeam.php');
}
if(!$result){
    exit('Payment <b>FirstTeam</b> Installed Failed!');
}
$keys = array_keys($result);
$html = '<table width="100%" border="1"><tr>';
foreach($keys as $k){
    $html .= '<td>' . $k . '</td>';
}
$html .= '</tr><tr>';
foreach($result as $key=> $value){
    $html .= '<td>' . $value . '</td>';
}
$html .= '</tr></table>';
echo $html;
exit("Payment <b>FirstTeam</b> Installed Success!");