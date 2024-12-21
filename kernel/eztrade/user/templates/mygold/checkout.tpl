<form action="{www_dir}{index}/trade/checkout/" method="post">

<h1>{intl-confirm_order}</h1>
<hr noshade="noshade" size="1" />
<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<!-- BEGIN full_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="1" border="0">
  <!-- BEGIN cart_item_list_tpl -->
  <tr align="left">
    <th class="right" width="1%"><nobr>{intl-product_qty}:</nobr></th>      
    <th width="1%"><nobr>&nbsp;{intl-product_number}:</nobr></th>
    <th><nobr>&nbsp;{intl-product_name}:</nobr></th>
    <!-- BEGIN header_savings_item_tpl -->
    <th width="1%"><nobr>{intl-product_savings}:</nobr></th>
    <!-- END header_savings_item_tpl -->
    <!-- BEGIN header_ex_tax_item_tpl -->
    <th width="1%" align="right"><nobr>{intl-product_total_ex_tax}:&nbsp;</nobr></th>
    <!-- END header_ex_tax_item_tpl -->
    <!-- BEGIN header_inc_tax_item_tpl -->
    <th width="1%" align="right"><nobr>{intl-product_total_inc_tax}:&nbsp;</nobr></th>
    <!-- END header_inc_tax_item_tpl -->
    <th width="1%">&nbsp;</td>        
  </tr>
  
  <!-- BEGIN cart_item_tpl -->
  <tr valign="top">
    <td class="{td_class}" align="center">
      {product_count}    
      <!--
      <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
      <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
      -->
    </td>    
    <td class="{td_class}"><nobr>&nbsp;{product_number}</nobr></td>
    <td class="{td_class}">&nbsp;
      <a href="{www_dir}{index}/trade/{url_action}">{product_name}</a>
      <!-- BEGIN cart_item_option_tpl -->
      <br />&nbsp;<span class="small">&nbsp;Gr&ouml;&szlig;e: {option_value}</span>
      <!-- BEGIN option_savings_item_tpl -->
      &nbsp;
      <!-- END option_savings_item_tpl -->
      <!-- BEGIN option_inc_tax_item_tpl -->
      &nbsp;
      <!-- END option_inc_tax_item_tpl -->
      <!-- BEGIN option_ex_tax_item_tpl -->
      &nbsp;
      <!-- END option_ex_tax_item_tpl -->
      <!-- END cart_item_option_tpl -->
    </td>
    <!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
    <!-- END cart_savings_item_tpl -->
    <!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_ex_tax}&nbsp;</nobr></td>
    <!-- END cart_ex_tax_item_tpl -->
    <!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_inc_tax}&nbsp;</nobr></td>
    <td class="{td_class}">&nbsp;</td>    
    <!-- END cart_inc_tax_item_tpl -->
  </tr>
  
  <!-- BEGIN cart_item_basis_tpl -->
  <!--
  {intl-basis_price} {basis_price}
  -->
  <!-- BEGIN basis_savings_item_tpl -->
  <!-- -->
  <!-- END basis_savings_item_tpl -->
  <!-- BEGIN basis_inc_tax_item_tpl -->
  <!-- -->  
  <!-- END basis_inc_tax_item_tpl -->
  <!-- BEGIN basis_ex_tax_item_tpl -->
  <!-- -->  
  <!-- END basis_ex_tax_item_tpl -->

  <!-- END cart_item_basis_tpl -->
  <!-- END cart_item_tpl -->
  <!-- END cart_item_list_tpl -->
  <tr>
    <td colspan="3" align="right">{intl-subtotal}:</th>
    <!-- BEGIN subtotal_ex_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_ex_tax}&nbsp;</nobr></td>
    <!-- END subtotal_ex_tax_item_tpl -->
    <!-- BEGIN subtotal_inc_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_inc_tax}&nbsp;</nobr></td>
    <td>&nbsp;</td>    
    <!-- END subtotal_inc_tax_item_tpl -->
  </tr>
  <tr>
    <td colspan="3" align="right">{intl-shipping}:</th>
    <!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right"><nobr>{shipping_ex_tax}&nbsp;</nobr></td>
    <!-- END shipping_ex_tax_item_tpl -->
    <!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}&nbsp;</nobr></td>
    <td>&nbsp;</td>    
    <!-- END shipping_inc_tax_item_tpl -->
  </tr>
  
  <!-- BEGIN vouchers_tpl --> 
  <!-- BEGIN voucher_item_tpl -->
  <tr>
    <td colspan="2" align="right">
    <td align="right" class="borderleft">{intl-voucher} {number}:</td>
    <td align="right" class="bordermid"><nobr>- {voucher_price_ex_vat}</nobr>&nbsp;</td>
    <td align="right" class="bordermid"><nobr>- {voucher_price_inc_vat}</nobr>&nbsp;</td>
    <td align="center" class="borderright"><input type="checkbox" name="RemoveVoucherArray[]" value="{number}" /></td>
  </tr>
  <!-- END voucher_item_tpl -->
  <!-- END vouchers_tpl --> 
  <tr>
    <td colspan="3" align="right">{intl-total}:</th>
    <!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}&nbsp;</nobr></td>
    <!-- END total_ex_tax_item_tpl -->
    <!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}&nbsp;</nobr></td>
    <td>&nbsp;</td>
    <!-- END total_inc_tax_item_tpl -->
  </tr>
</table>

<hr size="1" noshade="noshade" />

<b>{intl-shipping_method}:</b><br />
<select name="ShippingTypeID">
  <!-- BEGIN shipping_type_tpl -->
  <option value="{shipping_type_id}" {type_selected}>{shipping_type_name}</option>
  <!-- END shipping_type_tpl -->
</select>
&nbsp;<input class="okbutton" type="submit" name="Recalculate" value="{intl-recalculate}" />

<!-- BEGIN tax_specification_tpl -->
&nbsp;
<!-- BEGIN tax_item_tpl -->
&nbsp;
<!-- END tax_item_tpl -->
&nbsp;
<!-- END tax_specification_tpl -->
<!-- END full_cart_tpl -->


<!-- BEGIN billing_address_tpl -->
<br /><br />
<b>{intl-billing_to}:</b><br />
  <select name="BillingAddressID">
    <!-- BEGIN billing_option_tpl -->
    <option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
    <!-- END billing_option_tpl -->
  </select>
<!-- END billing_address_tpl -->
<br /><br />
<b>{intl-shipping_to}:</b><br />
  <select name="ShippingAddressID">
    <!-- BEGIN shipping_address_tpl -->
    <option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
    <!-- END shipping_address_tpl -->
    <!-- BEGIN wish_user_tpl -->
    <option value="{wish_user_address_id}">{wish_first_name} {wish_last_name}</option>
    <!-- END wish_user_tpl -->
  </select>
  <br /><br />
<b>{intl-comment}:</b><br />
<textarea name="Comment" cols="40" rows="5"></textarea>

<!-- BEGIN show_payment_tpl -->
<br /><br />

<b>{intl-payment_methods_description}:</b><br />
  <select name="PaymentMethod">
    <!-- BEGIN payment_method_tpl -->
    <option value="{payment_method_id}">{payment_method_text}</option>
    <!-- END payment_method_tpl -->
  </select>
<!-- END show_payment_tpl -->

<br /><br />

<!-- BEGIN remove_voucher_tpl -->
<input class="okbutton" type="submit" name="RemoveVoucher" value="{intl-remove_voucher}" />
<!-- END remove_voucher_tpl -->

<hr noshade="noshade" size="1" />

<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />
<input type="hidden" name="TotalVAT" value="{total_vat_value}" />

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>



