<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-FirstTeam" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default" style="height:800px;">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      
 
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-FirstTeam" class="form-horizontal">
        
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="FirstTeam_status" class="form-control" style="width:148px;">
              <?php if ($FirstTeam_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
            </div>
          </div>


      <!--  卡号选择 -->
          <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-cardtype"><?php echo $entry_Card_Type; ?></label>
          <div class="col-sm-10">
            <label><input name="FirstTeam_card"  id="card_VISA" type="checkbox"  onClick="result(this.form)" value="<?php echo $entry_Card_VISA; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_VISA; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_JCB" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_JCB; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_JCB; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_MASTER" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_MASTER; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_MASTER; ?></label>&nbsp;&nbsp;&nbsp;
            <label><input name="FirstTeam_card"  id="card_AE" type="checkbox"   onClick="result(this.form)" value="<?php echo $entry_Card_AE; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $entry_Card_AE; ?></label>
            <input type="hidden" name="FirstTeam_cardtype" value="<?php echo $FirstTeam_cardtype; ?>" id="cardtype_text">
              <?php if ($error_cardtype) { ?>
              <div class="text-danger"><?php echo $error_cardtype; ?></div>
              <?php } ?>
            </div>
          </div>
          
         <!--  卡号选择 -->  

        <!--  商户号 -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-merchant"><?php echo $entry_account; ?></label>
            <div class="col-sm-10">
              <input type="text" name="FirstTeam_account" value="<?php echo $FirstTeam_account; ?>" placeholder="FashionPay Account" id="input-merchant" class="form-control" />
              <?php if ($error_account) { ?>
              <div class="text-danger"><?php echo $error_account; ?></div>
              <?php } ?>
            </div>
          </div>
           <!--  商户号 -->

    <!-- 商户key-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><span data-toggle="tooltip" title="<?php echo $entry_secret; ?>"><?php echo $entry_secret; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="FirstTeam_secret" value="<?php echo $FirstTeam_secret; ?>" placeholder="FashionPay KEY" id="input-password" class="form-control" />
              <?php if ($error_secret) { ?>
              <div class="text-danger"><?php echo $error_secret; ?></div>
              <?php } ?>
            </div>
          </div>
      
      <!-- 商户key-->  

  <!-- 订单默认状态 -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="FirstTeam_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $FirstTeam_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
  <!-- 订单默认状态 -->     

  <!-- 成功订单状态 -->
          
           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_succeed_status; ?></label>
			<div class="col-sm-10">
           <select name="FirstTeam_order_succeed_status_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_succeed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
              </div>
          </div>
  <!-- 成功订单状态 -->

 

  <!-- 失败订单状态 -->

      <div class="form-group">
    <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_failed_status; ?></label>
         <div class="col-sm-10">
          <select name="FirstTeam_order_failed_status_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_failed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            </div>
          </div>
        
    <!-- 失败订单状态 -->

     <!-- 支付等待、延时支付订单状态 -->
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_payWait_status_id; ?></label>
         <div class="col-sm-10">
          <select name="FirstTeam_order_payWait_status_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $FirstTeam_order_payWait_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            </div>
          </div>

  <!-- 支付等待、延时支付订单状态 -->

    <!-- 订单区域 -->     
        
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="FirstTeam_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $FirstTeam_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          
      <!-- 订单区域 -->

      <!-- 支付网关 -->   
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-merchant"><span data-toggle="tooltip" title="<?php echo $entry_payment_url; ?>"><?php echo $entry_payment_url; ?></span></label>
              <div class="col-sm-10">
                <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-link"></i>
              </span>
              <input type="text" name="FirstTeam_payment_url" placeholder="FashionPay Transaction URL" value="<?php echo $FirstTeam_payment_url; ?>" id="input-merchant" class="form-control" />
             </div>
              <?php if ($error_merchant) { ?>
              <div class="text-danger"><?php echo $error_merchant; ?></div>
              <?php } ?>
            </div>
          </div>

  <!-- 支付网关 -->

<!-- 返回网址 -->
<?php 
//获取域名
   if ($_SERVER['HTTPS'] != "on") {
            $http_head =  "http://".$_SERVER['HTTP_HOST'];
         }else{
            $http_head =  "https://".$_SERVER['HTTP_HOST'];
        }

?>
       <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-return_url"><span data-toggle="tooltip" title="<?php echo $entry_return_url; ?>"><?php echo $entry_return_url; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="FirstTeam_return_url" placeholder="FashionPay Return Url" value="<?php echo $http_head."/index.php?route=checkout/checkout_result"; ?>" id="input-return_url" class="form-control" />
              <?php if ($error_return_url) { ?>
              <div class="text-danger"><?php echo $error_return_url; ?></div>
              <?php } ?>
            </div>
          </div>

<!-- 返回网址 -->


         
          
     <!-- 支付模块显示顺序 -->   
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="FirstTeam_sort_order" value="<?php echo $FirstTeam_sort_order; ?>" placeholder="<?php echo $FirstTeam_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
     <!-- 支付模块显示顺序 -->   
        </form>
        
      </div>
    </div>
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