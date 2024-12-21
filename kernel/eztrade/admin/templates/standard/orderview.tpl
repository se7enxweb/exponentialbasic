<!-- orderlist.tpl --> 
<!-- $Id: orderview.tpl 7417 2001-09-24 10:20:18Z ce $ -->

<h1>{intl-head_line}</h1>

<form action="{www_dir}{index}/trade/orderlist/">

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-billing_address}:</p>
	<div class="p">
	{customer_first_name} {customer_last_name}<br /> 
	{billing_street1}<br />
	{billing_street2}<br />
	{billing_zip} {billing_place}<br />
	{billing_country}
	</div>
	<br />
	</td>
	<td>
	<p class="boxtext">{intl-shipping_address}:</p>
	<div class="p">
	{shipping_first_name} {shipping_last_name}<br />
	{shipping_street1}<br />
	{shipping_street2}<br />
	{shipping_zip} {shipping_place}<br />
	{shipping_country}
	</div>
	<br />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-payment_method}:</p>
	<div class="p">{intl-payment_by} {payment_method}</div>
	</td>
	<td>
	<p class="boxtext">{intl-shipping_method}:</p>
	<div class="p">{shipping_method}</div>
	</td>
</tr>
</table>

<h2>{intl-productlist}</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>{intl-image}:</th>
	<th>{intl-productname}:</th>
	<th>{intl-productnumber}:</th>
	<th>{intl-option}:</th>
	<th>{intl-count}:</th>
	<td align="right"><b>{intl-price}:</b></td>
</tr>
<!-- BEGIN order_item_tpl -->

<tr>
	<td class="{td_class}">
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}/trade/productview/{product_id}/">{product_name}&nbsp;</a>
	</td>
	<td class="{td_class}">
	{product_number}&nbsp;
	</td>
	<td class="{td_class}">
        <!-- BEGIN order_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END order_item_option_tpl -->
	&nbsp;
	</td>
	<td class="{td_class}">
	{order_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END order_item_tpl -->
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-shipping}:</th>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-vat}:</th>
	<td align="right">{vat_cost}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-total}:</th>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}"/>
	</td>
</tr>
</table>

</form>
