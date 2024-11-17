
<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-bug_archive}<!-- - {current_module_name}--></h1>
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_module_description}</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/bug/archive/{current_module_id}/">

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/bug/archive/0/">{intl-top_level}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="{www_dir}{index}/bug/archive/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->


<!-- BEGIN module_list_tpl -->
<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="65%">{intl-module}:</td>
	<th width="10%">{intl-unhandled_bug_count}:</td>
	<th width="10%">{intl-open_bug_count}:</th>
	<th width="10%">{intl-bug_count}:</th>
	<th width="5%">&nbsp;</th>
</tr>
	
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bug/archive/{module_id}/">{module_name}</a>&nbsp;
	</td>
	
	<td class="{td_class}">
	  {unhandled_bug_count}
	</td>

	<td class="{td_class}">
	  {open_bug_count}
	</td>

	<td class="{td_class}">
	  {bug_count}
	</td>
	<td class="{td_class}">&nbsp;&nbsp;</td>

<!--	<td class="{td_class}">
	{module_description}&nbsp;
	</td>-->

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/bug/module/edit/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{module_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{module_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
</tr>
<!-- END module_item_tpl -->
</table>

<!-- END module_list_tpl -->


<!-- BEGIN bug_list_tpl -->
<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="60%">{intl-bug}:</th>
	<th width="10%">{intl-status}:</th>
	<th width="10%">{intl-priority}:</th>
	<th width="10%">{intl-is_closed}:</th>
	<th width="10%" colspan="2">&nbsp;</th>
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
	<a href="{www_dir}{index}/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-red','','{www_dir}/ezbug/design/admin/images/redigerminimrk.gif',1)"><img name="ezaa{bug_id}-red" border="0" src="{www_dir}/ezbug/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="BugArrayID[]" value="{bug_id}">
	</td>
</tr>
<!-- END bug_item_tpl -->

</table>
<!-- END bug_list_tpl -->

<hr noshade size="4"/>

<!-- BEGIN bug_delete_button_tpl -->
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_bugs}" />
<!-- END bug_delete_button_tpl -->

</form>

