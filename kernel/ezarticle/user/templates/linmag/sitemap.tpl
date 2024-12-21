<h1>{intl-site_map}</h1>

<br />

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="{www_dir}{index}/article/archive/{option_value}">{option_name}</a><br />
	</td>
</tr>
<!-- END category_value_tpl -->

<!-- BEGIN article_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="{www_dir}{index}/article/view/{option_value}">{option_name}</a><br />
	</td>
</tr>
<!-- END article_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</table>
<br />