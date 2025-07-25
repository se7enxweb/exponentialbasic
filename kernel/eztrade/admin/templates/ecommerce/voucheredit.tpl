<form method="post" action="{www_dir}{index}/trade/voucheredit/">

<h1>{intl-voucher_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-price}:</p>
<input type="text" size="8" name="Price" value="{voucher_price}"/>

<p class="boxtext">{intl-available}:</p>
<input type="checkbox"  name="Available"  {is_checked} />

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
	<p class="boxtext">{intl-receiver_description}:</p>
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
	{intl-used_by}:
	</th>
	<th>
	{intl-used_price}:
	</th>
	<th>
	{intl-order}:
	</th>
</tr>
<!-- BEGIN used_item_tpl -->
<tr>
       <td class="{td_class}">
       {used_used}       
       </td>
       <td class="{td_class}">
       <a href="{www_dir}{index}/trade/customerview/{user_id}/">{user_name}</a>
       </td>
       <td class="{td_class}">
       {used_price}       
       </td>
       <td class="{td_class}">
       <a href="{www_dir}{index}/trade/orderedit/{voucher_order_id}/">{voucher_order_id}</a>       
       </td>
</tr>
<!-- END used_item_tpl -->
</table>
<!-- END used_list_tpl -->

<br />
<hr noshade="noshade" size="4" />


<input type="hidden" name="VoucherID" value="{voucher_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
