<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-product_options}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<th>{intl-product_availability}:</th>
	<!-- END product_available_header_tpl -->
	<th>{intl-product_qty}:</th>

	<td class="path" align="right">{intl-product_price}:</td>
	<td class="path" align="right">&nbsp;</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN cart_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	<div class="small">{option_name}: {option_value}<!-- BEGIN cart_item_option_availability_tpl -->({option_availability})
<!-- END cart_item_option_availability_tpl --><div>
        <!-- END cart_item_option_tpl -->
	&nbsp;</td>
	<!-- BEGIN product_available_item_tpl -->
	<td class="{td_class}">
	{product_availability}
	</td>
	<!-- END product_available_item_tpl -->
	<td class="{td_class}">
	<input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	<input size="3" type="text" name="CartCountArray[]" value="{cart_item_count}" />
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td class="{td_class}" align="right">
	<a href="{www_dir}{index}/trade/cart/remove/{cart_item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztrade{cart_item_id}-slett','','/images/slettminimrk.gif',1)"><img name="eztrade{cart_item_id}-slett" border="0" src="{www_dir}/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="3">&nbsp;</td>
	<td align="right" colspan="2"><span class="boxtext">{intl-shipping}:</span></td>
	<td align="right">
	{shipping_sum}
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<td align="right" colspan="2"><span class="boxtext">{intl-vat}:</span></td>
	<td align="right">
	{cart_vat_sum}
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<td align="right" colspan="2"><span class="boxtext">{intl-total}:</span></td>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<hr noshade="noshade" size="4" />
<!-- BEGIN cart_checkout_tpl -->

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN cart_checkout_button_tpl -->
	<td>
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
	<!-- END cart_checkout_button_tpl -->

	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" value="{intl-update}" />
	</td>
</td>
</table>
<!-- END cart_checkout_tpl -->


<input type="hidden" name="Action" value="Refresh" />

</form>
