<form action="{www_dir}{index}/form/form/fixedvalues/{form_id}/{element_id}" method="post">

<h1>{intl-fixed_values}</h1>

<hr noshade="noshade" size="4" />

<br />
<!-- END error_list_tpl -->

<!-- BEGIN value_list_tpl -->
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
	<th>{intl-value_name}:</th>
<!-- BEGIN value_item_tpl -->
<tr>
    <td class="{td_class}">
    <input type="text" name="Value[]" value="{value}" />
    </td>
    <td class="{td_class}" align="right">
    <input type="hidden" name="ValueID[]" value="{value_id}" />
    <input type="checkbox" name="ValueDeleteID[]" value="{value_id}" />
    </td>
</tr>
<!-- END value_item_tpl -->

</table>
<!-- END value_list_tpl -->

<!-- BEGIN no_values_item_tpl -->

<!-- END no_values_item_tpl -->

<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="AddValue" value="{intl-add_value}" />&nbsp;
<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
<br />
<br />


<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>