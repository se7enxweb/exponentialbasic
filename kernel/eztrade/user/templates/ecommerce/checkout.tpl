<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; <a href="{www_dir}{index}/trade/cart/">{intl-cart}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-confirm_order}</h1>

<form action="{www_dir}{index}/trade/checkout/" method="post" name="todo">

<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<!-- BEGIN full_cart_tpl -->
<table border="0" class="list" width="90%" cellspacing="0" cellpadding="4">

<!-- BEGIN cart_item_list_tpl -->
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
    
    <td class="{td_class}" align="right">
    {product_count}    
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
    
    <td class="{td_class}"> <!-- <input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /> --></td>
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
    <td class="{td_class}"><span class="small">{option_name} {option_value} <nobr>{option_price}<nobr/></span></td>
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
<input type="hidden" name="shippcost" value="{shipping_ex_hide_tax}">
	<!-- END shipping_ex_tax_item_tpl -->

	<!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}</nobr></td>
<input type="hidden" name="shippcost" value="{shipping_inc_hide_tax}">
	<!-- END shipping_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>


<!-- BEGIN tax_total_tpl -->
<tr>
    <td>&nbsp;</td>
    <th colspan="{subtotals_span_size}" class="right">{intl-tax_total} ({tax_percentage}%):</th>
    <td align="right"><nobr>{total_cart_tax}</nobr></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<!-- END tax_total_tpl -->

<!-- BEGIN vouchers_tpl --> 
        <!-- BEGIN voucher_item_tpl -->
        <tr>
	<td>&nbsp;</td>
	<td colspan="{subtotals_span_size}" align="right"><span class="boxtext">{intl-voucher} {number}:</span></td>

	<td align="right">
	<nobr>- {voucher_price_ex_vat}</nobr>
	</td>
	<td align="right">
	<nobr>- {voucher_price_inc_vat}</nobr>
	</td>
	<td>
	<input type="checkbox" name="RemoveVoucherArray[]" value="{number}" />
	</td>
	</tr>
        <!-- END voucher_item_tpl -->
<!-- END vouchers_tpl --> 

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

<tr><td colspan="4" align="left">{shipping_type_error}</td></tr>
<tr><td colspan="7">&nbsp;</td></td>
<tr>
	<th colspan="3" class="right" valign="top">{intl-shipping_method}:</th>
    <td colspan="4" align="right">
	    <select name="ShippingTypeID[]" onChange="document.forms.todo.shippname.value=this.value;document.forms.todo.Recalculate.value='{intl-recalculate}';document.forms.todo.submit();">
	    <!-- BEGIN shipping_type_tpl -->
	    <option value="{shipping_type_id}" {type_selected}>{shipping_type_name}</option>
	    <!-- END shipping_type_tpl -->
	    </select><br/>

	    <input type="hidden" name="shippname" value="{ship_get_name}">
	    <input class="stdbutton" type="submit" name="Recalculate" value="{intl-recalculate}" />
    </td>
</tr>
</table>

<table class="list" width="90%" cellspacing="0" cellpadding="4" border="0">
<tr>
   <td>
        <!-- BEGIN shipping_disclaimer_tpl -->
          <div>{shipping_disclaimer_text}</div>
        <!-- END shipping_disclaimer_tpl -->
	<p>To change quantities or remove items from this order, please return to the <a href="{www_dir}{index}/trade/cart/" title="Shopping cart">Shopping Cart</a> page.</p>
   </td>
</tr>
</table>

<!-- BEGIN tax_specification_tpl -->
<br />
<br />

<table class="list" width="90%" cellspacing="0" cellpadding="4" border="0">
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

<hr noshade="noshade" size="4" />

<script language=javascript>
function shipaddchange(formthis)
{
alert ("Reloading cart totals and shipping options.");
formthis.Recalculate.value="Recalculate";
formthis.submit();
}
</script>

<!-- BEGIN billing_address_tpl -->
<p class="boxtext">{intl-billing_to}:</p>
<select name="BillingAddressID">
<!-- BEGIN billing_option_tpl -->
<option value="{address_id}" {billing_selected}>{customer_first_name} {customer_last_name}, {street1}, {street2}{place}{region} {zip}{country}</option>
<!-- END billing_option_tpl -->
</select>
<!-- END billing_address_tpl -->

<p class="boxtext">{intl-shipping_to}:</p>

<!-- BEGIN shipping_select_tpl -->
<select name="ShippingAddressID" {shipchange}>
<!-- END shipping_select_tpl -->

<!-- BEGIN shipping_address_tpl -->
<option value="{address_id}" {type_selected}>{customer_first_name} {customer_last_name}, {street1}, {street2}{place}{region} {zip}{country}</option>
<!-- END shipping_address_tpl -->
<!-- BEGIN wish_user_tpl -->
<option value="{wish_user_address_id}">{wish_first_name} {wish_last_name}</option>
<!-- END wish_user_tpl -->
</select>
<input type="hidden" name="lastshipid" value={lastshipid}>
<br>[<a href="{www_dir}{index}/user/userwithaddress/edit/{user_id}/" class="small">{intl-add_address}</a>]

<br /><br />
<p class="boxtext">{intl-comment}:</p>
<textarea class="box" name="Comment" cols="40" rows="5"></textarea>

<!-- BEGIN show_payment_tpl -->
<br /><br />

<span class="p">{intl-payment_methods_description}:</span>

<select name="PaymentMethod">
<!-- BEGIN payment_method_tpl -->
<option value="{payment_method_id}">{payment_method_text}</option>
<!-- END payment_method_tpl -->
</select>
<!-- END show_payment_tpl -->
<input type="hidden" name="PayWithVoucher" value="{pay_with_voucher}" />


<br /><br />


<!-- BEGIN remove_voucher_tpl -->
<input class="stdbutton" type="submit" name="RemoveVoucher" value="{intl-remove_voucher}" />
<!-- END remove_voucher_tpl -->

<hr noshade="noshade" size="4" />


<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />
<input type="hidden" name="TotalVAT" value="{total_vat_value}" />

<!-- BEGIN order_disclaimer_tpl -->
<div style="padding-bottom: 8px;">{order_disclaimer_text}</div>
<!-- END order_disclaimer_tpl -->

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>
</div>


