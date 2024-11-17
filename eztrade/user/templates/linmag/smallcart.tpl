<!-- BEGIN empty_cart_tpl -->

<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="5" cellpadding="1" border="0">
<tr>
	<td colspan="4" class="menusubhead">{intl-cart}:</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td colspan="2">
	<a class="small" href="{www_dir}{index}/trade/productview/{product_id}/"><b>{product_name}</b></a>
	</td>
	<td colspan="2" align="right">
	<div class="small" align="right">{product_price}</div>
	</td>
</tr>
<!-- END cart_item_tpl -->
<tr>
	<td class="small" colspan="3">{intl-shipping}:</td>
	<td class="small" align="right">
	{shipping_sum}
	</td>
</tr>
<tr>
	<td class="small" colspan="3">{intl-vat}:</td>
	<td class="small" align="right">
	{cart_vat_sum}
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
	<td colspan="2">
	<input class="stdbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
<tr>
	<td colspan="2" valign="top">&nbsp;&#149&nbsp;<a class="menu" href="{www_dir}{index}/trade/cart/">{intl-allcart}</a></td>
	<!-- END cart_checkout_tpl -->
</tr>
</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
