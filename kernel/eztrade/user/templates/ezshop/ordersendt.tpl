<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{intl-confirming-order}</div>
	</td>
</tr>
</table>

<br />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>


<h2>{intl-customer_info}</h2>

{customer_first_name} {customer_last_name} 

<br />

<!-- BEGIN billing_address_tpl -->
<h3>{intl-billing_address}:</h3>
{billing_street1}<br />
{billing_street2}<br />
{billing_zip} {billing_place}<br />
{billing_country}<br />
<!-- END billing_address_tpl -->

<!-- BEGIN shipping_address_tpl -->
<h3>{intl-shipping_address}:</h3>
{shipping_street1}<br />
{shipping_street2}<br />
{shipping_zip} {shipping_place}<br />
{shipping_country}<br />
<!-- END shipping_address_tpl -->

<br />

<h3>{intl-payment_method}</h3>
{payment_method}

<h2>{intl-goods_list}</h2>

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
	<td colspan="2">&nbsp;</td>
	<td class="boxtext">{intl-shipping_and_handling}:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

