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
    alert("只今、処理中です。しばらくお待ち下さい。");
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
    <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>
    <h2 class="title">クレジットカード情報の入力</h2>

    <p class="information">
    ご自身のクレジットカードの情報を入力し、「ご注文完了ページへ」ボタンをクリックしてください。<br />
    ※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。
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
          <th>ご利用いただけるカードの種類</th>
          <td>
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->logo_firstteam.png">
          </td>
        </tr>
        <tr id="card_no">
          <th>カード番号</th>
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
            <br /><p class="mini"><span class="attention">ご本人名義のカードをご使用ください。</span><br />半角入力（例：1234-5678-9012-3456）</p>
          </td>
        </tr>
        <tr id="card_security">
          <th>セキュリティコード</th>
          <td>
            <!--{assign var=key1 value="security_code"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="3">
            <p><a class="aCvv" href="#" onclick="javascript:window.open('<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->cvv_help.html', 'cvvdemo','height=300,width=400,top=200,left=500,fullscreen=no');return false;">
             <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->cvv_ico.jpg" style="border-style: None; vertical-align: middle;margin:-2.5em 0 0 5em" />(これは何ですか?)</a></p>
          </td>
        </tr>
        <tr id="card_exp">
          <th>有効期限</th>
          <td>
            <!--{assign var=key1 value="card_month"}-->
            <!--{assign var=key2 value="card_year"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <span class="attention"><!--{$arrErr[$key2]}--></span>
            <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
              <option value="">--</option>
              <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
            </select>月/
            <select name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" >
              <option value="">--</option>
              <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
            </select>年
          </td>
        </tr>
        <tr id="card_hold">
          <th>カード名義<br />（ローマ字）</th>
          <td>
            <!--{assign var=key2 value="card_name01"}-->
            <!--{assign var=key1 value="card_name02"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <span class="attention"><!--{$arrErr[$key2]}--></span>
            名&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
            姓&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
            <br /><p class="mini">半角入力（例：TARO YAMADA）</p>
          </td>
        </tr>
        
         <tr id="card_country">
          <th>国</th>
          <td>
            <!--{assign var=key1 value="card_country"}-->
            <span class="attention"><!--{$arrErr[$key1]}--></span>
            <select name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->"  style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" >
	        <option value="">--</option>
	        <!--{html_options options=$arrCountry selected=$arrForm[$key1].value}-->
          </td>
         </tr>
        </tbody>
      </table>

      <div class="btn_area">
        <ul>
          <li>
          <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03"/>
          </li>
          <li>
          <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ"  name="next" id="next" />
          </li>
        </ul>
      </div>
    </form>

  </div>
</div>
<!--▲CONTENTS-->
