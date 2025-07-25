<!-- start #breadcrumbs -->
<div id="breadcrumbs">
  <a href="{www_dir}{index}/article/archive/0/">{intl-top_level}</a>
<!-- BEGIN path_item_tpl -->
»
<a href="{www_dir}{index}/article/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->
</div>
<!-- end #breadcrumbs -->

<!-- start #contentWrap -->
<div id="contentWrap">

<!-- BEGIN article_url_item_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<h3>{intl-found}: http://{article_url}</h3>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1 class="mainHeading">{article_name}</h1>

<!-- BEGIN article_edit_tpl -->
<p class="byline"> As the author, you may <a class="byline" href="{www_dir}{index}/article/articleedit/edit/{edit_article_id}/">edit</a> this article.</p>
<!-- END article_edit_tpl -->

	</td>
	<!-- BEGIN current_category_image_item_tpl -->
	<td>
	<img src="{current_category_image_url}" alt="{current_category_image_caption}" width="{current_category_image_width}" height="{current_category_image_height}" border="0" />
	</td>
	<!-- END current_category_image_item_tpl -->
</tr>
</table>

<!-- BEGIN article_header_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr><td>
<p class="byline">{intl-article_author}: <a class="byline" href="{www_dir}{index}/article/author/view/{author_id}">{author_text}</a></p>
</td></tr><tr><td>
<p class="byline">{intl-article_date}: {article_created}</p>
</td></tr>
<br />
<!-- END article_header_tpl -->

<!-- BEGIN article_topic_tpl -->
<a class="path" href="{www_dir}{index}/article/topiclist/{topic_id}">{topic_name}</a>
<!-- END article_topic_tpl -->

<!-- BEGIN article_intro_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{article_intro}
	</td>
</tr>
</table>
<br />
<!-- END article_intro_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{article_body}
	</td>
</tr>
</table>

<br clear="all" />

<!-- BEGIN image_list_tpl -->
<table class="list" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}" valign="top">
	<a href="{alt_image_url}"><img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="0" alt="{image_caption}" align="left" /></a>
	<span class="p">{image_caption}</span>
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



<table class="list" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN attached_file_list_tpl -->
<tr><th>{intl-attached_files}:</th></tr>

<!-- BEGIN attached_file_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}">{file_name}</a>
     </td>
     <td width="50%" class="{td_class}" align="right">
     <div class="p"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}">( {file_size} {file_unit} )</a></div>
     </td>
</tr>
<tr>
     <td colspan="2" valign="top" class="{td_class}"> 
	{file_description}
     </td>
</tr>
<!-- END attached_file_tpl -->
</table>
<!-- END attached_file_list_tpl -->
<br>
<table cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN section_item_tpl -->
<tr>
	<th>
	{section_name}:
	</th>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td class="{td_class}">
	 <a href="{www_dir}{index}{link_url}">{link_name}</a>
	</td>
</tr>
<!-- END link_item_tpl -->
<tr>
	<td>&nbsp;
	</td>
</tr>
<!-- END section_item_tpl -->
</table>

<br clear="all" />

<div align="center">
<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{prev_page_number}/{category_id}/"><< {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{page_number}/{category_id}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| <span class="p"> < {page_number} > </span>
<!-- END current_page_link_tpl -->

<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{next_page_number}/{category_id}/">{intl-next_page} >></a>
<!-- END next_page_link_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{intl-numbered_page}</a>
<!-- END numbered_page_link_tpl -->


<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleprint/{article_id}/-1/{category_id}/">{intl-print_page}</a>
<!-- END print_page_link_tpl -->

| <a class="path" href="{www_dir}{index}/article/mailtofriend/{article_id}/1/{category_id}/">{intl-send_mailtofriend}</a> |

</div>

</div>
<!-- end #contentWrap -->

