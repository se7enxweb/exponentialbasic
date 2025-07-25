<form action="/groupeventcalendar/grpdspl/" method="post">

<h1>{intl-group_display}</h1>

<hr size="4" noshade="noshade" />
<br />

<h3>{intl-instructions}</h3>
<!-- BEGIN group_list_tpl -->

<table width="100%" cellpadding="3" cellspacing="3" border="0">
<tr>
	<th>
	{intl-group_name}:
	</th>
	<td>
	&nbsp
	</td>
</tr>
<!-- BEGIN group_item_tpl -->
<tr>
	<td class="{bgcolor}">
	<input type="hidden" name="GroupIDArray[]" value="{group_id}" />
	{group_name}
	</td>
	<td class="{bgcolor}" width="1%">
	<input type="checkbox" name="NoDisplayIDArray[]" value="{group_id}" {group_is_checked} />
	</td>
</tr>

<!-- END group_item_tpl -->
</tr>
</table>
<br />
<!-- END group_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />
</form>
