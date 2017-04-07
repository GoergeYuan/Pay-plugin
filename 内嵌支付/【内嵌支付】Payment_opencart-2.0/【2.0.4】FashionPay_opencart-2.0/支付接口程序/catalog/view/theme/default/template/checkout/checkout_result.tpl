<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />
</head>
<body>
<?php echo $header; ?><?php echo $content_top; ?><?php echo $column_right; ?><?php echo $content_bottom; ?><?php echo $column_left; ?>

<div style="text-align: center;">

	<h1><?php echo $Result_title; ?> </h1>
	<p><?php echo $text_Result_code."&nbsp".$text_Error; ?></p>
	<p><?php 
	if(!empty($text_ResultMessage)){

		echo $text_response."&nbsp".$text_ResultMessage;

	} ?></p>

 
  <div style="border: 1px solid #DDDDDD; margin-bottom: 20px; width: 350px; margin-left: auto; margin-right: auto;">
    <HoopPaydc ITEM=banner>
  </div>
  
  <p><?php echo $Your_BillNo; ?><?php echo $text_billno; ?></p>
  <p><?php echo $text_result; ?></p>
  <p><?php echo $text_failure_wait; ?></p>
</div>
<script type="text/javascript"><!--
var num = 15;
  function StartCount(){
    var second = document.getElementById("second");
    if(num==0){

      window.location.href="<?php echo $continue; ?>";
    }

    document.getElementById("card-seconds").innerHTML = num;
    num--;
  
    setTimeout("StartCount()",1000); 
  }
StartCount();
//setTimeout('location = \'<?php echo $continue; ?>\';', 10000);
//--></script>
<?php echo $footer; ?> 

</body>
</html>

