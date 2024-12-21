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

<!-- BEGIN error_email_item_tpl -->
<li>{intl-error_email}
<!-- END error_email_item_tpl -->

<!-- BEGIN error_address_item_tpl -->
<li>{intl-error_address}
<!-- END error_address_item_tpl -->

</ul>
<!-- END errors_tpl -->

<p class="boxtext">{intl-name}:</p>
<input type="text" size="20" name="Name" value="{name}"/>

<!--
<p class="boxtext">{intl-orgno}:</p>
<input type="text" size="20" name="CompanyNo" value="{companyno}"/>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{description}</textarea>

-->

<p class="boxtext">{intl-companytype}:</p>

<select multiple size="10" name="CompanyCategoryID[]">
<!-- BEGIN company_type_select_tpl -->
<option value="{company_type_id}" {is_selected}>{company_type_name}</option>
<!-- END company_type_select_tpl -->
</select>


<!-- BEGIN address_item_tpl -->
<!--
<p class="boxtext">{intl-address}:</p>
<input type="text" size="20" name="Street1" value="{street1}"/><br>
<input type="text" size="20" name="Street2" value="{street2}"/>

<p class="boxtext">{intl-zip}:</p>
<input type="text" size="4" name="Zip" value="{zip}"/>

<p class="boxtext">{intl-place}:</p>
<input type="text" size="20" name="Place" value="{place}"/>
-->
<!-- END address_item_tpl -->


<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
<input type="text" size="20" name="Phone[]" value="{telephone}"/>
<input type="hidden" name="PhoneTypeID[]" value="1">
<input type="hidden" name="PhoneID[]" value="{tele_phone_id}">
<!-- END phone_item_tpl -->

<!-- BEGIN fax_item_tpl -->
<!--
<p class="boxtext">{intl-fax}:</p>
<input type="text" size="20" name="Phone[]" value="{fax}"/>
<input type="hidden" name="PhoneTypeID[]" value="2">
<input type="hidden" name="PhoneID[]" value="{fax_phone_id}">
-->
<!-- END fax_item_tpl -->

<!-- BEGIN web_item_tpl -->
<!--
<p class="boxtext">{intl-web}:</p>
<input type="text" size="20" name="Online[]" value="{web}"/>
<input type="hidden" name="URLType[]" value="http">
<input type="hidden" name="OnlineTypeID[]" value="1">
<input type="hidden" name="OnlineID[]" value="{web_online_id}">
-->
<!-- END web_item_tpl -->

<!-- BEGIN email_item_tpl -->
<!--
<p class="boxtext">{intl-email}:</p>
<input type="text" size="20" name="Online[]" value="{email}"/>
<input type="hidden" name="URLType[]" value="mailto">
<input type="hidden" name="OnlineTypeID[]" value="2">
<input type="hidden" name="OnlineID[]" value="{email_online_id}">
-->
<!-- END email_item_tpl -->



<!-- BEGIN logo_add_tpl -->
<!--
	<p class="boxtext">{intl-logo}</p>
	<input size="40" name="logo" type="file" />
-->
<!-- END logo_add_tpl -->

<!-- BEGIN image_add_tpl -->
<!--
	<p class="boxtext">{intl-image}</p>
	<input size="40" name="image" type="file" />
-->
<!-- END image_add_tpl -->

<!-- BEGIN image_edit_tpl -->
<!--
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
       <p class="boxtext">{image_name}</p>
       <img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
        </td>
    <td>
    	<p class="boxtext">{intl-logo}</p>
	<input size="20" name="image" type="file" />
	<input type="hidden" name="ImageID" value="{image_id}">
    </td>
    <td>
	<p class="boxtext">{intl-delete}</p>
	<input name="DeleteImage" type="checkbox" />
    </td>
<tr>
</table>
-->
<!-- END image_edit_tpl -->

<!-- BEGIN logo_edit_tpl -->
<!--
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
       <p class="boxtext">{logo_name}</p>
       <img src="{www_dir}{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
        </td>
    <td>
    	<p class="boxtext">{intl-logo}</p>
	<input size="20" name="logo" type="file" />
	<input type="hidden" name="LogoID" value="{logo_id}">
    </td>
    <td>
	<p class="boxtext">{intl-delete}</p>
	<input name="DeleteImage" type="checkbox" />
    </td>
<tr>
</table>
-->
<!-- END logo_edit_tpl -->


<br /><br />

<hr noshade size="4"/>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input type="hidden" name="UserID" value="{user_id}" />
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/contact/companylist/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

