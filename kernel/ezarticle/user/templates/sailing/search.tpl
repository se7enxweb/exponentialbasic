
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
   <td colspan="3">
	  <center><span class="h3">The World of Sailing World</span></center><br>
   </td>
</tr>
  <tr>
    <td bgcolor="#006699" width="9"><img src="{www_dir}/sitedesign/sailing/images/leftrounded.gif" width="9" height="20" hspace="0" vspace="0" border="0" align="left" alt=""></td>
    <td bgcolor="#006699" width="100%"><b class="white">Search</b></td>
    <td width="70"><img src="{www_dir}/sitedesign/sailing/images/rightrounded.gif" width="70" height="20" hspace="0" vspace="0" border="0" align="right" alt=""></td>
  </tr>
</table>
The results of your search follow in no particular order:

<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<span class="h3">Search Terms: "{search_text}"</span>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

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



