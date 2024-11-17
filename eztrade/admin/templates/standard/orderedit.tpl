<!-- orderlist.tpl --> 
<!-- $Id: orderedit.tpl 8578 2001-11-22 11:27:02Z br $ -->

<h1>{intl-head_line} ({order_id})</h1>

<hr noshade="noshade" size="4" />


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <td><p class="boxtext">{intl-customer_email}:</p>
	<div class="p"><a href="mailto:{customer_email}">{customer_email}</a> <a href="/trade/customerview/{customer_id}/" >( {intl-view_customer} )</a></div>
	<br /><br /></td>
	<td align="right"><p class="boxtext">{intl-preorder_id}:</p>
        <div class="p">{preorder_id}</div>
	<br /><br /></td>
	<td align="right"><p class="boxtext">{intl-order_id}:</p>
        <div class="p">{order_id}</div>
	<br /><br /></td>
</tr>
<tr>
	<td colspan="2">
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
	<td colspan="2">
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
    <td class="{td_class}"><a href="{www_dir}{index}/trade/productedit/productpreview/{product_id}">{product_name}</a></td>
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

<h2>{intl-order_status}</h2>

<table width="100%">
<tr>
	<td  width="50%" valign="top">
<form action="{www_dir}{index}/trade/orderedit/{order_id}/newstatus/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-choose_status}:</p>
	<select name="StatusID">
	<!-- BEGIN order_status_option_tpl -->	
	<option value="{option_id}">
	{option_name}
	</option>
	<!-- END order_status_option_tpl -->	
	</select>
	<br /><br />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-comments_for_status_chage}:</p>
	<textarea cols="20" rows="5" name="StatusComment" wrap="soft"></textarea>
	</td>
</tr>
<tr>
	<td>
	</td>
</tr>
</table>
	</td>
	<td width="50%" valign="top">
	<table width="100%" cellspacing="0" cellpadding="4" border="0">
	<tr>
		<th colspan="3">{intl-status_history}:</th>
	</tr>
	<!-- BEGIN order_status_history_tpl -->	
	<tr>
		<td class="{td_class}">
		<span class="small">{status_date}</span>&nbsp;
		</td>
		<td class="{td_class}">
		{status_name}&nbsp;&nbsp;
		</td>
		<td class="{td_class}">
		<span class="small">{status_comment}</span>&nbsp;
		</td>
		<td class="{td_class}">
		<span class="small">{admin_login}</span>&nbsp;
		</td>
	</tr>
	<!-- END order_status_history_tpl -->	
	</table>	
	</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-button_change_status}"/>
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-button_cancel}"/>
	</td>
</tr>
</table>

</form>
