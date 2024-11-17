<form method="post">
<h1>This is an example with database</h1>
<hr noshade="noshade" size="4" />
<br />
<table width="100%" border="2">
<tr>
        <th>Text</th>
        <th>Delete</th>
</tr>
<!-- BEGIN row_tpl -->
<tr>
	<td>
	<input type="text" name="TextArray[]" value="{row_text}" />
	<input type="hidden" name="IDArray[]" value="{row_id}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteArrayID[]" value="{row_id}" />
	</td>
</tr>
<!-- END row_tpl -->
</table>
<input type="submit" name="Update" value="{intl-update}" />&nbsp;
<input type="submit" name="New" value="{intl-new}" />&nbsp;
<input type="submit" name="Delete" value="{intl-delete}" />
</form>
