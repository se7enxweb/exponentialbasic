<h1>{intl-attribute_edit} - {link_name}</h1>

<hr noshade size="4" />
<form method="post" action="{www_dir}{index}/link/linkedit/attributeedit/{link_id}/" >

<br />

<select name="TypeID">
<option value="-1">{intl-no_attributes}</option>
<!-- BEGIN type_tpl -->
<option value="{type_id}" {selected}>{type_name}</option>
<!-- END type_tpl -->
</select>&nbsp;<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />

<br /><br />

<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>{intl-attribute_name}:</th>
	<th>{intl-attribute_value}:</th>
</tr>
<!-- BEGIN attribute_tpl -->
<tr>
	<td>
	{attribute_name}: 
	</td>
	<td>
	<input type="hidden" name="AttributeID[]" value="{attribute_id}" />
	<input type="text" name="AttributeValue[]" value="{attribute_value}" />
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />

<hr noshade size="4" />

<input type="hidden" name="LinkID" value="{link_id}" />
<input type="hidden" name="Action" value="Update" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />

<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>