

<h1>{intl-search_result}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN news_item_tpl -->
<tr>
	<td valign="top">
	<a href="{www_dir}{index}{news_url}/"><div class="h2">{news_name}</div></a>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{www_dir}{index}{news_url}">{intl-read_more}</a>
	</td>
</tr>
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->

<hr noshade="noshade" size="4" />

<center>
<form action="{www_dir}{index}/newsfeed/search/" method="post">
<input type="text" name="SearchText" size="12" />	
<input type="submit" value="{intl-search}" />
</form>	
</center>