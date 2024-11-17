<!-- orderlist.tpl --> 
<!-- $Id: orderview.tpl 7812 2001-10-12 11:58:08Z sascha $ -->

<h1>{intl-head_line}</h1>

<form action="{www_dir}{index}/trade/orderlist/">

<hr noshade="noshade" size="1" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td>
      <b>{intl-billing_address}:</b><br />
      {customer_first_name} {customer_last_name}<br /> 
      {billing_street1}<br />
      {billing_street2}<br />
      {billing_zip} {billing_place}<br />
      {billing_country}
    </td>
    <td>
      <b>{intl-shipping_address}:</b><br />
      {shipping_first_name} {shipping_last_name}<br />
      {shipping_street1}<br />
      {shipping_street2}<br />
      {shipping_zip} {shipping_place}<br />
      {shipping_country}
    </td>
  </tr>
  <tr>
    <td>
      <b>{intl-payment_method}:</b><br />
      {intl-payment_by} {payment_method}
    </td>
    <td>
      <b>{intl-shipping_method}:</b><br />
      {shipping_method}
    </td>
  </tr>
</table>

<hr noshade="noshade" size="1" />
<h2>{intl-productlist}</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="1" border="0">
  <tr align="left">
    <th>&nbsp;{intl-image}:</th>
    <th>{intl-count}:</th>    
    <th>{intl-productname}:</th>
    <th align="right">{intl-price}:&nbsp;</td>
  </tr>
  <!-- BEGIN order_item_tpl -->
  <tr>
    <td class="{td_class}">
      &nbsp;<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
    </td>
    <td class="{td_class}">{order_item_count}</td>    
    <td class="{td_class}">
      <a href="{www_dir}/trade/productview/{product_id}/">{product_name}&nbsp;</a>
      <!-- BEGIN order_item_option_tpl -->
      <br /><span class="small">{option_name}: {option_value}</span>
      <!-- END order_item_option_tpl -->	
    </td>
    <td class="{td_class}" align="right">{product_price}&nbsp;</td>
  </tr>
  <!-- END order_item_tpl -->
  <tr>
    <td colspan="2">&nbsp;</td>
    <td align="right">{intl-shipping}:</td>
    <td align="right">{shipping_cost}&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td align="right">{intl-vat}:</td>
    <td align="right">{vat_cost}&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td align="right">{intl-total}:</td>
    <td align="right">{order_sum}&nbsp;</td>
  </tr>
</table>
<!-- END order_item_list_tpl -->

<hr noshade="noshade" size="1" />

<input class="okbutton" type="submit" value="{intl-ok}"/>
</form>
