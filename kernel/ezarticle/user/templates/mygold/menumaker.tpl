<!-- BEGIN menu_box_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{current_category_name}</td>
</tr>
<!-- BEGIN menu_article_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/article/static/{article_id}/">{article_link_text}</a></td>
</tr>
<!-- END menu_article_tpl -->

<!-- BEGIN menu_category_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/article/archive/{category_id}/">{category_link_text}</a></td>
</tr>
<!-- END menu_category_tpl -->
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<!-- END menu_box_tpl -->



