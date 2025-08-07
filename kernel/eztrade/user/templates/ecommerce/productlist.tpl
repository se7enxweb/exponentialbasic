<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; <a href="{www_dir}{index}/{module}/{module_list}/0/">{intl-top}</a>
<!-- BEGIN path_tpl -->
 &raquo; <a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-productlist}</h1>

<!-- BEGIN category_list_tpl -->

<table width="90%" class="list" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}" width="30%">
	<a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}" width="70%">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_tpl -->

</table>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="95%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
<td class="productlist" align="center">
<!-- BEGIN product_image_tpl -->
	<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{thumbnail_image_caption}"/>
	</a>
<!-- END product_image_tpl -->
</td>
<td class="productlist"><div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>
<div class="p">{product_intro_text}</div>
</td>
<td class="productlist" align="left" width="100px">
<!-- BEGIN price_tpl -->
<span class="pris">{product_price}</span><br />
<!-- END price_tpl -->
<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/" class="smallbold">More Info &raquo;</a></td>
<td>
<!-- BEGIN option_tpl -->
<div class="desc" style="width: 50%;"><small>{option_name}: {option_description}</small></div>

<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
<!-- BEGIN value_price_header_tpl -->
<!-- BEGIN value_description_header_tpl -->
<!-- END value_description_header_tpl -->
<!-- BEGIN value_price_header_item_tpl -->
<!-- END value_price_header_item_tpl -->
<!-- BEGIN value_currency_header_item_tpl -->
<!-- END value_currency_header_item_tpl -->
<!-- END value_price_header_tpl -->

		<select name="OptionValueArray[]">
<!-- BEGIN value_tpl -->
<!-- BEGIN value_description_tpl -->
<option value="{value_id}">{value_name}
<!-- END value_description_tpl -->
<!-- BEGIN value_price_item_tpl -->
{value_price}
<!-- END value_price_item_tpl -->
<!-- BEGIN value_availability_item_tpl -->
({value_availability})
<!-- END value_availability_item_tpl -->
</option>
<!-- BEGIN value_price_currency_list_tpl -->
<!-- BEGIN value_price_currency_item_tpl -->
<!-- END value_price_currency_item_tpl -->
<!-- END value_price_currency_list_tpl -->
<!-- END value_tpl -->
		</select><br />
<!-- END option_tpl -->
<!-- BEGIN add_to_cart_tpl -->
<form action="{www_dir}{index}/{module}/{action_url}/{product_id}/{category_id}">
<div class="productListBtns">
<div class="btnAddToCart" style="padding-top: 1.75rem;"><input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" /></div>
<div class="btnAddToWishlist"><input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" /></div>
</div>
</form>
<!-- END add_to_cart_tpl -->
</td>
</tr>
<!-- END product_tpl -->
</table>

<!-- END product_list_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
</div>
