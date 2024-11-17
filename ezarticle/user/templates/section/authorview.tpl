<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>


<h1>{intl-author_info}</h1>

<p>{intl-article_info}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td><h2>{intl-head_line} <a class="listheadline" style="text-decoration: underline;"href="mailto:{author_mail}">{author_name}</h2></a></td>
	<td width="10%" align="right"><div class="boxtext">({article_start}-{article_end}/{article_count})</div</td>
</tr>
</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/name">{intl-name}</a>:</th>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/category">{intl-category}</a>:</th>
	<th><div align="right"><a href="{www_dir}{index}/article/author/view/{author_id}/published">{intl-published}</a>:</div></th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{article_category}</a>
	</td>
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
