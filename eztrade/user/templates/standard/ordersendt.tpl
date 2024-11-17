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

<p class="boxtext">{intl-comment}:</p>
{comment}


<br />

<h2>{intl-goods_list}:</h2>

<!-- BEGIN full_cart_tpl --><table class="list" width="100%" cellspacing="0" cellpadding="4" border="0"><!-- BEGIN cart_item_list_tpl -->
<tr>
    <th>&nbsp;</th>

	<th>{intl-product_number}:</th>
	<th>{intl-product_name}:</th>
	<th class="right">{intl-product_price}:</th>

	<!-- BEGIN header_savings_item_tpl -->
	<th class="right">{intl-product_savings}:</th>
	<!-- END header_savings_item_tpl -->

	<th class="right">{intl-product_qty}:</th>

	<!-- BEGIN header_ex_tax_item_tpl -->
	<th class="right">{intl-product_total_ex_tax}:</th>
	<!-- END header_ex_tax_item_tpl -->

	<!-- BEGIN header_inc_tax_item_tpl -->
	<th class="right">{intl-product_total_inc_tax}:</th>
	<!-- END header_inc_tax_item_tpl -->

	<th class="right">&nbsp;</th>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">{product_number}</td>
    <td class="{td_class}"><a href="{www_dir}{index}/trade/productview/{product_id}">{product_name}</a></td>
    <td class="{td_class}" align="right"><nobr>{product_price}</nobr></td>
    
	<!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END cart_savings_item_tpl -->
    
    <td class="{td_class}" align="right">{product_count}
	    <!--
        <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	    <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
        -->
    </td>
    
	<!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_ex_tax}</nobr></td>
	<!-- END cart_ex_tax_item_tpl -->

	<!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_inc_tax}</nobr></td>
	<!-- END cart_inc_tax_item_tpl -->
    
    <td class="{td_class}"><!-- <input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /> --></td>
</tr>

<!-- BEGIN cart_item_basis_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><span class="small">{intl-basis_price} <nobr>{basis_price}<nobr/></span></td>
    <td class="{td_class}" align="right">&nbsp;</td>
    
	<!-- BEGIN basis_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_savings_item_tpl -->
    
    <td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN basis_inc_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_inc_tax_item_tpl -->
    
	<!-- BEGIN basis_ex_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_ex_tax_item_tpl -->

    <td class="{td_class}">&nbsp;</td>
</tr>
<!-- END cart_item_basis_tpl -->

<!-- BEGIN cart_item_option_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><span class="small">{option_id} {option_name} {option_value} <nobr>{option_price}<nobr/></span></td>
    <td class="{td_class}" align="right">&nbsp;</td>
    
	<!-- BEGIN option_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_savings_item_tpl -->
    
    <td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN option_inc_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_inc_tax_item_tpl -->
    
	<!-- BEGIN option_ex_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_ex_tax_item_tpl -->

    <td class="{td_class}">&nbsp;</td>
</tr>
<!-- END cart_item_option_tpl -->

<!-- END cart_item_tpl -->

<!-- END cart_item_list_tpl -->

<tr>
    <td>&nbsp;</td>
    
    <th colspan="{subtotals_span_size}" class="right">{intl-subtotal}:</th>

	<!-- BEGIN subtotal_ex_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_ex_tax}</nobr></td>
	<!-- END subtotal_ex_tax_item_tpl -->

	<!-- BEGIN subtotal_inc_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_inc_tax}</nobr></td>
	<!-- END subtotal_inc_tax_item_tpl -->
    
    <td>&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <th colspan="{subtotals_span_size}" class="right">{intl-shipping}:</th>

	<!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right"><nobr>{shipping_ex_tax}</nobr></td>
	<!-- END shipping_ex_tax_item_tpl -->

	<!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}</nobr></td>
	<!-- END shipping_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <th colspan="{subtotals_span_size}" class="right">{intl-total}:</th>

	<!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}</nobr></td>
	<!-- END total_ex_tax_item_tpl -->

	<!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}</nobr></td>
	<!-- END total_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

</table>

<!-- BEGIN voucher_item_list_tpl -->

<h2>{intl-voucher_list}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-voucher_number}:</th>

	<!-- BEGIN voucher_used_header_ex_tax_item_tpl -->
	<th class="right">{intl-voucher_used_ex_tax}:</th>
	<!-- END voucher_used_header_ex_tax_item_tpl -->

	<!-- BEGIN voucher_used_header_inc_tax_item_tpl -->
	<th class="right">{intl-voucher_used_inc_tax}:</th>
	<!-- END voucher_used_header_inc_tax_item_tpl -->

	<!-- BEGIN voucher_left_header_ex_tax_item_tpl -->
	<th class="right">{intl-voucher_left_ex_tax}:</th>
	<!-- END voucher_left_header_ex_tax_item_tpl -->

	<!-- BEGIN voucher_left_header_inc_tax_item_tpl -->
	<th class="right">{intl-voucher_left_inc_tax}:</th>
	<!-- END voucher_left_header_inc_tax_item_tpl -->

</tr>
<!-- BEGIN voucher_item_tpl -->
<tr>

    <td class="{td_class}">{voucher_number}</td>

	<!-- BEGIN voucher_used_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_used_ex_tax}</nobr></td>
	<!-- END voucher_used_ex_tax_item_tpl -->

	<!-- BEGIN voucher_used_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_used_inc_tax}</nobr></td>
	<!-- END voucher_used_inc_tax_item_tpl -->

	<!-- BEGIN voucher_left_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_left_ex_tax}</nobr></td>
	<!-- END voucher_left_ex_tax_item_tpl -->

	<!-- BEGIN voucher_left_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_left_inc_tax}</nobr></td>
	<!-- END voucher_left_inc_tax_item_tpl -->

</tr>
<!-- END voucher_item_tpl -->

</table>
<!-- END voucher_item_list_tpl -->

<!-- BEGIN tax_specification_tpl -->
<br />
<br />
<br />
<br />

<h2>{intl-tax_list}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<th class="right">{intl-tax_basis}:</th>
<th class="right">{intl-tax_percentage}:</th>
<th class="right">{intl-tax}:</th>
</tr>

<!-- BEGIN tax_item_tpl -->

<tr>
    <td class="{td_class}" align="right">{sub_tax_basis}</td>
    <td class="{td_class}" align="right">{sub_tax_percentage} %</td>
    <td class="{td_class}" align="right">{sub_tax}</td>
</tr>
<!-- END tax_item_tpl -->

<tr>
    <th colspan="2" class="right">{intl-total}:</th>
    <td align="right">{tax}</td>
</tr>

</table>
<!-- END tax_specification_tpl -->
<!-- END full_cart_tpl -->
