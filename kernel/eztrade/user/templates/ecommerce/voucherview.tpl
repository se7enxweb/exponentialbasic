<form method="post" action="{www_dir}{index}/trade/voucherview/">

<h1>{intl-voucher_edit}</h1>

<hr noshade="noshade" size="4" />

{intl-key}: <input type="text" size="20" name="Key" value="{voucher_key}"/>&nbsp;
<input class="stdbutton" type="submit" name="ViewVoucher" value="{intl-view_voucher}" />

<br /><br />

<!-- BEGIN error_tpl -->
<p class="error"> {intl-error_message}</p>
<!-- END error_tpl -->


<!-- BEGIN view_voucher_tpl -->

<p class="boxtext">{intl-price}:</p>
{voucher_price}


<p class="boxtext">{intl-created}:</p>
{voucher_created}

<br /><br />

<!-- BEGIN email_information_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <td>
	<p class="boxtext">{intl-receiver}:</p>
	{sent_email}
        </td>
</tr>
<tr>
        <td>
	<p class="boxtext">{intl-description}:</p>
	{sent_description}
        </td>
</tr>
<!-- END email_information_tpl -->
</table>

<br />
<!-- BEGIN used_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-used}:
	</th>
	<th>
	{intl-used_price}:
	</th>
	<th>
	{intl-view_order}:
	</th>
</tr>
<!-- BEGIN used_item_tpl -->
<tr>
       <td class="{td_class}">
       {used_used}       
       </td>
       <td class="{td_class}">
       {used_price}       
       </td>
       <td class="{td_class}">
       <a href="{www_dir}{index}/trade/orderview/{voucher_order_id}/">{voucher_order_id}</a>       
       </td>
</tr>
<!-- END used_item_tpl -->
</table>
<!-- END used_list_tpl -->

<br />
<hr noshade="noshade" size="4" />


<!-- END view_voucher_tpl -->

</form>
