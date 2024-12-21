<h1>{intl-topic_list}</h1>

<hr size="4" noshade="noshade" />
<!-- BEGIN topic_list_tpl -->

<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<!-- BEGIN topic_item_tpl -->
<tr>
	<th>
	{topic_name}
	</td>
	<td>
	{topic_description}
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td colspan="2">
	<a href="{www_dir}{index}/article/view/{article_id}/">{article_name}</a>
</tr>
<!-- END article_item_tpl -->

<!-- END topic_item_tpl -->
</tr>
</table>
<!-- END topic_list_tpl -->

<hr size="4" noshade="noshade" />
