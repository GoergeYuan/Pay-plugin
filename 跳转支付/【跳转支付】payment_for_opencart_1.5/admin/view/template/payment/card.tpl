<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content" style="height:700px;">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merchantid; ?></td>
          <td><input type="text" name="card_merchant" value="<?php echo $card_merchant; ?>" />
            <?php if ($error_merchant) { ?>
            <span class="error"><?php echo $error_merchant; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_md5key; ?></td>
          <td><input type="text" name="card_md5key" value="<?php echo $card_md5key; ?>" />
            <?php if ($error_md5key) { ?>
            <span class="error"><?php echo $error_md5key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_callback; ?></td>
          <td><input type="text" name="card_returnurl" value="<?php echo $callback; ?>" style="width:700px;"></td>
        </tr>	
	<tr>
          <td><?php echo $entry_language; ?></td>
          <td>
		<select name="card_language">
			<option value="" <?php if($card_language == ""){ echo 'selected'; } ?>>auto select</option>
			<option value="de" <?php if($card_language == "de"){ echo 'selected'; } ?>>German</option>
			<option value="es" <?php if($card_language == "fa"){ echo 'selected'; } ?>>Spanish</option>
			<option value="fr" <?php if($card_language == "fr"){ echo 'selected'; } ?>>French</option>
			<option value="ko" <?php if($card_language == "es"){ echo 'selected'; } ?>>Korean</option>
			<option value="it" <?php if($card_language == "it"){ echo 'selected'; } ?>>Italian</option>
			<option value="en" <?php if($card_language == "en"){ echo 'selected'; } ?>>English</option>
			<option value="ja" <?php if($card_language == "da"){ echo 'selected'; } ?>>Japanese</option>
		</select>
	  </td>
        </tr>
		<tr>
			<td><?php echo $entry_transaction_url; ?></td>
			<td><input type="text" name="card_transaction_url" value="<?php echo $card_transaction_url; ?>" style="width:400px;"/><?php echo $ex_card_transaction_url; ?></td>
		</tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="card_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $card_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
          <td><?php echo $entry_success_order_status; ?></td>
          <td><select name="card_success_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $card_success_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
          <td><?php echo $entry_processing_order_status; ?></td>
          <td><select name="card_processing_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $card_processing_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
          <td><?php echo $entry_failed_order_status; ?></td>
          <td><select name="card_failed_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $card_failed_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="card_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $card_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="card_status">
              <?php if ($card_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="card_sort_order" value="<?php echo $card_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>