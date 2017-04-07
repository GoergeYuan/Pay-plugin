<div  style="margin-top:0px;margin-left:10px; font-size:16px;text-align:left">
{if $payResult == '1'}
    <font color="green">{l s="{$errorMessage}" mod='neworder'}</font>
{elseif $payResult == '2'}
    <font color="orage">{l s="{$errorMessage}" mod='neworder'}</font>
{elseif $payResult == '-1'}
    <font color="red">{l s='Data validation failed !' mod='neworder'}</font>
{else}
    <font color="red">{$errorMessage}</font> 
{/if} 
</p>
<hr/>
</div>
<div  style="margin-top:0px;margin-left:10px; font-size:12px;text-align:left">
    <p>{l s="{$orderNoLabel}" mod='neworder'}{$orderNo}</p>
    <p>{l s="{$amountLabel}" mod='neworder'} :{$orderAmount}  {$orderCurrency}</p>
     <hr/>
</div>
