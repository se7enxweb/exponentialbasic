<h1>{intl-unhandled_bugs}</h1>

<hr noshade="noshade" size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="5%">{intl-bug_id}:</th>
	<th width="44%">{intl-bug_name}:</th>
	<th width="20%">{intl-bug_module_name}:</th>
	<th width="30%">{intl-bug_submitter}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN bug_tpl -->
<tr>
	<td class="{td_class}">
	{bug_id}
	</td>

	<td class="{td_class}">
	{bug_name}
	</td>
	<td class="{td_class}">
	{bug_module_name}
	</td>
	<td class="{td_class}">
	{bug_submitter}
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{bug_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{bug_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
</tr>
<!-- END bug_tpl -->
</table>
