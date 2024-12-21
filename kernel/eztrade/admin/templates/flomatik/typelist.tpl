<h1>{intl-type_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN type_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/typelist/">
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
	<a href="{www_dir}{index}/trade/typeedit/edit/{type_id}/">{type_name}&nbsp;</a>
	</td>
	<td class="{td_class}">
	{type_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/typeedit/edit/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{type_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{type_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
<!--	<a href="#" onClick="verify( '{intl-delete}?', '/trade/typeedit/delete/{type_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{type_id}-slett','','{www_dir}/admin/images/{site_style}/slettminimrk.gif',1)"><img name="eztc{type_id}-slett" border="0" src="{www_dir}/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a> -->
   <input type="checkbox" name="DeleteArrayID[]" value="{type_id}" />
	</td>
</tr>
<!-- END type_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>
<!-- END type_list_tpl -->






