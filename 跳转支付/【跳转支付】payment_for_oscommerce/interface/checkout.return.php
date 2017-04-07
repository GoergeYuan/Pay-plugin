<?php
include 'includes/application_top.php';

$messageStack = new messageStack();

if(!class_exists('order_total', false)) {
    include DIR_WS_CLASSES . 'order_total.php';
    $order_total_modules = new order_total;
}


if(!isset($_GET['result']) || base64_decode($_GET['result']) === false) exit('Data error');


parse_str(base64_decode($_GET['result']),$rData);

$rData['MD5key'] = MODULE_PAYMENT_CARD_MD5KEY;

$j = array('BillNo', 'Currency', 'Amount', 'Succeed', 'MD5key');
$md5sign = '';
foreach($j as $key=> $val){
    $md5sign .= $rData[$val];
}
$md5sign = strtoupper(md5($md5sign));

// if($md5sign != $rData['MD5info']){
//     exit('Validate Failed');
// }

$message=<<<html
<div style="float:left; width:100%;line-height:40px;"><div style="float:left; width: 50%;margin-left: 25%;">
{$rData['Result']} <br/>
Your Order Number : {$rData['BillNo']} <br/>
Order Amount : {$rData['Amount']} &nbsp;&nbsp;&nbsp;&nbsp;{$rData['CurrencyName']} <br/>
Payment Result : {$_GET['description']}
html;
if($md5sign == $rData['MD5info'] && $rData['Succeed'] == '88' or $rData['Succeed'] == '19') {
    $message .= '</div></div>';
    $messageStack->add('checkout_payresult', $message, 'success');
    $cart->reset(true);
    unset($_SESSION['sendto'], $_SESSION['billto'], $_SESSION['shiiping'], $_SESSION['payment'], $_SESSION['comments']);
    if(isset($_SESSION['cot_gv'])) {
        unset($_SESSION['cot_gv']);
    }
    if(isset($_SESSION['credit_covers'])){
        unset($_SESSION['credit_covers']);
    }
    if(isset($_SESSION['_card_order_id'])){
        unset($_SESSION['_card_order_id']);
    }
} else if($md5sign == $rData['MD5info']){
    $message .= '&nbsp;&nbsp;&nbsp;&nbsp;Response Code : ' . $rData['Succeed'] . '</div></div>';
    $messageStack->add('checkout_payresult', $message); 
}else{
    $message = '<div style="float:left width: 100%; text-align:center;line-height: 40px;">Validation failed !&nbsp;&nbsp;&nbsp;&nbsp;' . $rData['MD5key'] . '</div>';
    $messageStack->add('checkout_payresult', $message);
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0">
        <!-- header start -->
        <?php require DIR_WS_INCLUDES . 'header.php'; ?>
        <!-- header end -->
            <table style="float:left; width:100%;margin-top: 50px;">
                <tr>
                    <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
                    <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
                    <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
                    <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
                </tr>
            </table>
        <?php echo $messageStack->output('checkout_payresult'); ?>
        <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
    </body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>
<?php
    if($md5sign == $rData['MD5info']){
        $sql_data_array = array(
                'orders_status_id' => MODULE_PAYMENT_CARD_ORDER_STATUS_FILISHED_ID,
                'date_added' => 'now()',
                'customer_notified' => 0,
                'comments' => 'Pay Successfully '
            );
        $note = '[ - Bill No:' . $rData['BillNo'] . '- Currency: ' . $rData['Currency'] . '- Amount:' . $rData['Amount'] . $rData['CurrencyName'] . '- Response Code:' . $rData['Succeed'] . '- Result:' . $rData['Result'] . '- Description:' .$_GET['description'];
        $sql_order_data = array(
                'orders_status' => MODULE_PAYMENT_CARD_ORDER_STATUS_FILISHED_ID,
                'orders_date_finished' => 'now()'
            );
        try{
            if($rData['Succeed'] == '88' or $rData['Succeed'] == '19'){
                $sql_data_array['comments'] .= $note;
                tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$rData['BillNo']);
                tep_db_perform(TABLE_ORDERS, $sql_order_data, 'update', 'orders_id=' . (int)$rData['BillNo']);
            }else{
                unset($sql_data_array['orders_status_id']);
                $sql_data_array['comments'] = 'Pay failed ' . $note;
                tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$rData['BillNo']);
            }
        }catch(Exception $e){
            
        }
    }
?>


