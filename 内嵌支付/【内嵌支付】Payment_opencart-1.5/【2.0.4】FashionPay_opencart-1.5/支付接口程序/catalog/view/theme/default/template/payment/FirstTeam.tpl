<b style="margin-bottom: 3px; display: block;"><?php echo $text_credit_card; ?></b>
<div id="FirstTeamdirect" style="border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <table width="100%">
   <tr>
      <td align='right' style="width:25%;font: 12px/20px Verdana;color: #666666;"><?php echo $entry_cc_type; ?></td>
       <td ><?php echo $allowType_IMG; ?></td>
    </tr>
	<!--
	<tr>
      <td align='right' style="font: 12px/20px Verdana;color: #666666;"><?php echo $entry_cc_issue; ?></td>
      <td><input type="text" name="cc_issue" autocomplete="off" style="border: 1px solid #BBBBBB;float: left;margin: 5px 10px 5px 0;height:20px;font: 12px/20px Verdana;color: #666666;"/></td>
    </tr>
	-->
    <tr>
      <td align='right' style="padding-bottom:12px;font: 12px/20px Verdana;color: #666666;"><span class="required">*</span><?php echo $entry_cc_number; ?></td>
      <td><input type="text" style="margin-bottom:12px;" name="cc_number" id="cc_number" onkeyup="sc_onkeyup(this)" onkeydown="sc_onkeyup(this)" maxlength="19" autocomplete="off" class="large-field" />
	      <input type='hidden' id="clientIP" name="clientIP" value="">
	      <input type="hidden"   id="checkout_time" name="checkout_time" value="">
	  </td>
    </tr>
    <tr>
      <td align='right' style="padding-bottom:12px;font: 12px/20px Verdana;color: #666666;"><span class="required">*</span><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month" class="large-field" style="width:148px;margin-bottom:12px;">
	      <option value="">Month</option>
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['value']; ?> - <?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        <select name="cc_expire_date_year" class="large-field" style="width:148px;">
		  <option value="">Year</option>
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr float="center" style="position: relative;">
      <td style="padding-bottom:12px;font: 12px/20px Verdana;color: #666666;" align='right'><span class="required">*</span><?php echo $entry_cc_cvv2; ?></td>
      <td><input type="password" name="cvv" maxlength="3" autocomplete="off" size="12" style="width:120px;margin-bottom:12px;display:inline-block;" class="large-field"/> 
      <img src="catalog/view/theme/default/image/cvv_ico.jpg" alt="cvv_ico" id="cvv_box_ico" /><div id="cvv_box_none" style="display: none;"></div></td>
    </tr> 
  </table>
</div>
<div class="buttons">
    <div class="right">
		<a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a>
		<input type="button"  id="button-confirm" class="button" value="<?php echo $button_confirm; ?>">
	</div>
</div>
<!-- 获取ip 
<script language="javascript" type="text/javascript" src="http://pv.sohu.com/cityjson?ie=utf-8 ">  </script>
<script> document.getElementById("clientIP").value=returnCitySN.cip;</script>-->
<script src="catalog/view/theme/default/javascript/sc_card.js"></script>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {
		$.ajax({
			type: 'POST',
			url: 'index.php?route=payment/FirstTeam/send',
			data: $('#FirstTeamdirect :input'),
			dataType: 'json',
			timeout : 30000, //超时时间设置，30秒
				
			beforeSend: function() {
				$('#button-confirm').attr('disabled', true);
				
				$('#FirstTeamdirect').before('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
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

				if (json['success']) {
					location = json['success'];
				}
			    
			    if (json['info']) {
					alert(json['info']);
				}
				
				if (json['error']) {
					//alert(json['error']);
					location = 'index.php?route=checkout/checkout_result';
				}
			}	
		});
	});
</script>
<script language="javascript" type="text/javascript">
//获取时间
var now=new Date();
var year=now.getFullYear();
var month=now.getMonth()+1;
var day=now.getDate();
var hour=now.getHours();
var minute=now.getMinutes();
var seconds=now.getSeconds();

	if(month<10){
		month="0"+month;
	}
	if(day<10){
		day="0"+day;
	}
	if(hour<10){
		hour="0"+hour;
	}
	if(minute<10){
		minute="0"+minute;
	}
	if(seconds<10){
		seconds="0"+seconds;
	}
document.getElementById("checkout_time").value =""+year+"-"+month+"-"+day+" "+hour+":"+minute+":"+seconds+"";
	

</script>
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
<style type="text/css">
	#cvv_box_none{
		background:url(catalog/view/theme/default/image/cvv_help.gif) no-repeat;
		height: 167px;
		width: 200px;
		position:absolute;
		left: 45%;
        z-index:88;
		bottom: 20px;
	    border: 1px solid #eee;
	    box-shadow: 0 0 10px #eee;
	    transition: opacity 0.3s ease 0s;
	  
	  		
	}

	#cvv_box_ico{
		position: absolute;
		left: 40%;
		top: 0px;
		cursor:pointer;

	}

</style>
