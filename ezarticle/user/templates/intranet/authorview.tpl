<h1>{intl-author_info}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-author_name}:</p>
	<a href="mailto:{author_mail}">{author_name}</a>
	</td>
</tr>
</table>

<p>{intl-article_info}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td><h2>{intl-head_line} {author_name}</h2></td>
	<td width="10%" align="right"><nobr><b>({article_start}-{article_end}/{article_count})</b></nobr></td>
</tr>
</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/name">{intl-name}</a>:</th>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/category">{intl-category}</a>:</th>
<!--	<th><a href="{www_dir}{index}/article/author/view/{author_id}/author">{intl-author}</a>:</th> -->
	<th><div align="right"><a href="{www_dir}{index}/article/author/view/{author_id}/published">{intl-published}</a>:</div></th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}">{article_name}</a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{article_category}</a>
	</td>
<!--
	<td class="{td_class}">
	{author_name}
	</td>
-->
	<td class="{td_class}" align="right">
	<span class="small">{article_published}</span>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_index}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	&lt;&nbsp;{type_item_name}&nbsp;&gt;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	| <a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
