<h1>{intl-voucher_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN voucher_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/voucherlist/">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-created}:</th>
	<th>{intl-price}:</th>
	<th>{intl-available}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN voucher_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/voucheredit/edit/{voucher_id}/">{voucher_created}&nbsp;</a>
	</td>
	<td class="{td_class}">
	{voucher_price}&nbsp;
	</td>
	<td class="{td_class}">
	<!-- BEGIN voucher_is_available_tpl -->
	{intl-is_available}&nbsp;
	<!-- END voucher_is_available_tpl -->
	<!-- BEGIN voucher_is_not_available_tpl -->
	{intl-is_not_available}&nbsp;
	<!-- END voucher_is_not_available_tpl -->
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/voucheredit/edit/{voucher_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{voucher_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{voucher_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="DeleteArrayID[]" value="{voucher_id}" />
	</td>
</tr>
<!-- END voucher_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>
<!-- END voucher_list_tpl -->






