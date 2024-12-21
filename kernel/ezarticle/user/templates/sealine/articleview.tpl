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
	<td>
	<h1 class="small">{article_name}</h1>
	</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-venstre.gif" width="8" height="4" border="0" /><br /></td>
    <td class="tdmini" width="98%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/1x1.gif" width="1" height="1" border="0" /><br /></td>
    <td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-hoyre.gif" width="8" height="4" border="0" /><br /></td>
</tr>
</table>

<!-- BEGIN article_header_tpl -->

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

<p>
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
</p>

<!-- BEGIN numbered_page_link_tpl -->
<center> | <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/0/">Tilbake til normal sidevisning</a> | </center>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<center> <a class="path" href="{www_dir}{index}/article/articleprint/{article_id}/">| {intl-print_page} |</a> </center>
<!-- END print_page_link_tpl -->
</p>