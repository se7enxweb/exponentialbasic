

<!-- BEGIN empty_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="1" border="0">
<tr>
<th>{intl-cart}</th>
</tr>
<tr>
	<td>{intl-empty_cart}</td>
</tr>
</table>
<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="1" border="0">
<tr>
<th colspan="4">{intl-cart}</th>
</tr>
<!-- BEGIN cart_item_tpl -->
<tr>
	<td colspan="4" class="{td_class}">
	<a class="menulink" href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
	<div class="small" align="right">{product_price}</div>
	</td>

</tr>
<!-- END cart_item_tpl -->
<tr>
	<td class="small" colspan="3">{intl-shipping}:</td>
	<td class="small" align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td class="small" colspan="3">{intl-total}:</td>
	<td class="small" align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<table border="0">
<tr>
	<!-- BEGIN cart_checkout_tpl -->
	<td>
	<input type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
</tr>
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
<tr>
	<td>
	<a class="menu" href="{www_dir}{index}/trade/cart/">{intl-allcart}</a>
	</td>
	<!-- END cart_checkout_tpl -->
</tr>
</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
