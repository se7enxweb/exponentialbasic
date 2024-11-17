<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-view_headline}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="{www_dir}{index}/contact/search/person/" method="get">
        <input type="text" name="SearchText" size="12" />       
        <input class="stdbutton" type="submit" value="{intl-search}" />
        </form> 
        </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN image_item_tpl -->
<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
<!-- END image_item_tpl --> 

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<span class="p">{firstname} {lastname}</span>
	</td>

	<td>
	<p class="boxtext">{intl-birthday_headline}: </p>
	<!-- BEGIN birth_item_tpl -->
	<span class="p">{birthdate}</span>
	<!-- END birth_item_tpl -->
	<!-- BEGIN no_birth_item_tpl -->
	<span class="p">{intl-no_birthday}</span>
	<!-- END no_birth_item_tpl -->
	</td>
</tr>
</table>

<p class="boxtext">{intl-companies}</p>

<!-- BEGIN company_item_tpl -->
<a href="{www_dir}{index}/contact/company/view/{company_id}/">{company_name}</a><br />
<!-- END company_item_tpl -->

<p class="boxtext">{intl-description_headline}:</p>
<span class="p">{description}</span><br />

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-addresses_headline}</p>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN address_line_tpl -->
	<td>
	<p class="boxtext">{address_type_name}:</p>
	<span class="p">{street1}</span><br />
	<span class="p">{street2}</span><br />
	<span class="p">{zip} {place}</span><br />
	<span class="p">{country}</span><br />
	</td>
<!-- END address_line_tpl -->
</tr>
</table>
<!-- END address_item_tpl -->
<!-- BEGIN no_address_item_tpl -->
<p class="boxtext">{intl-addresses_headline}</p>
<p>{intl-error_no_addresses}</p>
<!-- END no_address_item_tpl -->

<p class="boxtext">{intl-telephone_headline}</p>
<!-- BEGIN phone_item_tpl -->
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN phone_line_tpl -->
	<td valign="top">
	<span class="boxtext">{phone_type_name}:</span> {phone}
	</td>
<!-- END phone_line_tpl -->
</tr>
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<p>{intl-error_no_phones}</p>
<!-- END no_phone_item_tpl -->


<p class="boxtext">{intl-online_headline}</p>
<!-- BEGIN online_item_tpl -->
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN online_line_tpl -->
	<td>
	<span class="boxtext">{online_type_name}:</span> <a href="{www_dir}{index}{online_prefix}{online}">{online_visual_prefix}{online}</a>
	</td>
<!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->
<!-- BEGIN no_online_item_tpl -->
<p>{intl-error_no_onlines}</p>
<!-- END no_online_item_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="100%">
	<p class="boxtext">{intl-project_status}:</p>
	<!-- BEGIN project_status_tpl -->
	{project_status}
	<!-- END project_status_tpl -->
	<!-- BEGIN no_project_status_tpl -->
	{intl-no_project_status}
	<!-- END no_project_status_tpl -->
	<br /><br />
	</td>
</tr>
</table>

<!-- BEGIN consultation_table_item_tpl -->
<h2>{intl-consultation_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-consultation_date}:</th>
	<th>{intl-consultation_short_description}:</th>
	<th>{intl-consultation_status}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td>
        {consultation_date}
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/type/list/{consultation_status_id}">{consultation_status}</a>
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/edit/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{consultation_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/delete/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-slett','','/design/admin/images/slettminimrk.gif',1)"><img name="ezc{consultation_id}-slett" border="0" src="{www_dir}/design/admin/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	

</tr>
<!-- END consultation_item_tpl -->
</table>

<!-- END consultation_table_item_tpl -->

<!-- BEGIN order_table_item_tpl -->
<h2>{intl-sales_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-order_date}:</th>
	<th>{intl-order_status}:</th>
	<th>{intl-order_price}:</th>
</tr>

<!-- BEGIN order_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_date}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_status}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_price}</a>
	</td>
</tr>
<!-- END order_item_tpl -->
</table>

<!-- END order_table_item_tpl -->

<!-- BEGIN mail_table_item_tpl -->
<h2>{intl-mail_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-mail_date}:</th>
	<th>{intl-mail_subject}:</th>
	<th>{intl-mail_email}:</th>
</tr>

<!-- BEGIN mail_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_date}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_subject}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_email}</a>
	</td>
</tr>
<!-- END mail_item_tpl -->
</table>

<!-- END mail_table_item_tpl -->


<form method="post" action="{www_dir}{index}/contact/person/edit/{person_id}/">

<hr noshade="noshade" size="4" />

<!-- BEGIN consultation_buttons_tpl -->
<input class="stdbutton" type="submit" name="ListConsultation" value="{intl-consultation_list}">
<input class="stdbutton" type="submit" name="NewConsultation" value="{intl-consultation}">
<!-- BEGIN file_button_tpl -->
<input class="stdbutton" type="submit" name="FileButton" value="{intl-files}">
<!-- END file_button_tpl -->
<!-- BEGIN buy_button_tpl -->
<input class="stdbutton" type="submit" name="BuyButton" value="{intl-buy}">
<!-- END buy_button_tpl -->
<!-- BEGIN mail_button_tpl -->
<input class="stdbutton" type="submit" name="MailButton" value="{intl-mail}">
<!-- END mail_button_tpl -->
<!-- END consultation_buttons_tpl -->
<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">
</form>
