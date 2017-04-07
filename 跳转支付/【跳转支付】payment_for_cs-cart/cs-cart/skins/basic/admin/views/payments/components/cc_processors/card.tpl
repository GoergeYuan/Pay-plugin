{* $Id: card.tpl 10055 2010-07-14 10:15:19Z klerik $ *}

{assign var="main_url" value="`$config.http_location`/`$config.customer_index`"|fn_url:'C'}
{assign var="ok_url" value="payment_notification.notify?payment=card"|fn_url:'C':'http':'&'}
{assign var="returnURL" value="`$config.http_location`/index.php?dispatch=payment_notification.notify&payment=card"}
<p>Return URL:{$returnURL}</p>
{assign var="submitUrl" value="`$config.http_location`/submitOrder.php"}
<hr />

<div class="form-field">
	<label for="merchant_id">{$lang.merchant_id}:</label>
	<input type="text" name="payment_data[processor_params][merchant_id]" id="merchant_id" value="{$processor_params.merchant_id}" class="input-text"  size="60" />
</div>

<div class="form-field">
	<label for="MD5key">MD5key:</label>
	<input type="text" name="payment_data[processor_params][MD5key]" id="MD5key" value="{$processor_params.MD5key}" class="input-text"  size="60" />
</div>

<div class="form-field">
	<label for="language">{$lang.language}:</label>
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
<div class="form-field">
    <label for="returnurl">ReturnURL:</label>
    {if $processor_params.ReturnURL}
    <input type="text" name="payment_data[processor_params][ReturnURL]" id="ReturnURL" value="{$processor_params.ReturnURL}" class="input-text" size="60"/>
    {else}
    <input type="text" name="payment_data[processor_params][ReturnURL]" id="ReturnURL" value="{$returnURL}" class="input-text" size="60"/>
    {/if}
</div>
<div class="form-field">
    <label for="transactionurl">Transaction URL:</label>
    {if $processor_params.transactionurl}
    <input type="text" name="payment_data[processor_params][transactionurl]" id="transactionurl" value="{$processor_params.transactionurl}" class="input-text" size="60"/>
    {else}
    <input type="text" name="payment_data[processor_params][transactionurl]" id="transactionurl" value="" class="input-text" size="60"/>
    {/if}
</div>
