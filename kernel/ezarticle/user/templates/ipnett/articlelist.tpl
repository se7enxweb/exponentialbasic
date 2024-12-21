<!-- BEGIN header_item_tpl -->
<!-- BEGIN latest_headline_tpl -->	
<h1>{intl-head_line}</h1>
<!-- END latest_headline_tpl -->	
<!-- BEGIN category_headline_tpl -->	
<h1>{current_category_name}</h1>
<!-- END category_headline_tpl -->

<!-- END header_item_tpl -->

<!--
<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/article/archive/0/">{intl-top_level}</a>
-->
<!-- BEGIN path_item_tpl -->
<!--
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/article/archive/{category_id}/">{category_name}</a>
-->
<!-- END path_item_tpl -->

<!--
<div class="spacer">
-->
<!-- BEGIN current_image_item_tpl -->
<!--
<img src="{current_image_url}" alt="{current_image_caption}" width="{current_image_width}" height="{current_image_height}" border="0" />
-->
<!-- END current_image_item_tpl -->
<div class="spacer"><div class="p">{current_category_description}</div></div>
<!--
</div>
-->
<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>&nbsp;</th>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}" width="1%">
	<!-- BEGIN image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td width="49%" class="{td_class}">
	<a href="/article/archive/{category_id}/">{category_name}</a>
	</td>
	<td width="50%" class="{td_class}">
	<span class="small">{category_description}</small>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<br />
<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
	<div class="small">( {article_published} )</div>

	<!-- BEGIN article_image_tpl -->
	    <table width="1%" align="right" width="{thumbnail_image_width}">
	        <tr>
			<td>
			<a href="/article/articleview/{article_id}/1/{category_id}/"><img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
            </td>
                </tr>
                <tr>
                         <td class="pictext">
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->


	<div class="spacer"><div class="p">{article_intro}</div></div>

        <!-- BEGIN read_more_tpl -->
	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END read_more_tpl -->
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/article/archive/{category_current_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="/article/archive/{category_current_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="/article/archive/{category_current_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

