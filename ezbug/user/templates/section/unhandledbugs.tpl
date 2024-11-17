<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-unhandled_bugs}</h1>

<br />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="5%">{intl-bug_id}:</th>
	<th width="44%">{intl-bug_name}/{intl-bug_module_name}:</th>
	<th width="40%">{intl-bug_submitter}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN bug_tpl -->
<tr>
	<td class="{td_class}">
	{bug_id}
	</td>

	<td class="{td_class}">
	{bug_name}<br /><span class="small">{bug_module_name}</span>
	</td>
	<td class="{td_class}">
	<span class="small">{bug_submitter}</span>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{bug_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{bug_id}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
</tr>
<!-- END bug_tpl -->
</table>