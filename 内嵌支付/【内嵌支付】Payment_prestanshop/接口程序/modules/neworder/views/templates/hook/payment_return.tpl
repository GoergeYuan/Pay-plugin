<p>{l s={$errorMessage} sprintf=$shop_name mod='neworder'}
		<br /><br />
		<br /><br />- {l s={$amountLabel} mod='neworder'} &nbsp;&nbsp;{l s={$orderCurrency} mod='neworder'}
		<span class="price"><strong>{$total_to_neworder}</strong></span>
		<br /><br />{l s='Your order on' mod='neworder'} : {l s={$orderNo} mod='neworder'} <span ><strong>{$id_order}</strong></span>
		<br /><br />{l s='If you have questions, comments or concerns, please contact our' mod='neworder'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team' mod='neworder'}</a>
</p>

