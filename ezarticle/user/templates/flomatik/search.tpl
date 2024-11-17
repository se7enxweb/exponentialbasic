<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-head_line}: "{search_text}"</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<p>
{current_category_description}
</p>

<hr noshade="noshade" size="4" />

<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Article:</th>
	<th>
	<div align="right">
	{intl-publishing_date}:
	</div>
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



