<form method="post" action="{www_dir}{index}/trade/voucherview/">
<h1>{intl-voucher_edit}</h1>
<hr noshade="noshade" size="1" />
<table width="100%" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td colspan="3">
      <b>{intl-key}:</b><br />
      <input type="text" size="20" name="Key" value="{voucher_key}"/>&nbsp;
      <input class="okbutton" type="submit" name="ViewVoucher" value="{intl-view_voucher}" />
    </td>
  </tr>

  <!-- BEGIN error_tpl -->
  <tr>
    <td colspan="3"><p>{intl-error_message}</p></td>
  </tr>
  <!-- END error_tpl -->

  <!-- BEGIN view_voucher_tpl -->
  <tr>
    <td colspan="3">
      <b>{intl-price}:</b><br />
      {voucher_price}
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <b>{intl-created}:</b></br>
      {voucher_created}
    </td>
  </tr>
  <!-- BEGIN email_information_tpl -->
  <tr>
    <td colspan="3">
      <b>{intl-receiver}:</b><br />
      {sent_email}
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <b>{intl-description}:</b><br />
      {sent_description}
    </td>
  </tr>
  <!-- END email_information_tpl -->

  <!-- BEGIN used_list_tpl -->
  <tr align="left">
    <th>{intl-used}:</th>
    <th>{intl-used_price}:</th>
    <th>{intl-view_order}:</th>
  </tr>
  <!-- BEGIN used_item_tpl -->
  <tr>
    <td class="{td_class}">{used_used}</td>
    <td class="{td_class}">{used_price}</td>
    <td class="{td_class}">
      <a href="/trade/orderview/{voucher_order_id}/">{intl-invoice_nr} {voucher_order_id}</a>       
    </td>
  </tr>
  <!-- END used_item_tpl -->
  <!-- END used_list_tpl -->
  <!-- END view_voucher_tpl -->

</table>
<hr noshade="noshade" size="1" />
</form>
