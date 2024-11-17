<form method="post" action="{www_dir}{index}/trade/typeedit/">

<h1>{intl-type_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>


<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />


<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>
	{intl-attribute_name}:
	</th>
</tr>
<!-- BEGIN attribute_tpl -->
<tr>
	<td>
	<input type="hidden" name="AttributeID[]" value="{attribute_id}" />
	<input type="text" name="AttributeName[]" value="{attribute_name}" />
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewAttribute" value="{intl-new_attribute}" />

<hr noshade="noshade" size="4" />

<input type="hidden" name="TypeID" value="{type_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
