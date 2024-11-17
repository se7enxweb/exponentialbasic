<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-search} - ( {query_text} )</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />


<!-- BEGIN bug_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-bug}:</th>
	<th>{intl-status}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-is_closed}:</th>

	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN bug_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/bugpreview/{bug_id}/">
	{bug_name}&nbsp;
	</a>
	</td>

	<td class="{td_class}">
	{bug_status}&nbsp;
	</td>

	<td class="{td_class}">
	{bug_priority}&nbsp;
	</td>

	<td class="{td_class}">
	<!-- BEGIN bug_is_closed_tpl -->
	{intl-is_closed}&nbsp;
	<!-- END bug_is_closed_tpl -->

	<!-- BEGIN bug_is_open_tpl -->
	{intl-is_open}&nbsp;
	<!-- END bug_is_open_tpl -->

	</td>

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-red','','{www_dir}/design/admin/images/redigerminimrk.gif',1)"><img name="ezaa{bug_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/bug/bugedit/delete/{bug_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-slett','','{www_dir}/design/admin/images/slettminimrk.gif',1)"><img name="ezaa{bug_id}-slett" border="0" src="{www_dir}/design/admin/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END bug_item_tpl -->

</table>
<!-- END bug_list_tpl -->
