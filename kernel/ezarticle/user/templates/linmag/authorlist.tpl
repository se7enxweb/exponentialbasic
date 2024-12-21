<h1>{intl-head_line}</h1>

<p>{intl-author_info}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="{www_dir}{index}/article/author/list/name">{intl-author}</a>:</th>
	<th class="right"><a href="{www_dir}{index}/article/author/list/count">{intl-count}</a>:</th>
</tr>

<!-- BEGIN author_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/author/view/{author_id}/">{author_name}</a>
	</td>
	<td align="right" class="{td_class}">
	{article_count}
	</td>
</tr>
<!-- END author_item_tpl -->
</table>

