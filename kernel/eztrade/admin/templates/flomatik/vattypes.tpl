<h1>{intl-vat_types}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/vattypes/" method="post">


<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th>
        {intl-name}:
    </th>
    <th>
        {intl-percentage}:
    </th>
    <th>
        &nbsp;
    </th>
</tr>
<!-- BEGIN vat_item_tpl -->
<tr>
	<td class="{td_class}">
	  <input type="hidden" name="VatID[]" value="{vat_id}" />
	  <input type="text" name="VatName[]" value="{vat_name}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="5" name="VatValue[]" value="{vat_value}" />%
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="VatArrayID[]" value="{vat_id}">
	</td>
</tr>
<!-- END vat_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="Add" value="{intl-add_type}" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade size="4" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
