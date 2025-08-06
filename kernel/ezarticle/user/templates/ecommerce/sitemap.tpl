<!-- start #breadcrumbs -->
<div id="breadcrumbs">

<!-- BEGIN path_tpl -->
<!-- END path_tpl -->
</div>
<!-- end #breadcrumbs -->

<!-- start #contentWrap -->
<div id="contentWrap">
<h1 class="mainHeading">{intl-site_map}</h1>

<table width="90%" class="list" cellspacing="0" cellpadding="4" border="0">
<tr>
<td>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/design/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="{www_dir}{index}/article/archive/{option_value}">{option_name}</a><br />
	</td>
</tr>
<!-- END category_value_tpl -->

<!-- BEGIN article_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="{www_dir}/design/base/images/icons/document.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="{www_dir}{index}/article/view/{option_value}/1/{category_id}">{option_name}</a><br />
	</td>
</tr>
<!-- END article_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</table>
<br />


</td></tr></table>
</div>
