<form method="post" action="{www_dir}{index}/user/groupedit/new/">
<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-description}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN group_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/user/ingroup/{group_id}">{group_name}</a>
	</td>

	<td class="{td_class}">
	{group_description}
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/user/groupedit/edit/{group_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{group_id}-red','','{www_dir}/design/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezuser{group_id}-red" border="0" src="{www_dir}/design/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="GroupArrayID[]" value="{group_id}">
	</td>
</tr>
<!-- END group_item_tpl -->

</table>

<hr noshade size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="New" value="{intl-new_group}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" Name="DeleteGroups" value="{intl-delete_groups}">
	</td>
</table>
</form>

