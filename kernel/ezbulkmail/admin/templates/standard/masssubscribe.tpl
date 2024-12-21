<form method="post" action="{www_dir}{index}/bulkmail/masssubscribe/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN new_email_list_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{intl-new_addresses}:</th>
</tr>
<!-- BEGIN new_email_item_tpl -->
<tr>
        <td>{new_email} {intl-to} {new_category}</td>
</tr>
<!-- END new_email_item_tpl -->
</table>
<br />
<!-- END new_email_list_tpl -->


<!-- BEGIN email_exists_list_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{intl-addresses_already_exists}:</td>
</tr>
<!-- BEGIN email_exists_item_tpl -->
<tr>
        <td>{email_exists}</td>
</tr>
<!-- END email_exists_item_tpl -->
</table>
<br />
<!-- END email_exists_list_tpl -->

<!-- BEGIN not_valid_list_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{intl-not_valid_addresses}:</th>
</tr>
<!-- BEGIN not_valid_item_tpl -->
<tr>
        <td>{not_valid}</td>
</tr>
<!-- END not_valid_item_tpl -->
</table>
<!-- END not_valid_list_tpl -->


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-addresses}:</p>
	<textarea name="Addresses" class="box" rows="20" cols="40">{addresses}</textarea>
	</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-categories}:</p>
	<select multiple size="5" Name="CategoryArrayID[]">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}">{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
</table>

<p class="boxtext"><input type="checkbox" name="SendMail" />&nbsp;{intl-send_welcome_message}</p>
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}" name="OK" />

</form>
