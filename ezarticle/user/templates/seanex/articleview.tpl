<!-- BEGIN header_item_tpl -->
<!-- END header_item_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="{www_dir}/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">&nbsp;</span></div></td>
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

<h1>{article_name}</h1>

<!-- BEGIN article_header_tpl -->

<!-- END article_header_tpl -->

<p>
{article_body}
</p>

<br />

<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| < {page_number} >
<!-- END current_page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleprint/{article_id}/">{intl-print_page}</a> |
<!-- END print_page_link_tpl -->
