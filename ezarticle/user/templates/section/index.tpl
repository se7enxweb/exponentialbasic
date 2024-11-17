<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-head_line}</h1>

<table width="100%" border="0">
<!-- BEGIN index_item_tpl -->
<tr>
	<td width="1%" valign="top">
	<b>{index_name}:</b>
	</td>
	<td>
	<!-- BEGIN article_item_tpl -->
	<!-- BEGIN comma_item_tpl -->
	,
	<!-- END comma_item_tpl -->
	<a href="{www_dir}{index}/article/articleview/{article_id}/{article_page}/{article_category}">{article_name}</a>
	<!-- END article_item_tpl -->
	</td>
</tr>
<!-- END index_item_tpl -->
</table>
