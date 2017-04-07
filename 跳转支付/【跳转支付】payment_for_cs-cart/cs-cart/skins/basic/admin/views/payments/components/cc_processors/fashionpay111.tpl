{* $Id: winbank.tpl 10055 2010-07-14 10:15:19Z klerik $ *}

{assign var="main_url" value="`$config.http_location`/`$config.customer_index`"|fn_url:'C'}
{assign var="ok_url" value="payment_notification.notify?payment=fashionpay"|fn_url:'C':'http':'&'}
<p>Return URL:{$ok_url}</p>
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
		<option value="en" {if $processor_params.language == "en"}selected="selected"{/if}>English</option>
		<option value="es" {if $processor_params.language == "es"}selected="selected"{/if}>Spanish</option>
		<option value="fr" {if $processor_params.language == "fr"}selected="selected"{/if}>French</option>
		<option value="it" {if $processor_params.language == "it"}selected="selected"{/if}>Italian</option>
		<option value="ja" {if $processor_params.language == "ja"}selected="selected"{/if}>Japanese</option>
		<option value="de" {if $processor_params.language == "de"}selected="selected"{/if}>German</option>
	</select>
</div>

<div class="form-field">
	<label for="currency">{$lang.currency}:</label>
	<select name="payment_data[processor_params][currency]" id="currency">
		<option value="1" {if $processor_params.currency == "1"}selected="selected"{/if}>USD</option>
		<option value="2" {if $processor_params.currency == "2"}selected="selected"{/if}>EUR</option>
		<option value="3" {if $processor_params.currency == "3"}selected="selected"{/if}>CNY</option>
		<option value="4" {if $processor_params.currency == "4"}selected="selected"{/if}>GBP</option>
		<option value="5" {if $processor_params.currency == "5"}selected="selected"{/if}>HKD</option>
		<option value="6" {if $processor_params.currency == "6"}selected="selected"{/if}>JPY</option>
		<option value="7" {if $processor_params.currency == "7"}selected="selected"{/if}>AUD</option>
		<option value="8" {if $processor_params.currency == "8"}selected="selected"{/if}>CAD</option>
		<option value="9" {if $processor_params.currency == "9"}selected="selected"{/if}>NOK</option>
	</select>
</div>
