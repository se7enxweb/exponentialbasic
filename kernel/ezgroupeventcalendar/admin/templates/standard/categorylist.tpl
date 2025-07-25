<h1>{intl-category_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<form method="post" action="/groupeventcalendar/categoryedit/delete/" enccategory="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	{category_name}&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/groupeventcalendar/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-red','','/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{category_id}-red" border="0" src="/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
</tr>
<!-- END category_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteForums" value="{intl-delete_selected_categorys}">
</form>

<!-- END category_list_tpl -->

