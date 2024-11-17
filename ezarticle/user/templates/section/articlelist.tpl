<!-- BEGIN header_item_tpl -->

<!-- END header_item_tpl -->
aaaaaaaaaaaaa
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
<h2>Categories</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}" width="1%" valign="top">
	<!-- BEGIN image_item_tpl -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	
	<!-- END no_image_tpl -->
        </td>
	<td width="49%" class="{td_class}" valign="top">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{category_name}</a>
	</td>
	<td width="50%" class="{td_class}" valign="top">
	{category_description}
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->

<br />
<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<div class="listheadline"><a class="listheadline" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
	<div class="small">( {article_published} )</div>

	<!-- BEGIN article_image_tpl -->
	    <table width="1%" align="right">
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
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="8" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END read_more_tpl -->
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

