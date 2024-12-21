<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<center><h3>{intl-found}: http://{article_url}</h3></center>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
   <td colspan="3">
	  <center><span class="h3">The World of Sailing World</span></center><br>
   </td>
</tr>
  <tr>
    <td bgcolor="#006699" width="9"><img src="{www_dir}/sitedesign/sailing/images/leftrounded.gif" width="9" height="20" hspace="0" vspace="0" border="0" align="left" alt=""></td>
    <td bgcolor="#006699" width="100%"><b class="white">{category_definition_name}</b></td>
    <td width="70"><img src="{www_dir}/sitedesign/sailing/images/rightrounded.gif" width="70" height="20" hspace="0" vspace="0" border="0" align="right" alt=""></td>
  </tr>

</table>

<br />
<!-- BEGIN print_page_link_tpl -->
&nbsp;&nbsp; <img src="{www_dir}/sitedesign/sailing/images/print.gif" alt="">&nbsp; <a class="section" href="{www_dir}{index}/article/articleprint/{article_id}/">{intl-print_page}</a>
<!-- END print_page_link_tpl -->


<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<span class="h3">{article_name}</span>
	</td>
</tr>
</table>

<!-- BEGIN article_header_tpl -->
<b>By</b>: {author_text} <br />
{article_created}
<!-- END article_header_tpl -->

<p>
{article_body}
</p>


<!-- BEGIN attached_file_list_tpl -->
<h3>{intl-attached_files}:</h3>
<!-- BEGIN attached_file_tpl -->
<a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">{file_name}</a><br />
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->

<br />
<div align="center">
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

</div>