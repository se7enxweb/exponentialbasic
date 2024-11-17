<form action="{www_dir}{index}/todo/statustypeedit/new/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN status_item_tpl -->
<tr>
	<td class="{td_class}">
	{status_type_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/todo/statustypeedit/edit/{status_type_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ct{status_type_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ct{status_type_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/todo/statustypeedit/delete/{status_type_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ct{status_type_id}-slett','','{www_dir}/design/admin/images/{site_style}/slettminimrk.gif',1)"><img name="ct{status_type_id}-slett" border="0" src="{www_dir}/design/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END status_item_tpl -->

</table>

<hr noshade size="4"/>

<input class="stdbutton" type="submit" value="{intl-newstatus}">

</form>
