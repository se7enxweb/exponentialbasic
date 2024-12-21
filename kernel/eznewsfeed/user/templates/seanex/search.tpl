<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="{www_dir}/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Nyheter</span> | {intl-search_result}</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="{www_dir}/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="{www_dir}/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="{www_dir}/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="{www_dir}/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="{www_dir}/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<br />

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

<div align="right">
<form action="{www_dir}{index}/newsfeed/search/" method="post">
<input type="text" name="SearchText" size="20" /><br />
<input type="image" value="{intl-search}" src="{www_dir}/images/button-searchmain.gif" border="0" /><br />
</form>	
</div>