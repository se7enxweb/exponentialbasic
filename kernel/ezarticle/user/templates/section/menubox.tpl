<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="{www_dir}/sitedesign/{sitedesign}/images/articles.gif" width="128" height="20"><br />
	</td>
</tr>
<tr>
	<td>
	<div class="leftmenu">
	<a href="{www_dir}{index}/article/frontpage">{intl-latest}</a>
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="leftmenu">
	<a href="{www_dir}{index}/article/articleheaderlist/0/">{intl-archive}</a>
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="leftmenu">
	<a href="{www_dir}{index}/article/author/list">{intl-authors}</a>
	</div>
	</td>
</tr>

<!-- BEGIN submit_article_tpl -->
<tr>
	<td width="100%">
	<div class="leftmenu">
	<a href="{www_dir}{index}/article/articleedit/new/">{intl-submit_article}</a>
	</div>
	</td>
</tr>
<!-- END submit_article_tpl -->

<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
<tr>
	<td>
	<img src="{www_dir}/sitedesign/{sitedesign}/images/categories.gif" width="128" height="20"><br />
	</td>
</tr>

<!-- BEGIN article_category_tpl -->
<tr>

	<td width="100%">
	<div class="leftmenu"><a href="{www_dir}{index}/article/archive/{articlecategory_id}/">{articlecategory_title}</a></div>
	</td>
</tr>
<!-- END article_category_tpl -->

<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>

