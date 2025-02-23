<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead" colspan="{hotdeal_columns}">{intl-hot_deals}</td>
</tr>

<!-- BEGIN hdl_product_list_tpl -->

<!-- BEGIN hdl_product_tpl -->
{begin_tr}
	<td class="menutext">
	<a class="menutext" href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><b>{product_name}</b></a><br />
	<!-- BEGIN hdl_product_image_tpl -->
	<a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/></a>
	<!-- END hdl_product_image_tpl -->
	<div><span class="menutext">{product_intro_text}</span></div>
<!-- BEGIN hdl_price_tpl -->
	<span class="pris">{product_price}</span>
<!-- END hdl_price_tpl -->
<!-- BEGIN add_to_cart_tpl -->
<form action="{www_dir}{index}/{module}/{action_url}/{product_id}/" method="post">
<div calss="spacer"><div class="pris">
<input type="image" src="/design/trade/images/addtocart.gif" name="Cart" value="{intl-add_to_cart}" />
</form>
<!-- END add_to_cart_tpl -->
	</td>
{end_tr}

<!-- END hdl_product_tpl -->

<tr>
	<td class="menuspacer" colspan="{hotdeal_columns}">&nbsp;</td>
</tr>


<!-- END hdl_product_list_tpl -->

</table>