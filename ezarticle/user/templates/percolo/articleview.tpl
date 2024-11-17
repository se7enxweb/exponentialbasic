<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<h3>Artikkelen er funnet på http://{article_url}</h3>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->

        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><a href="{www_dir}{index}/tema/bildegalleri"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="Bygg mer enn hus..." width="140" height="100" border="0" /></a><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">{article_name}</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		</table>

        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
		<tr>
		    <td width="1%"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="99%">

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN article_header_tpl -->

<!-- END article_header_tpl -->

<p class="intro">
{article_intro}
</p>

<p>
{article_body}
</p>

<!-- BEGIN attached_file_list_tpl -->
<h2>{intl-attached_files}:</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN attached_file_tpl -->
<tr class="{td_class}">
     <td>
     {file_name}
     </td>
     <td align="right">
     <div class="p"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">{original_file_name} {file_size}&nbsp;{file_unit}</a></div>
     </td>
</tr>
<tr class="{td_class}">
     <td colspan="2">
     {file_description}
     </td>
</tr>
<tr>
     <td>&nbsp;</td>
</tr>
<!-- END attached_file_tpl -->
</table>
<!-- END attached_file_list_tpl -->

<br clear="all" />

<div align="center">
<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| <span class="p"> &lt;&nbsp;{page_number}&nbsp;&gt; </span>
<!-- END current_page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleprint/{article_id}/">{intl-print_page}</a> |
<!-- END print_page_link_tpl -->
</div>

</td>
</tr>
</table>