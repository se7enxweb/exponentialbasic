<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-head_lines}</td>
</tr>

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->

<!-- BEGIN article_item_tpl -->
<tr>
	<td>
<!-- BEGIN current_image_item_tpl -->
<img src="{www_dir}{current_image_url}" alt="{current_image_caption}" width="{current_image_width}" height="{current_image_height}" border="0" />
<!-- END current_image_item_tpl -->

	<a class="menutext" href="{www_dir}{index}/article/articleview/{article_id}/">
	{article_name}
	</a>
	</td>
</tr>
<tr>
	<td class="small" align="right">
	(&nbsp;{article_published}&nbsp;)
	</td>
</tr>
<!-- END article_item_tpl -->
<!-- END article_list_tpl -->
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>




