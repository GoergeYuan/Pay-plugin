<?php 
    header("Content-type:text/html; charset=utf-8");
    define('AREA', 'C');  
    require_once('./config.local.php');
    error_reporting(E_ALL);
    $conn   = mysql_connect($config['db_host'], $config['db_user'], $config['db_password']);
    if($conn){
        mysql_select_db($config['db_name'], $conn);
        $sql            = "INSERT INTO cscart_payment_processors (`processor_id`, `processor`, `processor_script`, `processor_template`, `admin_template`, `callback`, `type`) VALUES (0, 'card', 'card.php', 'cc_outside.tpl', 'card.tpl', 'N', 'P')";
        mysql_query($sql, $conn) or die("数据添加失败;Error No:".mysql_errno.";Error Msg:".mysql_error());
        if(mysql_insert_id()>0){
            echo '请不要刷新页面， 否则会出错';
            exit("数据添加成功;进入网店系统后台，找到支付方式安装页面，刷新一下页面；然后添加CreditCard支付方式;具体参数可参照安装说明图解");
        }else{
            exit("数据添加失败;Error No:".mysql_errno().";Error Msg:".mysql_error());
        }
    }else{
        exit("数据库连接失败");
    }
?>