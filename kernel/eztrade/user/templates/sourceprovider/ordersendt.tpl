<h1>{intl-confirming-order}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>


<!-- BEGIN visa_tpl -->
{intl-mastercard_manual_confirmation}:<br />

{intl-print_out_this_page}.<br />

{intl-fill_in_the_info}.<br />

{intl-fax_it_to_us}<br />

{intl-were_exploring_options}.<br />

<pre>

{intl-order_number}: {order_id}

{intl-card_holder_name}:

--------------------------------

{intl-card_holder_address}:

--------------------------------

{intl-card_number}:

---------------------------------

{intl-card_expire}:

---------------------------------

{intl-card_signature}:

---------------------------------

</pre>
<!-- END visa_tpl -->

<!-- BEGIN mastercard_tpl -->

{intl-mastercard_manual_confirmation}:<br />

{intl-print_out_this_page}.<br />

{intl-fill_in_the_info}.<br />

{intl-fax_it_to_us}<br />

{intl-were_exploring_options}.<br />

<pre>
{intl-order_number}: {order_id}

{intl-card_holder_name}:

--------------------------------

{intl-card_holder_address}:

--------------------------------

{intl-card_number}:

---------------------------------

{intl-card_expire}:

---------------------------------

{intl-card_signature}:

---------------------------------

{intl-card_CVC2}:

---------------------------------
</pre>
<!-- END mastercard_tpl -->

<!-- BEGIN cod_tpl -->
<p>{intl-your_order_will_be_shipped}</p>
<p>{intl-cod}</p>

<!-- END cod_tpl -->

<!-- BEGIN invoice_tpl -->
<p>{intl-your_order_will_be_shipped}</p>
<p>{intl-invoicing}</p>
<!-- END invoice_tpl -->



<h2>{intl-customer_info}</h2>

{customer_first_name} {customer_last_name} 

<br />

<!-- BEGIN address_tpl -->
{street1}<br />
{street2}<br />
{zip} {place}<br />
{country}<br />
<!-- END address_tpl -->

<br />

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
	<td colspan="2" class="boxtext">{intl-shipping_and_handling}:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="2" class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

