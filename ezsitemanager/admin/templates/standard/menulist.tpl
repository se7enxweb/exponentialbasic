<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-menus}</h1>
     </td>
</tr>
<tr>
	<td>
	<p class="boxtext">({menu_start}-{menu_end}/{menu_total})</p>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/sitemanager/menu/list/0/">{intl-top}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/sitemanager/menu/list/{category_id}/">{category_name}</a>

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/sitemanager/menu/edit/" method="post">
<!-- BEGIN menu_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
         <th>{intl-id}</th>
         <th>{intl-name}</th>
         <th>{intl-link}</th>
</tr>
<!-- BEGIN menu_item_tpl -->
<tr class="{td_class}">
	<td width="5%">
	{menu_id}
	</td>

	<td width="42%">
	<a href="{www_dir}{index}/sitemanager/menu/list/{menu_id}/">{menu_name}</a>
	</td>

	<td width="50%">
	{menu_link}
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/sitemanager/menu/edit/{menu_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezsitemanager{menu_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezsitemanager{menu_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<input type="checkbox" name="MenuArrayID[]" value="{menu_id}">
	</td>
</tr>
<!-- END menu_item_tpl -->
</table>
<!-- END menu_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="New" value="{intl-new_menu}" />&nbsp;
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_menus}" />



</form>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/sitemanager/menu/list/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/sitemanager/menu/list/parent/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/sitemanager/menu/list/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
