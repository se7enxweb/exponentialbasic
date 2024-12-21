<form action="{www_dir}{index}/trade/checkout/" method="post">

<h1>{intl-confirm_order}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-options}:</th>
	<th>{intl-qty}:</th>
	<td class="path" align="right">{intl-price}</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN cart_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END cart_item_option_tpl -->
	&nbsp;
	</td>
	<td class="{td_class}">
	{cart_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-shipping_charges}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-total_cost_is}:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<h2>{intl-shipped_to}:</h2>

{customer_first_name} {customer_last_name} 
<br />

<!-- BEGIN address_tpl -->
{street1} <br />
{street2}<br />
{zip} {place}<br />
{country}<br />

<!-- END address_tpl -->


<br /><br />
<hr noshade="noshade" size="4" />
<br />

{intl-payment_methods_description}:

<select name="PaymentMethod">
<!-- BEGIN visa_tpl -->
<option value="1">{intl-charge_my_visa}</option>
<!-- END visa_tpl -->

<!-- BEGIN mastercard_tpl -->
<option value="2">{intl-charge_my_mastercard}</option>
<!-- END mastercard_tpl -->

<!-- BEGIN cod_tpl -->
<option value="3">{intl-charge_me_at_the_postoffice}</option>
<!-- END cod_tpl -->

<!-- BEGIN invoice_tpl -->
<option value="4">{intl-charge_me_by_invoice}</option>
<!-- END invoice_tpl -->
</select>
<br /><br />

<hr noshade="noshade" size="4" />


<input type="hidden" name="SendOrder" value="true" />
<input class="okbutton" type="submit" value="{intl-send}" />
</form>



