<style type="text/css">
<!--
div#under02column_shopping table.stockcard {
  width: 540px;
  text-align: center;
  margin: 5px auto;
}

div#under02column_shopping table.stockcard tbody th {
  text-align: center;
  white-space: nowrap;
}

div#under02column_shopping table.stockcard td {
  text-align: center;
  white-space: nowrap;
}
-->
</style>
<script type="text/javascript">//<![CDATA[
var send = true;

window.onunload=function(){
}
window.onload=function onloadCashClear() {
  if (send) {
    return false;
  } else {
    sendo = true;
    return false;
  }
}

function fnCheckSubmit(mode) {
  if(send) {
    send = false;
    fnModeSubmit(mode,'','');
    return false;
  } else {
    alert("Please wait while processing transaction.");
    return false;
  }
}

function fnCngStock() {
  arr_obj = new Array('card_no', 'card_exp', 'card_hold', 'card_stock');
  flg = document.form1.stock.checked;
  for (i=0; i < arr_obj.length; i++) {
    obj = document.all && document.all(arr_obj[i]) || document.getElementById && document.getElementById(arr_obj[i]);
    if (flg) {
      obj.style.display = "none";
    } else {
      obj.style.display = "";
    }
  }
}

function next(now, next) {
  if (now.value.length >= now.getAttribute('maxlength')) {
    next.focus();
  }
}
//]]>
</script>

<!--▼CONTENTS-->
<div id="undercolumn">
  <div id="undercolumn_shopping">
    <div class="flow_area">
      <ol>
        <li><span>&gt; STEP1</span><br />Delivery destination</li>
        <li class="large"><span>&gt; STEP2</span><br />Payment method and delivery time</li>
        <li class="active"><span>&gt; STEP3</span><br />Confirmation</li>
        <li class="last"><span>&gt; STEP4</span><br />Order complete</li>
      </ol>
    </div>
    <h2 class="title">Credit card information</h2>
    <p class="information">
    Enter information on your own credit card, then press "Completion Page" button.
    </p>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
      <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
      <input type="hidden" name="mode" value="next">
      <table summary="お支払詳細入力" class="delivname">
        <tbody>
        <!--{if $tpl_error != ""}-->
        <tr>
          <td colspan="2">
            <span class="attention"><!--{$tpl_error}--></span>
          </td>
        </tr>
        <!--{/if}-->
        <tr>
          <th>Card Type</th>
          <td>
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->logo_firstteam.png">
          </td>
        </tr>
        <tr id="card_no">
          <th>Card number</th>
          <td>
            <!--{assign var=key1 value="card_no01"}-->
            <!--{assign var=key2 value="card_no02"}-->
            <!--{assign var=key3 value="card_no03"}-->
            <!--{assign var=key4 value="card_no04"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <span class="attention"><!--{$arrErr[$key2]}--></span>
            <span class="attention"><!--{$arrErr[$key3]}--></span>
            <span class="attention"><!--{$arrErr[$key4]}--></span>
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key2}-->)" >&nbsp;-&nbsp;
            <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key3}-->)" >&nbsp;-&nbsp;
            <input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key3]|sfGetErrorColor}-->"  size="6" onkeyup="next(this, this.form.<!--{$key4}-->)" >&nbsp;-&nbsp;
            <input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4].value|h}-->" maxlength="<!--{$arrForm[$key4].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key4]|sfGetErrorColor}-->"  size="6">
          </td>
        </tr>
        <tr id="card_security">
          <th>Card security code</th>
          <td>
            <!--{assign var=key1 value="security_code"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="3">
            <p><a class="aCvv" href="#" onclick="javascript:window.open('<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->cvv_help.html', 'cvvdemo','height=300,width=400,top=200,left=500,fullscreen=no');return false;">
             <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->cvv_ico.jpg" style="border-style: None; vertical-align: middle;margin:-2.5em 0 0 5em" />(what is this?)</a></p>
          </td>
        </tr>
        <tr id="card_exp">
          <th>Card expiration</th>
          <td>
            <!--{assign var=key1 value="card_month"}-->
            <!--{assign var=key2 value="card_year"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <span class="attention"><!--{$arrErr[$key2]}--></span>
            Month:<select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
              <option value="">--</option>
              <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
            </select>
            Year:<select name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" >
              <option value="">--</option>
              <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
            </select>
          </td>
        </tr>
        <tr id="card_hold">
          <th>Name of cardholder</th>
          <td>
            <!--{assign var=key2 value="card_name01"}-->
            <!--{assign var=key1 value="card_name02"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <span class="attention"><!--{$arrErr[$key2]}--></span>
            First Name&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
            Last Name&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          </td>
          <tr id="card_country">
          <th>Country</th>
          <td>
            <!--{assign var=key1 value="card_country"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->"  style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
	        <option value="">--</option>
	        <!--{html_options options=$arrCountry selected=$arrForm[$key1].value}-->
	        </select>
          </td>
         </tr>
      </div>
      </tbody>
    </table>

    <div class="btn_area">
      <ul>
        <li>
        <button onclick="return fnCheckSubmit('return');" class="bt04">Back</button>
        </li>
        <li>
        <button class="bt02" onclick="return fnCheckSubmit('next');">Completion page</button>
        </li>
      </ul>
    </div>
  </form>

</div>
</div>
<!--▲CONTENTS-->
