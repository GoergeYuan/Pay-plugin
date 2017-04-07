<!--{if $tpl_mode != 'add'}-->
<h2><!--{$smarty.const.MDL_FASHIONPAY_PAYMENT_METHOD}--></h2>
<table class="form">
  <tr>
    <th>TransactionAmount</td>
    <td><!--{$arrFashionpayOrder.memo02|h}--></td>
  </tr>
  <tr>
    <th>TransactionCurrency</td>
    <td><!--{$arrFashionpayOrder.memo03|h}--></td>
  </tr>
  <tr>
    <th>TransactionID</td>
    <td><!--{$arrFashionpayOrder.memo04|h}--></td>
  </tr>
  <tr>
    <th>Return Code</td>
    <td><!--{$arrFashionpayOrder.memo05|h}--></td>
  </tr>
  <tr>
    <th>Result</td>
    <td><!--{$arrFashionpayOrder.memo06|h}--></td>
  </tr>
  <tr>
    <th>Description</td>
    <td><!--{$arrFashionpayOrder.memo07|h}--></td>
  </tr>
</table>
<!--{/if}-->
<!--{assign var=path value=`$smarty.const.TEMPLATE_ADMIN_REALDIR`order/edit.tpl}-->
<!--{include file=$path}-->
