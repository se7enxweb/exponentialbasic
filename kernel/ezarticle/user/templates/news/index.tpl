<h1>{intl-head_line} - {current_letter}</h1>

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" width="100%" border="0">
<!-- BEGIN index_item_tpl -->
<tr>
	<td width="20%" valign="top">
	<div class="spacer"><div class="path">{index_name}:</div></div>
	</td>
	<td width="80%">
	<!-- BEGIN article_item_tpl -->
	<!-- BEGIN comma_item_tpl -->
	,
	<!-- END comma_item_tpl -->
	&nbsp;<a href="{www_dir}{index}/article/articleview/{article_id}/{article_page}/{article_category}">{article_name}</a>
	<!-- END article_item_tpl -->
	</td>
</tr>
<!-- END index_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<!-- BEGIN letter_item_tpl -->
	<td width="3%">
	<a href="{www_dir}{index}/article/index/{letter}/">{letter}</a>
	</td>
	<!-- END letter_item_tpl -->
</tr>
</table>
