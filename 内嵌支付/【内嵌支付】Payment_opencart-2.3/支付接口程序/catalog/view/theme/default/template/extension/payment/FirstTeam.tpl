<form class="form-horizontal">
  <fieldset id="FirstTeam_payment">
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="alert alert-warning" id="warning" style="display:none;"></div>
    <div class="form-group">
      <label class="col-sm-2 control-label" style="margin-top:30px;" for="input-cc-type"><?php echo $entry_cc_type; ?></label>
      <div class="col-sm-10">
        <?php echo $allowType_IMG; ?>
      </div>
    </div>
<!-- card number -->
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?><span>:</span></label>
      <div class="col-sm-10">
        <input type="text" name="FirstTeam_cc_number" maxlength="19" autocomplete="off"  onkeyup="sc_onkeyup(this)" onkeydown="sc_onkeyup(this)" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
        <input type="hidden" name="IPAddress" value="" id="clientIP"/>
      </div>
    </div>
<!-- card number -->

<!-- card date -->
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?><span>:</span></label>
      <div class="col-sm-3">
        <select name="FirstTeam_expire_date_month" id="input-cc-expire-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['value']; ?> - <?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        </div>
       <div class="col-sm-3">
        <select name="FirstTeam_expire_date_year" class="form-control">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
<!-- card date -->
   
 <!-- cvv -->
    <div class="form-group required" >
      <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?><span>:</span></label>
      <div class="col-sm-10" style="position:relative">
        <input type="text" name="FirstTeam_cvv" style="max-width:200px;" maxlength="3" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
        <img src="catalog/view/theme/default/image/cvv_ico.jpg" alt="cvv_ico" id="cvv_box_ico" /><div id="cvv_box_none" style="display: none;"></div>
    </div>
  </div>
 <!-- cvv -->

  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
  	<a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a>
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="loading" class="btn btn-primary" />
  </div>
</div>
<!--
<script language="javascript" type="text/javascript" src="http://pv.sohu.com/cityjson?ie=utf-8 ">  </script>    
<script>
 document.getElementById("clientIP").value=returnCitySN.cip;
</script> 
-->
<script type="text/javascript">
   /* cvv提示控制 */
 $(document).ready(function(){ 
   $("#cvv_box_ico").mousemove(function(){
  $("#cvv_box_none").css("display","block");
});
    $("#cvv_box_ico").mouseout(function(){
  $("#cvv_box_none").css("display","none");
});
});

</script>
<script src="catalog/view/theme/default/javascript/sc_card.js"></script>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=extension/payment/FirstTeam/send',
		type: 'post',
    timeout: 30000, //默认请求时间30秒
		data: $('#FirstTeam_payment :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#FirstTeam_payment').before('<div class="wait alert alert-info"><img src="catalog/view/theme/default/image/loading_1.gif" alt="loading" /> <?php echo $text_wait; ?></div>');					
		},
		complete: function(XMLHttpRequest,status) {
		 $('.wait').remove();
      $('#button-confirm').attr('disabled', false);
     if(status=='timeout'){    //超时,status还有success,error等值的情况
 　　　　　var warning = document.getElementById('warning');  
               warning.style.display="block";  
               warning.innerHTML = 'warning: request time out!';
　　　　}
    },
		success: function(json) {

			if (json['info']) {

			    	 var warning = document.getElementById('warning');  
         			 warning.style.display="block";  
         			 warning.innerHTML = 'warning:'+json['info'];
					//alert(json['info']);
				}

			if (json['error']) {
			//	alert(json['error']);
				location = 'index.php?route=extension/checkout/checkout_result';
			}
		
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>



  <style type="text/css">

    #cvv_box_none{
    background:url(catalog/view/theme/default/image/cvv_help.gif) no-repeat;
    height: 167px;
    width: 200px;
    position:absolute;
    left: 260px;
        z-index:88;
    bottom: 20px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px #eee;
      transition: opacity 0.3s ease 0s;
    
        
  }

  #cvv_box_ico{
    position: absolute;
    left: 225px;
    top: 5px;
    cursor:pointer;

  }

  </style>