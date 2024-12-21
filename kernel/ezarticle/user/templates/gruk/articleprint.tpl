<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<p>Denne artikkelen er funnet på: {article_url}</p><br />
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{article_name}</h1>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<br />
<!-- BEGIN article_header_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="byline"><a class="byline" href="{www_dir}{index}/article/author/view/{author_id}">{author_text}</a></p>
	</td>
	<td align="right">
	<p class="byline">( {article_created} )</p>
	</td>
</tr>
</table>
<!-- END article_header_tpl -->

<p>
{article_intro}
</p>

<p>
{article_body}
</p>


<!-- BEGIN image_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN image_tpl -->
<tr>
	<td width="1%">
	<img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="2" />
	{image_caption}
	</td>
</tr>
<!-- END image_tpl -->

</table>
<!-- END image_list_tpl -->

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
<h2>{intl-attached_files}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN attached_file_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">{file_name}</a>
     </td>
     <td width="50%" class="{td_class}" align="right">
     <div class="p"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">( {original_file_name} {file_size}&nbsp;{file_unit} )</a></div>
     </td>
</tr>
<tr>
     <td colspan="2" class="{td_class}" valign="top">
	{file_description}
     </td>
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
