
<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{current_category_name}</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td colspan="2">
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
	</td>
</tr>
<tr>
	<td class="menutext" colspan="2">
	{article_intro}
	</td>
</tr>
<!-- BEGIN read_more_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/article/articleview/{article_id}/">{article_link_text}</a></td>
</tr>
<!-- END read_more_tpl -->
<!-- END article_item_tpl -->
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<!-- END article_list_tpl -->



