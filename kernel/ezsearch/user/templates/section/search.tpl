<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/design/base/images/design/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/design/base/images/design/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-site_search}</h1>

<h2>Search for: "{search_text}"</h2>

<!-- BEGIN search_type_tpl -->
<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	{intl-search_in_module}: {module_name}
	<br />
	{intl-search_count}: {search_count}
	</th>
</tr>
<!-- BEGIN search_item_tpl -->
<tr>
	<td class="{td_class}" width="1%">
	<img src="{www_dir}{icon_src}" width="16" height="16" alt="" border="0" />
	</td>
	<td class="{td_class}" width="99%">
	<a href="{www_dir}{index}{search_link}">{search_name}</a>
	</td>
</tr>
<!-- END search_item_tpl -->
<tr>
	<td colspan="2">
	{intl-full_search}: <a href="{www_dir}{index}{search_more_link}">{intl-click_here}</a>
	</td>
</tr>
</table>
<!-- END search_type_tpl -->
