<h1>{intl-currency}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/currency/" method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="30%">{intl-currency_name}:</th>
	<th width="23%">{intl-currency_sign}:</th>
	<th width="23%">{intl-prefixed_currency_sign}:</th>
	<th width="23%">{intl-currency_ratio}:</th>
	<th width="1%">&nbsp;</th>
<tr>

<!-- BEGIN currency_item_tpl -->
<tr>
	<th class="{td_class}">
	 <input type="hidden" name="CurrencyID[]" value="{currency_id}" />
	  <input type="text" size="12" name="CurrencyName[]" value="{currency_name}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="CurrencySign[]" value="{currency_sign}" />
	</td>
	<td class="{td_class}">
	<input type="radio" name="CurrencyPrefix_{currency_id}[]" {currency_prefixed} value="1" />&nbsp;<span class="small">{intl-prefix}</span><br />
	<input type="radio" name="CurrencyPrefix_{currency_id}[]" {currency_not_prefixed} value="0" />&nbsp;<span class="small">{intl-postfix}</span>
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="CurrencyValue[]" value="{currency_value}" />
	</td>
	<td class="{td_class}">
	  <input type="checkbox" name="DeleteID[]" value="{currency_id}" />
	</td>

</tr>
<!-- END currency_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="AddCurrency" value="{intl-add_currency}" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
