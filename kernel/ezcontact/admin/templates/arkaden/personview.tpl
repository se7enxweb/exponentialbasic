<h1>{intl-view_headline}</h1>

<hr noshade size="4"/>

<br />

<p class="boxtext">{intl-name_headline}:</p>
<p>
{firstname} {lastname}<br />
</p>
<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-addresses_headline}:</p>
<table>
<!-- BEGIN address_line_tpl -->
<tr>
<td>{address_id}</td>
<td>{address_type_id} {address_type_name}</td>
<td>{street1}</td>
<td>{street2}</td>
<td>{zip}</td>
<td>{place}</td>
</tr>
<!-- END address_line_tpl -->
</table>
<!-- END address_item_tpl -->

<!-- BEGIN no_address_item_tpl -->
<p class="boxtext">{intl-addresses_headline}:</p>
<p>{intl-error_no_addresses}</p>
<!-- END no_address_item_tpl -->


<p class="boxtext">{intl-telephone_headline}:</p>

<!-- BEGIN phone_item_tpl -->
<table>
<!-- BEGIN phone_line_tpl -->
<tr>
<td>{phone_id}</td>
<td>{phone_type_id}{phone_type_name}</td>
<td>{phone}</td>
</tr>
<!-- END phone_line_tpl -->
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<p class="boxtext">{intl-telephone_headline}:</p>
<p>{intl-error_no_phones}</p>
<!-- END no_phone_item_tpl -->

<p class="boxtext">{intl-online_headline}:</p>
<!-- BEGIN online_item_tpl -->
<table>
<!-- BEGIN online_line_tpl -->
<tr>
<td>{online_id}</td>
<td>{online_type_id} {online_type_name}</td>
<td>{online}</td>
</tr>
<!-- END online_line_tpl -->
</table>
<!-- END online_item_tpl -->
<!-- BEGIN no_online_item_tpl -->
<p class="boxtext">{intl-online_headline}:</p>
<p>{intl-error_no_onlines}</p>
<!-- END no_online_item_tpl -->

<p class="boxtext">{intl-birthday_headline}: </p>
<p>{birthyear} {birthmonth} {birthday} {personno}</p>
<p class="boxtext">{intl-comment_headline}:</p>
<p>{comment}</p>

<hr noshade size="4"/>

<form method="post" action="{www_dir}{index}/contact/person/edit/{person_id}/" enctype="multipart/form-data">
<input class="okbutton" type="submit" value="{intl-edit}" />
</form>

<form method="post" action="{www_dir}{index}/contact/person/list/">
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

