<form method="post" action="{www_dir}{index}/contact/company/{action_value}/{company_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_name_item_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_item_tpl -->

    <!-- BEGIN error_companyno_item_tpl -->
    <li>{intl-error_companyno}
    <!-- END error_companyno_item_tpl -->

    <!-- BEGIN error_address_item_tpl -->
    <li>{intl-error_address}
    <!-- END error_address_item_tpl -->

    <!-- BEGIN error_email_item_tpl -->
    <li>{intl-error_email}
    <!-- END error_email_item_tpl -->

    <!-- BEGIN error_email_not_valid_item_tpl -->
    <li>{intl-error_email_not_valid}
    <!-- END error_email_not_valid_item_tpl -->

    <!-- BEGIN error_password_item_tpl -->
    <li>{intl-error_password}
    <!-- END error_password_item_tpl -->

    <!-- BEGIN error_password_too_short_item_tpl -->
    <li>{intl-error_password_too_short}
    <!-- END error_password_too_short_item_tpl -->

    <!-- BEGIN error_passwordrepeat_item_tpl -->
    <li>{intl-error_passwordrepeat_item}
    <!-- END error_passwordrepeat_item_tpl -->

    <!-- BEGIN error_passwordmatch_item_tpl -->
    <li>{intl-error_passwordmatch_item}
    <!-- END error_passwordmatch_item_tpl -->

    <!-- BEGIN error_loginname_item_tpl -->
    <li>{intl-error_loginname}
    <!-- END error_loginname_item_tpl -->

</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="20" name="Name" value="{name}"/>
	</td>
	<td>
	<p class="boxtext">{intl-orgno}:</p>
	<input type="text" size="20" name="CompanyNo" value="{companyno}"/>
	</td>
</tr>
<tr>
	<td>
	<br />
	<p class="boxtext">{intl-login}:</p>
	<input type="text" {read_only} size="20" name="Login" value="{login}"/>
	</td>
</tr>
<tr>
	<td>
	<br />
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password}"/>
	</td>
	<td>
	<br />
	<p class="boxtext">{intl-repeat_password}:</p>
	<input type="password" size="20" name="RepeatPassword" value="{repeat_password}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-companytype}:</p>
<select multiple size="10" name="CompanyCategoryID[]">
<!-- BEGIN company_type_select_tpl -->
<option value="{company_type_id}" {is_selected}>{company_type_level}{company_type_name}</option>
<!-- END company_type_select_tpl -->
</select>


<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
<input type="text" size="30" name="Street1" value="{street1}"/><br>
<input type="text" size="30" name="Street2" value="{street2}"/>
<input type="hidden" name="AddressTypeID" value="{address_type_id}">

<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
	<p class="boxtext">{intl-zip}:</p>
	<input type="text" size="4" name="Zip" value="{zip}"/>
	</td>
	<td>
	<p class="boxtext">{intl-place}:</p>
	<input type="text" size="20" name="Place" value="{place}"/>
	</td>
</tr>
</table>
<!-- END address_item_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
<!-- BEGIN phone_item_tpl -->
	<p class="boxtext">{intl-telephone}:</p>
	<input type="text" size="20" name="Phone[]" value="{telephone}"/>
	<input type="hidden" name="PhoneTypeID[]" value="{phone_type_id}">
	<input type="hidden" name="PhoneID[]" value="{tele_phone_id}">
<!-- END phone_item_tpl -->
	</td>
	<td>
<!-- BEGIN fax_item_tpl -->
	<p class="boxtext">{intl-fax}:</p>
	<input type="text" size="20" name="Phone[]" value="{fax}"/>
	<input type="hidden" name="PhoneTypeID[]" value="{fax_type_id}">
	<input type="hidden" name="PhoneID[]" value="{fax_phone_id}">
<!-- END fax_item_tpl -->
	</td>
</tr>
</table>

<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
<!-- BEGIN web_item_tpl -->
	<p class="boxtext">{intl-web}:</p>
	<input type="text" size="20" name="OnlineWeb" value="{web}"/>
	<input type="hidden" name="URLType[]" value="http">
	<input type="hidden" name="OnlineTypeID[]" value="{web_type_id}">
	<input type="hidden" name="OnlineID[]" value="{web_online_id}">
<!-- END web_item_tpl -->
	</td>
	<td>
<!-- BEGIN email_item_tpl -->
	<p class="boxtext">{intl-email}:</p>
	<input type="text" size="20" name="OnlineEmail" value="{email}"/>
	<input type="hidden" name="URLType[]" value="mailto">
	<input type="hidden" name="OnlineTypeID[]" value="{email_type_id}">
	<input type="hidden" name="OnlineID[]" value="{email_online_id}">
<!-- END email_item_tpl -->
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{description}</textarea>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
	<h2>{intl-logo}</h2>
<!-- BEGIN logo_add_tpl -->
	<p class="boxtext">{intl-logo}:</p>
	<input size="20" name="logo" type="file" />
<!-- END logo_add_tpl -->

<!-- BEGIN logo_edit_tpl -->
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<!--       <p class="boxtext">{logo_name}:</p> -->
    <td>
    <img src="{www_dir}{logo_image_src}" width="{logo_image_width}" height="{logo_image_height}" border="0" alt="{image_alt}" /><br /><br />
   	<p class="boxtext">{intl-logo}:</p>
	<input size="20" name="logo" type="file" />
	<input type="hidden" name="LogoID" value="{logo_id}">
	<br /><br />
	<p class="boxtext">{intl-delete}: <input name="DeleteLogo" type="checkbox" /></p>
    </td>
<tr>
</table>
<!-- END logo_edit_tpl -->

	</td>
	<td valign="top">
	<h2>{intl-image}</h2>

<!-- BEGIN image_add_tpl -->
	<p class="boxtext">{intl-image}:</p>
	<input size="20" name="image" type="file" />
<!-- END image_add_tpl -->

<!-- BEGIN image_edit_tpl -->
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<!--       <p class="boxtext">{image_name}:</p> -->
    <td>
    <img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /><br /><br />
  	<p class="boxtext">{intl-image}:</p>
	<input size="20" name="image" type="file" />
	<input type="hidden" name="ImageID" value="{image_id}">
	<br /><br />
	<p class="boxtext">{intl-delete}: <input name="DeleteImage" type="checkbox" /></p>
    </td>
<tr>
</table>

<!-- END image_edit_tpl -->

	</td>
</tr>
</table>

<br />

<input type="hidden" name="UserID" value="{user_id}" />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}">
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="OK" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}">

</form>

