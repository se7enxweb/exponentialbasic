<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-topic_list}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input class="searchbox" type="text" name="SearchText" size="10" />	
	<input class="stdbutton" type="submit" value="Søk" />
	</form>	
	</td>
</tr>
</table>

<hr size="4" noshade="noshade" />

<!-- BEGIN topic_list_tpl -->

<!-- BEGIN topic_item_tpl -->
<h2>{topic_name}</h2>
<span class="p">{topic_description}</span>

<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<a href="{www_dir}{index}/article/view/{article_id}/">{article_name}</a>
	</td>
	<td align="right">
	<a href="{www_dir}{index}/article/archive/{category_id}/">(&nbsp;{category_name}&nbsp;)</a>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>

<!-- END topic_item_tpl -->
<!-- END topic_list_tpl -->

