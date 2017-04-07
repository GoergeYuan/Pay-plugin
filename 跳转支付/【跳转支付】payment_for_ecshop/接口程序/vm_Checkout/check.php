<?php
require dirname(__FILE__) . '/startup.php';
$curl_enable = extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec');

$fsockopen_enable = function_exists('fsockopen');

$is_win = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? true : false;

$sslenable = extension_loaded('openssl');

$allow_url_fopen = ini_get('allow_url_fopen');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>install check</title>
        <style type="text/css">
            body{text-align: center;}
            table{width: 600px; margin: 0 auto;}
            .left,.right{padding-top: 5px; padding-bottom: 5px;}
            .left{width: 200px; text-align: right;padding-right: 20px;}
            .right{text-align: left; padding-left: 20px;}
            caption{text-align: center; padding: 10px 0; font-weight: bold;}
            th{background: #ccc;}
        </style>
    </head>
    <body>
        <table border="1">
            <caption>安装前环境检查</caption>
            <tr>
                <th class="left">name</th>
                <th class="right">enable</th>
            </tr>
            <tr>
                <td class="left">Version:</td>
                <td class="right"><?php echo VERSION_INFO; ?></td>
            </tr>
            <tr>
                <td class="left">OS:</td>
                <td class="right"><?php echo PHP_OS; ?></td>
            </tr>
            <tr>
                <td class="left">curl:</td>
                <td class="right"><?php if($curl_enable){ echo 'Enable'; }else{ echo "<span style='color: red;'>Disable</span>"; } ?></td>
            </tr>
            <tr>
                <td class="left">fsockopen:</td>
                <td class="right"><?php if($fsockopen_enable){ echo 'Enable'; }else{ echo "<span style='color: red;'>Disable</span>"; } ?></td>
            </tr>
            <tr>
                <td class="left">allow_url_fopen:</td>
                <td class="right"><?php if($allow_url_fopen){ echo 'Enable'; }else{ echo "<span style='color: red;'>Disable</span>"; } ?></td>
            </tr>
            <tr>
                <td class="left">openssl:</td>
                <td class="right"><?php if($sslenable){ echo 'Enable'; }else{ echo "<span style='color: red;'>Disable</span>"; } ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <?php if(($curl_enable) or ($fsockopen_enable && $allow_url_fopen)){ ?>
                        <span style="color: green;">支持安装</span>
                    <?php }else{ ?>
                        <span style="color: red;">不支持安装， 空间服务器必须先开启至少curl模块或是fsockopen函数, 请先联系空间商</span>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </body>
</html>