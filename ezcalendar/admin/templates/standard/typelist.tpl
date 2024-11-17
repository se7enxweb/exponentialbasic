<h1>{intl-type_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN type_list_tpl -->
<form method="post" action="{www_dir}{index}/calendar/typeedit/delete/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-type}:</th>
	<th>{intl-description}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr>
	<td class="{td_class}">
	{type_name}&nbsp;
	</td>
	<td class="{td_class}">
	{type_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/calendar/typeedit/edit/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{type_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{type_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="TypeArrayID[]" value="{type_id}">
	</td>
</tr>
<!-- END type_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteForums" value="{intl-delete_selected_types}">
</form>

<!-- END type_list_tpl -->

