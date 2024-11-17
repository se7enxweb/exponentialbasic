<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">
	<!-- BEGIN path_tpl -->
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/bug/archive/0/">{intl-top_level}</a>
	<!-- END path_tpl -->
	<!-- BEGIN path_item_tpl -->	
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/bug/archive/{module_id}/">{module_name}</a>
	<!-- END path_item_tpl -->
	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-bug_archive}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/bug/archive/{current_module_id}" enctype="multipart/form-data">

<div class="p">{current_module_description}</div>

<!-- BEGIN module_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="40%">{intl-module}:</th>
	<th width="40%">{intl-description}:</th>
	<th width="10%">{intl-open_bug_count}:</th>
	<th width="10%">{intl-bug_count}:</th>
</tr>
	
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/archive/{module_id}/">{module_name}</a>&nbsp;
	</td>

	<td class="{td_class}">
	<span class="small">{module_description}</span>
	</td>

	<td class="{td_class}">
	{open_bug_count}
	</td>

	<td class="{td_class}">
	{bug_count}
	</td>

</tr>
<!-- END module_item_tpl -->
</table>
<!-- END module_list_tpl -->


<!-- BEGIN bug_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="70%">{intl-bug}:</th>
	<th width="10%">{intl-status}:</th>
	<th width="10%">{intl-priority}:</th>
	<th width="10%">{intl-is_closed}:</th>
</tr>

<!-- BEGIN bug_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/bugview/{bug_id}/">
	{bug_name}
	</a>
	</td>

	<td class="{td_class}">
	<span class="small">{bug_status}</span>
	</td>

	<td class="{td_class}">
	<span class="small">{bug_priority}</span>
	</td>

	<td class="{td_class}">
	<!-- BEGIN bug_is_closed_tpl -->
	<span class="small">{intl-is_closed}</span>
	<!-- END bug_is_closed_tpl -->

	<!-- BEGIN bug_is_open_tpl -->
	<span class="small">{intl-is_open}</span>
	<!-- END bug_is_open_tpl -->
	
	<!-- BEGIN bug_edit_tpl -->
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-red','','/ezbug/admin/images/redigerminimrk.gif',1)"><img name="ezaa{bug_id}-red" border="0" src="{www_dir}/ezbug/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>	</td>
	<td class="{td_class}">
	<input type="checkbox" name="BugArrayID[]" value="{bug_id}">
	</td>
	<!-- END bug_edit_tpl -->
	</td>

</tr>
<!-- END bug_item_tpl -->


</table>
<!-- END bug_list_tpl -->

<!-- BEGIN bug_edit_buttons_tpl -->

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_bugs}" />
<!-- END bug_edit_buttons_tpl -->
</form>