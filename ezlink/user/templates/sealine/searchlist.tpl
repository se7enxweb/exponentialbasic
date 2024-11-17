<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

<!-- BEGIN path_tpl -->

<img src="{www_dir}/ezarticle/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="{www_dir}{index}/link/group/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<hr noshade size="4">


<h2>Search for: "{query_string}"</h2>
<br>

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<br>
<h3>{intl-result} ({intl-maxhit} {hit_count})</h3> 
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN search_item_tpl -->
<tr>

	<td bgcolor="{bg_color}">
	<b><a href="{www_dir}{index}/link/gotolink/addhit/{link_id}/?Url={link_url}">{link_title}</a></b> ( {intl-max} {link_hits} )<br>
        {link_description}<br><br>
        
     </td>

</tr>
<!-- END search_item_tpl -->
</table>


<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/link/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
prev
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/link/search/?Offset={next_offset}&URLQueryString={url_query_string}">
next
</a>
<!-- END next_tpl -->
