<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
    <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
   <!-- 状态开启/关闭 -->     
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="FirstTeam_status">
              <?php if ($FirstTeam_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
    <!-- 状态开启/关闭 -->   

    <!-- 卡种支持类型 -->
      <tr>
          <td><?php echo $entry_Card_Type; ?></td>
          <td>  <label><input name="FirstTeam_card"  id="card_VISA" type="checkbox"  onClick="result(this.form)" value="<?php echo $entry_Card_VISA; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_VISA; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_JCB" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_JCB; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_JCB; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_MASTER" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_MASTER; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_MASTER; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_AE" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_AE; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_AE; ?></label>
            <input type="hidden" name="FirstTeam_cardtype" value="<?php echo $FirstTeam_cardtype; ?>" id="cardtype_text">
             <?php if ($error_cardtype) { ?>
            <span class="error"><?php echo $error_cardtype; ?></span>
            <?php } ?>

            </td>

      </tr>



    <!-- 卡种支持类型 -->

    <!-- 商户号 -->
      <tr>
          <td><span class="required">*</span><?php echo $entry_account; ?></td>
          <td><input type="text" name="FirstTeam_account" placeholder="FashionPay Account" value="<?php echo $FirstTeam_account; ?>" />
            <?php if ($error_account) { ?>
            <span class="error"><?php echo $error_account; ?></span>
            <?php } ?></td>
      </tr>
    <!-- 商户号 -->
    <!-- 商户KEY -->
        <tr>
          <td><span class="required">*</span><?php echo $entry_secret; ?></td>
          <td><input type="text" name="FirstTeam_secret" placeholder="FashionPay Key" value="<?php echo $FirstTeam_secret; ?>" />
            <?php if ($error_secret) { ?>
            <span class="error"><?php echo $error_secret; ?></span>
            <?php } ?></td>
        </tr>
  <!-- 商户KEY -->
  <!-- 订单状态 -->
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="FirstTeam_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
<!-- 订单状态 -->


<!-- 支付成功订单状态 -->
        <tr>
          <td><?php echo $entry_order_succeed_status; ?></td>
          <td><select name="FirstTeam_order_succeed_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_succeed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
  
<!-- 支付成功订单状态 -->
<!-- 支付失败订单状态 -->
      
      <tr>
          <td><?php echo $entry_order_failed_status; ?></td>
          <td>
            <select name="FirstTeam_order_failed_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_failed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </td>
      </tr>

  
<!-- 支付失败订单状态 -->

<!-- 支付等待订单状态 -->

<tr>
    <td><?php echo $entry_order_payWait_status_id; ?></td>
    <td>
         <select name="FirstTeam_order_payWait_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_payWait_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
    </td>
</tr>

<!--支付等待订单状态 -->

<!-- 订单区域 -->
   <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="FirstTeam_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $FirstTeam_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>

<!-- 订单区域 -->

<!-- 支付网关 -->
      <tr>
          <td><span class="required">*</span><?php echo $entry_payment_url; ?></td>
          <td><input type="text" name="FirstTeam_payment_url" placeholder="FashionPay Payment gateway" value="<?php echo $FirstTeam_payment_url; ?>" size=60/>
           <?php if ($error_merchant) { ?>
            <span class="error"><?php echo $error_merchant; ?></span>
            <?php } ?></td>
        </tr>
<!-- 支付网关-->

<!-- 返回网址 -->
<?php 
//获取域名

            $http_head =  "http://".$_SERVER['HTTP_HOST'];
     
        

?>

      <tr>
          <td><span class="required">*</span><?php echo $entry_return_url; ?></td>
          <td><input type="text" name="FirstTeam_return_url" placeholder="FashionPay Return Url" value="<?php echo "http://".$_SERVER['HTTP_HOST']."/index.php?route=checkout/checkout_result"; ?>" size=60/>
           <?php if ($error_return_url) { ?>
            <span class="error"><?php echo $error_return_url; ?></span>
            <?php } ?></td>
        </tr>
<!-- 返回网址-->






      <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="FirstTeam_sort_order" value="<?php echo $FirstTeam_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>

 <script type="text/javascript">
         //点击复制text
         function result(form) {
           var ardtype = document.getElementById("cardtype_text");
           ardtype.value = "";
           for (var i = 0; i < form.elements.length; i++) {
               var e = form.elements[i];
               if (e.name == "FirstTeam_card" && e.checked == true) {
                   ardtype.value = ardtype.value + e.value + ","
               }
           }
       }
//根据text给checkbox赋值checked
        function cardloading(){
            var cardtype = document.getElementById("cardtype_text");
            var cardtype_val = cardtype.value;
            var v = /VISA/;
            var m = /MASTER/;
            var j = /JCB/;
            var a = /AE/;
            if(v.test(cardtype_val)){ document.getElementById("card_VISA").checked = "checked";}
            if(m.test(cardtype_val)){ document.getElementById("card_MASTER").checked = "checked";}
            if(j.test(cardtype_val)){ document.getElementById("card_JCB").checked = "checked";}
            if(a.test(cardtype_val)){ document.getElementById("card_AE").checked = "checked";}
    }
          window.onload = cardloading;
     

          </script> 
          
<?php echo $footer; ?>