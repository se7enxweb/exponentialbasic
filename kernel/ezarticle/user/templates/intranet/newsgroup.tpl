<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input class="searchbox" type="text" name="SearchText" size="10" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="{www_dir}{index}/article/archive/0/">{intl-top_level}</a>


<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN category_item_tpl -->

<!-- BEGIN start_with_break_tpl -->
<tr><td  valign="top" width="50%">
<!-- END start_with_break_tpl -->
<!-- BEGIN start_without_break_tpl -->
<td valign="top"  width="50%">
<!-- END start_without_break_tpl -->


<a href="{www_dir}{index}/article/archive/{category_id}/"><h2>{category_name}</h2></a>

<!-- BEGIN article_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
<td>
	<!-- BEGIN article_image_tpl -->
	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td rowspan="2" width="50%">
		<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
		</td>
		<td >
		<a href="{www_dir}{index}/article/articleview/{article_id}"><b>{article_name}<b/></a>
		</td>
	</tr>
	<tr>
		<td  width="50%">
		<p>{article_intro}</p>
		( {article_published} )
		</td>

        </tr>
        </table>
        <!-- END article_image_tpl -->

	<!-- BEGIN no_image_tpl -->
	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td  width="50%">
		</td>

		<td  width="50%">
		<a href="{www_dir}{index}/article/articleview/{article_id}"><b>{article_name}<b/></a>
		</td>
       </tr>		
       <tr>		
		<td  width="50%">
		</td>
		<td  width="50%">
		<p>{article_intro}</p>
		( {article_published} )
		</td>

        </tr>
        </table>
        <!-- END no_image_tpl -->
      
</td>
</tr>
</table>
<!-- END article_item_tpl -->

<!-- BEGIN end_without_break_tpl -->
</td>
<!-- END end_without_break_tpl -->
<!-- BEGIN end_with_break_tpl -->
</td></tr>
<!-- END end_with_break_tpl -->




<!-- END category_item_tpl -->
</table>
