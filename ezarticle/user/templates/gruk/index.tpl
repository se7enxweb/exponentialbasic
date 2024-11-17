<h1>{intl-head_line}: {current_letter}</h1>

<hr noshade="noshade" size="4" />

<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr>
	<!-- BEGIN letter_item_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/index/{letter}/?SectionIDOverride=2">{letter}</a>
	</td>
	<!-- END letter_item_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />

<table class="list" cellpadding="4" cellspacing="0" width="100%" border="0">
<!-- BEGIN index_item_tpl -->
<tr>
	<td width="1%" valign="top">
	<div class="path">{index_name}:</div>
	</td>
	<td>
	<!-- BEGIN article_item_tpl -->
	<!-- BEGIN comma_item_tpl -->

	<!-- END comma_item_tpl -->
	<div class="liste"><a href="{www_dir}{index}/article/articleview/{article_id}/{article_page}/{article_category}">{article_name}</a></div>
	<!-- END article_item_tpl -->
	</td>
</tr>
<!-- END index_item_tpl -->
</table>
