<?php 
    /* MD5私钥 */
    $MD5key         = '12345678';
    /* 商户号 */
    $MerNo          = '10003';
    /* 订单号[必填](商户自己产生，要求不重复)*/
    $BillNo         = date('his');
    /* 订单金额[必须] */
    $Amount         = '5.2';
    /* 支付页面语言 */
    $Language       = 'en';
    /* 速汇通开通币种
        对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗
    */
    $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14');
    /* 美元 */
    $Currency       = $CurrencyArray['EUR'];
    /* 返回地址[必填]返回数据给商户，商户自己填写，需要在商户后台绑定 */
    $ReturnURL      = 'http://www.test.com/_html_test/php/fashionpay/result.php';
    /* 备注信息[选填]填写网站的域名，方便以后维护,可不做修改 */
    //$Remark         = 'http://'.$_SERVER['HTTP_HOST'];
    $Remark = 'test order';
    /* 校验源字符串 (顺序不可改变) */
    $MD5src         = $MerNo . $BillNo . $Currency . $Amount . $Language . $ReturnURL . $MD5key;
    $MD5info        = strtoupper(md5($MD5src));
    
    /* 
        送货信息(方便以后维护，请尽量收集，如果没有以下信息提供，可写空值)
        为方便以后升级的需要，请尽量收集，谢谢
    */
    /* 收货人姓 */
    $DeliveryFirstName      = 'fisrt name';
    /* 收货人名 */
    $DeliveryLastName       = 'last name';
    /* 收货人邮箱 */
    $DeliveryEmail          = '1093184107@qq.com';
    /* 收货人电话 */
    $DeliveryPhone          = '13400000000';
    /* 收货人邮编 */
    $DeliveryZipCode        = '156854';
    /* 收货人地址 */        
    $DeliveryAddress        = 'delivery address';
    /* 收货人城市 */
    $DeliveryCity           = 'new york';
    /* 收货人省或是州 */
    $DeliveryState          = 'new york';
    /* 收货人国家 */
    $DeliveryCountry        = 'china';
    
    $Products            = '<Goods><GoodsName>Nike Bag</GoodsName><Price>59.88</Price><Qty>2</Qty><Currency>USD</Currency><GoodsName>Nike shoes</GoodsName><Price>12.88</Price><Qty>1</Qty><Currency>USD</Currency></Goods>';
	$action = './submitOrder.php';
?>
<html>
    <head>
        <title>Submit</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
        <form action="<?php echo $action; ?>" method="post">
            <input type="hidden" name="MerNo" value="<?=$MerNo?>"/>
            <input type="hidden" name="Currency" value="<?=$Currency?>"/>
            <input type="hidden" name="BillNo" value="<?=$BillNo?>"/>
            <input type="hidden" name="Amount" value="<?=$Amount?>"/>
            <input type="hidden" name="ReturnURL" value="<?=$ReturnURL?>"/>
            <input type="hidden" name="Language" value="<?=$Language?>"/>
            <input type="hidden" name="MD5info" value="<?=$MD5info?>"/>
            <input type="hidden" name="Remark" value="<?=$Remark?>"/>
            
            <input type="hidden" name="DeliveryFirstName" value="<?=$DeliveryFirstName?>"/>
            <input type="hidden" name="DeliveryLastName" value="<?=$DeliveryLastName?>"/>
            <input type="hidden" name="DeliveryEmail" value="<?=$DeliveryEmail?>"/>
            <input type="hidden" name="DeliveryPhone" value="<?=$DeliveryPhone?>"/>
            <input type="hidden" name="DeliveryZipCode" value="<?=$DeliveryZipCode?>"/>
            <input type="hidden" name="DeliveryAddress" value="<?=$DeliveryAddress?>"/>
            <input type="hidden" name="DeliveryCity" value="<?=$DeliveryCity?>"/>
            <input type="hidden" name="DeliveryState" value="<?=$DeliveryState?>"/>
            <input type="hidden" name="DeliveryCountry" value="<?=$DeliveryCountry?>"/>
            <input type="hidden" name="Products" value="<?=$Products?>"/>
            <input type="submit" name="payment" value="Payment Submit"/>
        </form>
    </body>
<html>