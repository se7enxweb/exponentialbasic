<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td valign="top">
	<h1>{intl-search_result}</h1>
	</td>
	<td valign="top" align="right">
	<form action="{www_dir}{index}/newsfeed/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN news_item_tpl -->
<tr>
	<td valign="top">
	<h1>{news_name}</h1>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="{www_dir}/design/admin/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" target="_blank"  href="{www_dir}{index}{news_url}">{intl-read_more}</a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/newsfeed/news/edit/{news_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-red','','{www_dir}/eznewsfeed/design/admin/images/redigerminimrk.gif',1)"><img name="ezaa{news_id}-red" border="0" src="{www_dir}/eznewsfeed/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}?', '/newsfeed/news/delete/{news_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-slett','','{www_dir}/eznewsfeed/design/admin/images/slettminimrk.gif',1)"><img name="ezaa{news_id}-slett" border="0" src="{www_dir}/eznewsfeed/design/admin/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td width="50%">
<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/newsfeed/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
{intl-prev}
</a>
<!-- END previous_tpl -->

     </td>
     <td width="50%" align="right">

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/newsfeed/search/?Offset={next_offset}&URLQueryString={url_query_string}">
{intl-next}
</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>
<hr noshade="noshade" size="4" />
