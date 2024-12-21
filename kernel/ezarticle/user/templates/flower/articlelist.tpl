<!-- BEGIN header_item_tpl -->
<h1>{current_category_name}</h1>
<!-- END header_item_tpl -->

<hr noshade size="6"/>

<p>
{current_category_description}
</p>

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->


<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Kategori:
	</td>

	<td>
	Beskrivelse:
	</td>

</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade size="4"/>
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
	<hr noshade size="3"/>


	<h2>
	{article_name}
	</h2>

	<!-- BEGIN article_image_tpl -->
	    <table align="right"  width="{thumbnail_image_width}">
	        <tr>
			<td>
                        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
                        </td>
                </tr>
                <tr>
                         <td>
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->


	<p>
	{article_intro}
	</p>
	<br />
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




