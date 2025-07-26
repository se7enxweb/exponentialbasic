<!-- BEGIN empty_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menusubhead">{intl-cart}:</td>
</tr>
<tr>
	<td class="menutext">{intl-empty_cart}</td>
</tr>
</table>
<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="4" class="menusubhead">{intl-cart}:</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td colspan="4" class="{td_class}">
	<a class="menutext" href="{www_dir}{index}/trade/productview/{product_id}/"><b>{product_name}</b></a>
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
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/trade/cart/">{intl-allcart}</a></td>
	<!-- END cart_checkout_tpl -->
</tr>
<td class="small" colspan="3" align="center">
<input type="image"  src="{www_dir}/design/{sitedesign}/images/banner_intl.gif" width="88" height="33" border="0" name="DoCheckOut" alt="We accept PayPal, the #1 international online payment service!">

</td>
</tr>


<tr>
	<!-- BEGIN cart_checkout_tpl -->
	<td colspan="2">
	<center><input class="stdbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" /></center>
	</td>
</tr>

<tr>
<td class="small" colspan="3" align="center">
<img src="{www_dir}/design/{sitedesign}/images/icon_visa.gif" width="39" height="26" border="0" alt="We accept major credit cards through PayPal, the #1 international online payment service!">
<img src="{www_dir}/design/{sitedesign}/images/icon_mastercard.gif" width="39" height="26" border="0" alt="We accept major credit cards through PayPal, the #1 international online payment service!"><br/>
<img src="{www_dir}/design/{sitedesign}/images/icon_discover.gif" width="39" height="26" border="0" alt="We accept major credit cards through PayPal, the #1 international online payment service!">
<img src="{www_dir}/design/{sitedesign}/images/icon_amex.gif" width="27" height="26" border="0" alt="We accept major credit cards through PayPal, the #1 international online payment service!">
</td>
</tr>

<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>

</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
