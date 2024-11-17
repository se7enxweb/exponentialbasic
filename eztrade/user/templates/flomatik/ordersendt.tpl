<h1>{intl-confirming-order}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<!-- BEGIN billing_address_tpl -->
	<p class="boxtext">{intl-billing_address}:</p>
	{customer_first_name} {customer_last_name} <br />
	{billing_street1}<br />
	{billing_street2}<br />
	{billing_zip} {billing_place}<br />
	{billing_country}<br />
	<!-- END billing_address_tpl -->
	<br />
	</td>
	<td>
	<!-- BEGIN shipping_address_tpl -->
	<p class="boxtext">{intl-shipping_address}:</p>
	{shipping_first_name} {shipping_last_name} <br />
	{shipping_street1}<br />
	{shipping_street2}<br />
	{shipping_zip} {shipping_place}<br />
	{shipping_country}<br />
	<!-- END shipping_address_tpl -->
	<br />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-payment_method}:</p>
	<div class="p">{payment_method}</div>
	</td>
	<td>
	<p class="boxtext">{intl-shipping_type}:</p>
	<div class="p">{shipping_type}</div>
	</td>
</tr>
</table>
<br />

<h2>{intl-goods_list}:</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-options}:</th>
	<th>{intl-qty}:</th>
	<td class="path" align="right">{intl-price}:</td>
</tr>
<!-- BEGIN order_item_tpl -->

<tr>
	<td class="{td_class}">
	<!-- BEGIN order_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END order_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}&nbsp;
	</td>
	<td class="{td_class}">
        <!-- BEGIN order_item_option_tpl -->
	<span class="small">{option_name}: {option_value}</span><br />
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
	<td>&nbsp;</td>
	<td align="right" colspan="3" class="boxtext">{intl-shipping_and_handling}:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right" colspan="3" class="boxtext">{intl-vat}:</td>
	<td align="right">{order_vat_sum}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right" colspan="3" class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

