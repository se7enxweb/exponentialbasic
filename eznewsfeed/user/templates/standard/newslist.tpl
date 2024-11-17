<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td align="bottom"><h1>{intl-latest_news}</h1></td>
	<td align="right">
	<form action="{www_dir}{index}/newsfeed/search/" method="post">
	<input type="text" name="SearchText" size="20" />
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<br />
<!-- BEGIN news_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<a href="{www_dir}{index}{first_news_url}" target="_vblank"><h1>{first_news_name}</h1></a>
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	<div class="spacer"><div class="p">{first_news_intro}</div></div>
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />&nbsp;<a class="path" href="{www_dir}{index}{first_news_url}">{intl-read_more}</a>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<div class="listheadline"><a class="listheadline" href="{www_dir}{index}{news_url}" target="_vblank">{news_name}</a></div>
	<span class="small">( {news_origin} - {news_date} )</span>
	<div class="spacer"><div class="p">{news_intro}</div></div>
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />&nbsp;<a class="path" href="{www_dir}{index}{news_url}">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN short_news_item_tpl -->
<tr>
	<td valign="top">
	<a href="{www_dir}{index}{news_url}" target="_vblank"><b>{news_name}</b></a>&nbsp;&nbsp;
	<span class="small">
	( {news_origin} - {news_date} )
	</span>
	</td>
<tr>
<!-- END short_news_item_tpl -->

</table>
<!-- END news_list_tpl -->

<br />

