<h1>{intl-link_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN link_list_tpl -->
<form method="post" action="{www_dir}{index}/link/linklist/">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-link}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN link_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/link/linkedit/edit/{link_id}/">{link_name}&nbsp;</a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{link_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{link_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
<!--	<a href="#" onClick="verify( '{intl-delete}?', '/link/linkedit/delete/{link_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{link_id}-slett','','{www_dir}/admin/images/{site_style}/slettminimrk.gif',1)"><img name="eztc{link_id}-slett" border="0" src="{www_dir}/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a> -->
   <input link="checkbox" name="DeleteArrayID[]" value="{link_id}" />
	</td>
</tr>
<!-- END link_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" link="submit" name="Delete" value="{intl-delete_selected}" />
</form>
<!-- END link_list_tpl -->






