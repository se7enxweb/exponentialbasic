
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

<h2>{intl-searchfor} "{query_string}"</h2>
<br>

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<!-- BEGIN search_result_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <th>{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
</tr>
    <!-- BEGIN message_tpl -->
<tr>
    	<td class="{td_class}">
	<a href="{www_dir}{index}/forum/message/{message_id}/">
	{message_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	{postingtime}
	</td>
</tr>
    <!-- END message_tpl -->

</table>
<!-- END search_result_tpl -->

<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/forum/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
{intl-prev}
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/forum/search/?Offset={next_offset}&URLQueryString={url_query_string}">
{intl-next}
</a>
<!-- END next_tpl -->
