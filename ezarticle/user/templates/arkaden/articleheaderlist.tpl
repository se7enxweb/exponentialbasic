
<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>
	{intl-article}:
	</th>
	<th>
	<div align="right">{intl-publishing_date}:</div>
	</th>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<a href="{www_dir}{index}/article/articleview/{article_id}/">
	{article_name}
	</a>
	</td>
	<td align="right">
	{article_published}
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




