
<!-- BEGIN path_item_tpl -->
<h1 class="small">{category_name}</h1>
<!-- END path_item_tpl -->

<!-- BEGIN header_item_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-venstre.gif" width="8" height="4" border="0" /><br /></td>
    <td class="tdmini" width="98%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/1x1.gif" width="1" height="1" border="0" /><br /></td>
    <td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-hoyre.gif" width="8" height="4" border="0" /><br /></td>
</tr>
</table>
<!-- END header_item_tpl -->

<br />

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<a href="{www_dir}{index}/article/articleview/{article_id}/">
	<h2>{article_name}</h2>
	</a>
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0"
	<tr>
	<td valign="top">
	<!-- BEGIN article_image_tpl -->
	    <table align="left">
	        <tr>
			<td>
			<a href="{www_dir}{index}/article/articleview/{article_id}/">
                        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
                        </td>
                </tr>
                <tr>
                         <td class="pictext">
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->

	     </td>
		<td valign="top">
		<p>
		{article_intro}
		</p>
		</td>
	</tr>
	</table>

	<img src="{www_dir}/images/path-arrow.gif" height="10" width="15" border="0" alt="">
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/">
	{article_link_text}</a>

	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->
