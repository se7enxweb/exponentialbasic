<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<form action="{www_dir}{index}/trade/cart/" method="post">

<!-- BEGIN full_cart_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN cart_item_list_tpl -->
<tr>
    <th>&nbsp;</th>

	<th>{intl-product_number}:</th>
	<th>{intl-product_name}:</th>
	<th class="right">{intl-product_price}:</th>

	<th class="right">{intl-product_qty}:</th>

	<!-- BEGIN header_savings_item_tpl -->
	<th class="right">{intl-product_savings}:</th>
	<!-- END header_savings_item_tpl -->

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
    
    <td class="{td_class}" align="right">
	    <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	    <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
    </td>
    
	<!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_savings}</nobr></td>
	<!-- END cart_savings_item_tpl -->
    
	<!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_ex_tax}</nobr></td>
	<!-- END cart_ex_tax_item_tpl -->

	<!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_inc_tax}</nobr></td>
	<!-- END cart_inc_tax_item_tpl -->
    
    <td class="{td_class}"><input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /></td>
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

<!-- BEGIN tax_specification_tpl -->
<br />
<br />
<br />
<br />

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

<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
	<td>&nbsp;</td>
	<td align="right">
	<input class="stdbutton" type="submit" value="{intl-update}" />
	</td>
</td>
</table>
<!-- END full_cart_tpl -->


<!-- BEGIN cart_checkout_tpl -->
<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="ShopMore" value="{intl-shopmore}" />
	</td>
	<td align="right">
	<!-- BEGIN cart_checkout_button_tpl -->
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	<!-- END cart_checkout_button_tpl -->
	</td>
</tr>
</table>
<!-- END cart_checkout_tpl -->


<input type="hidden" name="Action" value="Refresh" />

</form>
