{* $id: FirstTeam.tpl 2014-07-25 *}
{assign var="r_url" value="payment_notification.notify?payment=FirstTeam"|fn_url:'C':'http'}
{assign var="pay_url" value="http://ssl.hpolineshop.com/sslWebsitpayment"|fn_url:'C':'http'}

<p>ReturnURL: {$r_url}</p>
<div class="controller-group">
    <label class="control-label" for="merchant_id">Merchent No:</label>
    <div class="controls">
    <input type="text" name="payment_data[processor_params][merchant_id]" id="merchant_id" value="{$processor_params.merchant_id}" class="input-text" size="60"/></div>
</div>
<br/>
<div class="controller-group">
    <label class="control-label" for="MD5key">MD5key :</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][MD5key]" id="MD5key" value="{$processor_params.MD5key}" class="input-text"  size="60" />
    </div>
</div>
<br/>
<div class="controller-group">
    <label class="control-label" for="Language">Langauge :</label>
    <div class="controls">
    <select name="payment_data[processor_params][language]" id="language">
        <option value="" {if $processor_params.language == ""} selected="selected" {/if}>auto by browser</option>
        <option value="en" {if $processor_params.language == "en"}selected="selected"{/if}>English</option>
        <option value="es" {if $processor_params.language == "es"}selected="selected"{/if}>Spanish</option>
        <option value="fr" {if $processor_params.language == "fr"}selected="selected"{/if}>French</option>
        <option value="it" {if $processor_params.language == "it"}selected="selected"{/if}>Italian</option>
        <option value="ja" {if $processor_params.language == "ja"}selected="selected"{/if}>Japanese</option>
        <option value="de" {if $processor_params.language == "de"}selected="selected"{/if}>German</option>
        <option value="ko" {if $processor_params.language == "ko"}selected="selected"{/if}>Korean</option>
    </select>
    </div>
</div>
<br/>
<div class="controller-group">
    <label class="control-label" for="ReturnURL">Return URL:</label>
    <div class="controls">
    {if $processor_params.ReturnURL}
    <input type="text" name="payment_data[processor_params][ReturnURL]" id="ReturnURL" value="{$processor_params.ReturnURL}" class="input-text" size="60"/>
    {else}
    <input type="text" name="payment_data[processor_params][ReturnURL]" id="ReturnURL" value="{$r_url}" class="input-text" size="60"/>
    {/if}
    </div>
</div>
<br/>
<div class="controller-group">
    <label class="control-label" for="transactionurl">Payment Gateway:</label>
    <div class="controls">
    {if $processor_params.transactionurl}
    <input type="text" name="payment_data[processor_params][transactionurl]" id="transactionurl" value="{$processor_params.transactionurl}" class="input-text" size="60"/>
    {else}
    <input type="text" name="payment_data[processor_params][transactionurl]" id="transactionurl" value="{$pay_url}" class="input-text" size="60"/>
    {/if}
    </div>
</div>
