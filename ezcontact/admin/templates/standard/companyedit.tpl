<form method="post" action="{www_dir}{index}/contact/company/{action_value}/{company_id}/" enctype="multipart/form-data">

<!-- BEGIN edit_tpl -->

<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_name_item_tpl -->
<li>{intl-error_name}</li>
<!-- END error_name_item_tpl -->

<!-- BEGIN error_address_item_tpl -->
<li>{intl-error_address}{error_address_position}</li>
<!-- END error_address_item_tpl -->

<!-- BEGIN error_phone_item_tpl -->
<li>{intl-error_phone}{error_phone_position}</li>
<!-- END error_phone_item_tpl -->

<!-- BEGIN error_online_item_tpl -->
<li>{intl-error_online}{error_online_position}</li>
<!-- END error_online_item_tpl -->

<!-- BEGIN error_logo_item_tpl -->
<li>{intl-error_logo}</li>
<!-- END error_logo_item_tpl -->

<!-- BEGIN error_image_item_tpl -->
<li>{intl-error_image}</li>
<!-- END error_image_item_tpl -->

</ul>

<hr noshade size="4" />

<br />
<!-- END errors_tpl -->

<h2>{intl-company_headline}</h2>
<p>{intl-general_information}</p>

<!-- BEGIN company_item_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="halfbox" size="20" name="Name" value="{name}" />
	<br /><br />
	</td>
	<td width="50%">
	<p class="boxtext">{intl-orgno}:</p>
	<input type="text" class="halfbox" size="20" name="CompanyNo" value="{companyno}" />
	<br /><br />
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-companytype}:</p>
	<select multiple size="10" name="CompanyCategoryID[]">
	<option value="0" {is_top_selected}>{intl-top}</option>
	<!-- BEGIN company_type_select_tpl -->
	<option value="{company_type_id}" {is_selected}>{company_type_level}{company_type_name}</option>
	<!-- END company_type_select_tpl -->
	</select>
	</td>
	<td valign="top" width="50%">
	<p class="boxtext">{intl-comment}:</p>
	<textarea class="halfbox" cols="20" rows="8" name="Comment">{comment}</textarea>
	</td>
</tr>
</table>

<!-- END company_item_tpl -->

<h2>{intl-address_headline}</h2>
<p>{intl-address_information}</p>
<p>{intl-address_ignore_information}</p>

<!-- BEGIN address_table_item_tpl -->
<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address_pos}&nbsp;{address_position}:</p>
<p><select name="AddressTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN address_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END address_item_select_tpl -->

	    </select>
	<input type="hidden" name="AddressID[]" value="{address_id}" />
	<input type="checkbox" name="AddressDelete[]" value="{address_index}" />
	<span class="boxtext">{intl-delete}</span><br />
        </p>
	<p class="boxtext">{intl-address}:</p>
	<input type="text" class="box" size="40" name="Street1[]" value="{street1}" /><br />
	
	<input type="text" class="box" size="40" name="Street2[]" value="{street2}" /><br />
	<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
        <p class="boxtext">{intl-zip}:*</p>
        <input type="text" size="4" name="Zip[]" value="{zip}" /><br />
		<br />
	</td>
	<td>
        <p class="boxtext">{intl-place}:</p>
        <input type="text" size="20" name="Place[]" value="{place}" /><br />
		<br />
	</td>
</tr>
<tr>
	<td colspan="2">
        <p class="boxtext">{intl-country}:</p>
        <select size="4" name="Country[]" value="{zip}">
	    <option value="-1" {no_country_selected}>{intl-no_country}</option>
	    <!-- BEGIN country_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END country_item_select_tpl -->
	</select>
	</td>
</tr>
</table>
<!-- END address_item_tpl -->
<!-- END address_table_item_tpl -->

<h2>{intl-telephone_headline}</h2>
<p>{intl-telephone_information}</p>
<p>{intl-telephone_ignore_information}</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN phone_table_item_tpl -->
<tr>
    <!-- BEGIN phone_item_tpl -->
    <td>
	<p class="boxtext">{intl-phone_pos} {phone_position}:</p>
	<select name="PhoneTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN phone_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END phone_item_select_tpl -->

	    </select><br />
        <input type="text" size="20" name="Phone[]" value="{phone_number}" />
        <input type="hidden" name="PhoneID[]" value="{phone_id}" /><br />
	<input type="checkbox" name="PhoneDelete[]" value="{phone_index}" />
	<span class="boxtext">{intl-delete}</span><br />
    </td>
    <!-- END phone_item_tpl -->
</tr>
<!-- END phone_table_item_tpl -->
</table>

