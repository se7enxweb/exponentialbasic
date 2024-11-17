<!-- BEGIN header_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
<!-- BEGIN latest_headline_tpl -->	
<h1>{intl-head_line} </h1>
<!-- END latest_headline_tpl -->	
<!-- BEGIN category_headline_tpl -->	
<h1>{current_category_name}</h1>
<!-- END category_headline_tpl -->
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input class="searchbox" type="text" name="SearchText" size="10" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<!-- END header_item_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->
<br />
<!--
<div class="spacer">
-->
<!-- BEGIN current_image_item_tpl -->
<!--
<img src="{www_dir}{current_image_url}" alt="{current_image_caption}" width="{current_image_width}" height="{current_image_height}" border="0" />
-->
<!-- END current_image_item_tpl -->
<!--
<div class="p">{current_category_description}</div>
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
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td width="49%" class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{category_name}</a>
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
        <!-- BEGIN headline_with_link_tpl -->
        <div class="listheadline"><a class="listheadline" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
        <!-- END headline_with_link_tpl -->
        <!-- BEGIN headline_without_link_tpl -->
        <div class="listheadline">{article_name} </div>
        <!-- END headline_without_link_tpl -->
<!-- BEGIN article_date_tpl -->
	<div class="small">( {article_published} )</div>
<!-- END article_date_tpl -->

	<!-- BEGIN article_image_tpl -->
	    <table width="1%" align="right" width="{thumbnail_image_width}">
	        <tr>
			<td>
			<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
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
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END read_more_tpl -->

	<!-- BEGIN article_topic_tpl -->

	<!-- END article_topic_tpl -->

	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/archive/{category_current_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/article/archive/{category_current_id}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/article/archive/{category_current_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

