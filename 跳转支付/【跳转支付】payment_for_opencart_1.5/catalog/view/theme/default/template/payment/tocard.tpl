<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<h1 style="text-align:center">You will be redirected to CreditCard GateWay in a few seconds.</h1>
<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout_card" name="checkout_card">
  <input type="hidden" name="MerNo" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="BillNo" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="Amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="Currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="Language" value="<?php echo $language; ?>" />
  <input type="hidden" name="ReturnURL" value="<?php echo $returnURL; ?>"/>
  <input type="hidden" name="Remark" value="<?php echo $remark; ?>" />
  <input type="hidden" name="MD5info" value="<?php echo $MD5info; ?>" />
  <input type="hidden" name="MerWebsite" value="<?php echo $MerWebsite; ?>"/>
  <input name="DeliveryFirstName" type="hidden"  value="<?php echo $DeliveryFirstName; ?>" />
	<input name="DeliveryLastName" type="hidden"  value="<?php echo $DeliveryLastName; ?>" />
	<input name="DeliveryEmail" type="hidden"  value="<?php echo $DeliveryEmail; ?>" />
	<input name="DeliveryPhone" type="hidden"  value="<?php echo $DeliveryPhone; ?>" />
	<input name="DeliveryZipCode" type="hidden"  value="<?php echo $DeliveryZipCode; ?>" />
	<input name="DeliveryAddress" type="hidden"  value="<?php echo $DeliveryAddress; ?>" />
	<input name="DeliveryCity" type="hidden"  value="<?php echo $DeliveryCity; ?>"/>
	<input name="DeliveryState" type="hidden"  value="<?php echo $DeliveryState; ?>" />
	<input name="DeliveryCountry" type="hidden"  value="<?php echo $DeliveryCountry; ?>" />
    <input name="NoticeURL" type="hidden" value="<?php echo $NoticeURL; ?>"/>
    <input name="Products" type="hidden" value="<?php echo $Products; ?>"/>
</form>
<script type="text/javascript">
	document.getElementById('checkout_card').submit();
</script>
<?php echo $footer; ?>
