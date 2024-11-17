<!-- BEGIN article_url_item_tpl --> 
<!-- END article_url_item_tpl -->
<p><b>{article_name}</b><br />
<!-- BEGIN current_category_image_item_tpl --> 
<!-- END current_category_image_item_tpl -->

<!-- BEGIN path_item_tpl --> 
<!-- END path_item_tpl -->

<!-- BEGIN article_header_tpl --> 
<!-- END article_header_tpl -->

<!-- BEGIN article_topic_tpl --> 
<!-- END article_topic_tpl -->

<!-- BEGIN article_intro_tpl --> 
<!-- END article_intro_tpl -->

{article_body}

<!-- BEGIN image_list_tpl -->
<!-- BEGIN image_tpl -->
<!-- END image_tpl -->
<!-- END image_list_tpl -->

<!-- BEGIN attribute_list_tpl -->
<!-- BEGIN type_item_tpl -->
<!-- BEGIN attribute_item_tpl -->
<!-- END attribute_item_tpl -->
<!-- END type_item_tpl -->
<!-- END attribute_list_tpl -->

<!-- BEGIN attached_file_list_tpl -->
<!-- BEGIN attached_file_tpl -->
<!-- END attached_file_tpl -->
<!-- END attached_file_list_tpl -->

<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{prev_page_number}/{category_id}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{page_number}/{category_id}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| <span class="p"> &lt;&nbsp;{page_number}&nbsp;&gt; </span>
<!-- END current_page_link_tpl -->

<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/{next_page_number}/{category_id}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/article/articleview/{article_id}/0/">{intl-numbered_page}</a>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl --> 
<!-- END print_page_link_tpl -->
</p>