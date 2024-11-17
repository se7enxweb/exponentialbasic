<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<h3>{intl-found}: http://{article_url}</h3>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">
	<!-- BEGIN path_item_tpl -->	
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/article/archive/{category_id}/">{category_name}</a> 
	<!-- END path_item_tpl -->
	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{article_name}</h1>

<!-- BEGIN article_header_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="byline">{intl-article_author}: <a class="byline" href="{www_dir}{index}/article/author/view/{author_id}">{author_text}</a></p>
	</td>
	<td align="right">
	<p class="byline">{intl-article_date}: {article_created}</p>
	</td>
</tr>
</table>
<!-- END article_header_tpl -->

<!-- BEGIN article_intro_tpl -->
<p>
{article_intro}
</p>
<!-- END article_intro_tpl -->

<p>
{article_body}
</p>

<!-- BEGIN attribute_list_tpl -->
<!-- BEGIN type_item_tpl -->
<h2>{type_name}</h2>
<!-- BEGIN attribute_item_tpl -->
<p class="boxtext">{attribute_name}:</p>
<span class="p">{attribute_value}</span><br />
<!-- END attribute_item_tpl -->
<!-- END type_item_tpl -->
<!-- END attribute_list_tpl -->

<!-- BEGIN attached_file_list_tpl -->
<p class="boxtext">{intl-attached_files}:</p>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN attached_file_tpl -->
<tr class="{td_class}">
     <td width="20%">
     {file_name}
     </td>
     <td width="80%">
     <div class="p"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">( {original_file_name} {file_size}&nbsp;{file_unit} )</a></div>
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

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleprint/{article_id}/">{intl-print_page}</a> |
<!-- END print_page_link_tpl -->
</div>