<h2>{intl-online_headline}</h2>
<p>{intl-online_information}</p>
<p>{intl-online_ignore_information}</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN online_table_item_tpl -->
<tr>
    <!-- BEGIN online_item_tpl -->
    <td>
	<p class="boxtext">{intl-online_pos}&nbsp;{online_position}:</p>
	<select name="OnlineTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN online_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END online_item_select_tpl -->

	    </select><br />
        <input type="text" size="20" name="Online[]" value="{online_value}" />
        <input type="hidden" name="OnlineID[]" value="{online_id}"><br />
	<input type="checkbox" name="OnlineDelete[]" value="{online_index}" />
	<span class="boxtext">{intl-delete}</span><br />
    </td>
    <!-- END online_item_tpl -->
</tr>
<!-- END online_table_item_tpl -->
</table>

<!-- BEGIN project_item_tpl -->
<h2>{intl-project_headline}</h2>
<p>{intl-project_information}</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <!-- BEGIN project_contact_item_tpl -->
    <tr>
	    <td width="1%" valign="top" rowspan="2">
            <p class="boxtext">{intl-contact}:</p>
		    <select size="10" name="ContactID">
		    <!-- BEGIN contact_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_lastname}, {type_firstname}</option>
		    <!-- END contact_item_select_tpl -->
		    </select>
	    </td>
	    <td width="1%" valign="top">
	            <p class="boxtext">{intl-contact_group}:</p>
		    <select name="ContactGroupID">
		    <option value="-2" {none_selected}>{intl-group_none}</option>
		    <option value="-1" {all_selected}>{intl-group_all}</option>
		    <option value="-3" {persons_selected}>{intl-persons_all}</option>
		    <!-- BEGIN contact_group_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END contact_group_item_select_tpl -->
		    </select>
		    <input type="hidden" name="ContactPersonType" value="{contact_person_type}">
			<br />
		    <input type="text" name="UserSearch" value="{user_search}">
		    <input class="stdbutton" type="submit" name="RefreshUsers" value="{intl-refresh}">
			<br /><br />
	    </td>
    </tr>
    <!-- END project_contact_item_tpl -->

    <tr>
	    <td width="1%" valign="top">
	        <p class="boxtext">{intl-state}:</p>
		    <select name="ProjectID">
		    <option value="-1">{intl-no_state}</option>
		    <!-- BEGIN project_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END project_item_select_tpl -->
		    </select>
    	    <p>{intl-project_status_information}</p>
	    </td>
	    <td width="*" valign="top">
	    </td>
    </tr>
</table>
<!-- END project_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
	<h2>{intl-logo}</h2>
    </td>
    <td valign="top">
	<h2>{intl-image}</h2>
    </td>
</tr>

<tr>
    <td>
        <!-- BEGIN logo_item_tpl -->
        <img src="{www_dir}{logo_image_src}" width="{logo_image_width}" height="{logo_image_height}" border="0" alt="{logo_image_alt}" />
	<input name="DeleteLogo" type="checkbox" />
	<span class="boxtext">{intl-delete}</span>
        <!-- END logo_item_tpl -->
    </td>

    <td>
        <!-- BEGIN image_item_tpl -->
        <img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<input name="DeleteImage" type="checkbox" />
	<span class="boxtext">{intl-delete}</span>
        <!-- END image_item_tpl -->
    </td>

</tr>
<tr>
    <td valign="top">
    <p class="boxtext">{intl-file}:</p>
	<input class="halfbox" size="20" name="logo" type="file" />
	<input type="hidden" name="LogoImageID" value="{logo_id}" />
    </td>

    <td>
  	<p class="boxtext">{intl-file}:</p>
	<input class="halfbox" size="20" name="image" type="file" />
	<input type="hidden" name="CompanyImageID" value="{image_id}" />
    </td>
<tr>
</table>

<br />

<p>{intl-address_optional}</p>

<input type="hidden" name="CompanyID" value="{company_id}">

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteMarked" value="{intl-delete_marked}" />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="NewAddress" value="{intl-new_address}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewPhone" value="{intl-new_phone}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewOnline" value="{intl-new_online}" />
	</td>
</tr>
</table>

<!-- END edit_tpl -->

<!-- BEGIN confirm_tpl -->

<h1>{intl-confirm_headline}</h1>

<hr noshade size="4" />

<br />

<h2>{intl-confirm_delete}{name}{intl-confirm_delete_end}</h2>

<input type="hidden" name="Confirm" value="confirm">

<!-- END confirm_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />
<!-- BEGIN delete_item_tpl -->

<!-- END delete_item_tpl -->

</form>

