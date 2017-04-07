<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<h2><!--{ $tpl_subtitle }--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="edit">

  <!--{if $arrErr.err != ""}-->
  <table border="0" cellspacing="0" cellpadding="0" summary=" ">
    <tr>
      <td><span class="red"><!--{$arrErr.err}--></span></td>
    </tr>
  </table>
  <!--{/if}-->

  <table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
      <th>Merchant ID<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="merchant_id"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
      </td>
    </tr>
    <tr>
      <th>Merchant Md5key<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="merchant_md5key"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
      </td>
    </tr>
    <tr>
      <th>Base Currency<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="base_currency"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->">
          <!--{html_options options=$arrBaseCurrency selected=$arrForm[$key].value}-->
        </select>
      </td>
    </tr>
    <tr>
      <th>Language<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="base_language"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->">
          <!--{html_options options=$arrBaseLanguage selected=$arrForm[$key].value}-->
        </select>
      </td>
    </tr>
     <tr>
      <th> Status of New Order <span class="red">※</span></th>
    	<td>
        <!--{assign var=key value="new_order"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->">
          <!--{html_options options=$arrBaseStatus selected=$arrForm[$key].value}-->
        </select>
      </td>
    </tr>
      <tr>
      <th> Status of Checkout Completion <span class="red">※</span></th>
    	<td>
        <!--{assign var=key value="succss_order"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->">
          <!--{html_options options=$arrBaseStatus selected=$arrForm[$key].value}-->
        </select>
      </td>
    </tr>
      <tr>
      <th> Status of Checkout Fail <span class="red">※</span></th>
    	<td>
        <!--{assign var=key value="fail_order"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <select name="<!--{$key}-->">
          <!--{html_options options=$arrBaseStatus selected=$arrForm[$key].value}-->
        </select>
      </td>
    </tr>
    <tr>
      <th>Gateway Url<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="gateway_url"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
      	 <!--{html_options options=$gatewayUrl selected=$arrForm[$key].value}-->
      </td>
    </tr>
      <tr>
      <th>Return Url<span class="red">※</span></th>
      <td>
        <!--{assign var=key value="return_url"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box20" maxlength="<!--{$arrForm[$key].length}-->">
      	<!--{html_options options=$returnUrl selected=$arrForm[$key].value}-->
      </td>
    </tr>
    <tr>
      <th colspan="2">▼ apply the customization</th>
    </tr>
    <tr>
      <th>Copy of the files</th>
      <td>
        <span class="red">
          ※ Fashionpay support online updates<br/>Plug-in list-> Fashionpay Payment Plug-in  2.0.4en-> Update <br />
        </span>
      </td>
    </tr>
  </table>

  <div class="btn-area">
    <ul>
      <li>
      <a class="btn-action" href="javascript:;" onclick="document.body.style.cursor = 'wait';document.form1.submit();return false;"><span class="btn-next">Save</span></a>
      </li>
    </ul>
  </div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
