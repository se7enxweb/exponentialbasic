<h1>{intl-box_types}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/boxtypes/" method="post">


<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th>
        {intl-name}:
    </th>
    <th>
        {intl-length}:
    </th>
    <th>
        {intl-width}:
    </th>
    <th>
        {intl-height}:
    </th>
    <th>
        &nbsp;
    </th>
</tr>
<!-- BEGIN box_item_tpl -->
<tr>
	<td class="{td_class}">
	  <input type="hidden" name="BoxID[]" value="{box_id}" />
	  <input type="text" name="BoxName[]" value="{box_name}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="5" name="Length[]" value="{length}" /> {intl-unit}
	</td>
	<td class="{td_class}">
	  <input type="text" size="5" name="Width[]" value="{width}" /> {intl-unit}
	</td>
	<td class="{td_class}">
	  <input type="text" size="5" name="Height[]" value="{height}" /> {intl-unit}
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="BoxArrayID[]" value="{box_id}">
	</td>
</tr>
<!-- END box_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="Add" value="{intl-add_type}" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade size="4" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
