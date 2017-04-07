<?php
?>
<div class="centerColumn" id="checkoutSuccess">

<h1 id="checkoutSuccessHeading" style="height: auto;">
<?php if ($messageStack->size('checkout_payresult') > 0) echo $messageStack->output('checkout_payresult'); ?>
</h1>

<!--zhang done<div id="checkoutSuccessOrderNumber"><?php echo 'your order number:' . $zv_orders_id; ?></div>-->

<!--bof logoff-->
<div id="checkoutSuccessLogoff">

<div class="buttonRow forward"><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo zen_image_button(BUTTON_IMAGE_LOG_OFF , BUTTON_LOG_OFF_ALT); ?></a></div>
</div>
<!--eof logoff-->
<h3 id="checkoutSuccessThanks" class="centeredContent"><?php echo 'thank you for shopping'; ?></h3>
</div>
