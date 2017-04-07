<div class="buttons">
<a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a>
</div>
<script type="text/javascript"><!--
	$("#button-confirm").bind("click",function(){
		$.ajax({
			type: "GET",
			url: "<?php echo $baseUrl; ?>index.php?route=payment/card/confirm",
			success: function()
			{
			location = "<?php echo $baseUrl; ?>index.php?route=payment/card/tocard";
			},
            error:function(){
            	alert("Request Error, Please Try Again");
            }
		});
	});
//--></script>
