<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />
</head>
<body>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div style="text-align: center;">
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_response; ?></p>
  <div style="border: 1px solid #DDDDDD; margin-bottom: 20px; width: 350px; margin-left: auto; margin-right: auto;">
    <HoopPaydc ITEM=banner>
  </div>
  
  <p><?php echo $Your_BillNo; ?><?php echo $text_billno; ?></p>
  <p><?php echo $text_failure; ?></p>
  <p><?php echo $text_failure_wait; ?></p>
</div>
<script type="text/javascript"><!--
setTimeout('location = \'<?php echo $continue; ?>\';', 2500);
//--></script>
<?php echo $footer; ?> 

</body>
</html>