<!-- BEGIN product_gallery_page_tpl -->

<!-- BEGIN path_tpl -->
<div align="center" style="font-size: 25px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">{category_name}</div><br />
<!-- END path_tpl -->


<!-- BEGIN category_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_tpl -->

</table>

<!-- END category_list_tpl -->


<!-- BEGIN product_gallery_tpl -->

<table width="100%" cellspacing="5" cellpadding="5" border="0">
<!-- BEGIN product_tpl -->
<!-- BEGIN product_group_begin_tpl -->
<tr>
<!-- END product_group_begin_tpl -->
	<td valign="top">

<table align="right" border="0">
    <tr>
        <td>
	<div class="listproducts">
		<a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a><br />
	</div>

    </td>
    </tr>
   <!-- BEGIN product_image_tpl -->
    <tr>
        <td align="right">
	<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
        </td>
    </tr>
    <tr>
        <td align="right" class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
   <!-- END product_image_tpl -->

    <tr>
       <td>
	<div class="p">{product_intro_text}</div>
       </td>
    </tr>
    <tr>
	<td align="right">
	<!-- BEGIN price_tpl -->
	<div class="spacer"><div class="pris">{product_price}</div>
	<a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/" title="Product Number">{product_number}</a></div>
	<!-- END price_tpl -->
        <!-- BEGIN product_catalog_number_tpl -->
        <div class="spacer"><div class="pris"><a title="Catalog Number">{catalog_number}</a></div></div>
        <!-- END product_catalog_number_tpl -->
	</td>
    </tr>
    <tr>
	<td>
	<!-- BEGIN add_to_cart_tpl -->
	<form action="{www_dir}{index}/{module}/{action_url}/{product_id}/" method="post">
	<div align="right">
	<div class="spacer"><div class="pris">
	<input type="image" src="/design/aih_standard/images/addtocart.gif" name="Cart" value="{intl-add_to_cart}" />
	</div></div>
	</div>
	</form>
	<!-- END add_to_cart_tpl -->
	</td>
    </tr>
    </table>
	</td>
<!-- BEGIN product_group_end_tpl -->
</tr>
<!-- END product_group_end_tpl -->
<!-- END product_tpl -->
</table>
<!-- END product_gallery_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br /><br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/productgallery/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productgallery/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productgallery/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>

</table>
<!-- BEGIN product_gallery_page_tpl -->
