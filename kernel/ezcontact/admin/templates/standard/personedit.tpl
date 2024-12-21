<form method="post" action="{www_dir}{index}/contact/person/{action_value}/{person_id}" enctype="multipart/form-data">

<!-- BEGIN edit_tpl -->

<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_firstname_item_tpl -->
<li>{intl-error_firstname}</li>
<!-- END error_firstname_item_tpl -->

<!-- BEGIN error_lastname_item_tpl -->
<li>{intl-error_lastname}</li>
<!-- END error_lastname_item_tpl -->

<!-- BEGIN error_birthdate_item_tpl -->
<li>{intl-error_birthdate}</li>
<!-- END error_birthdate_item_tpl -->

<!-- BEGIN error_address_item_tpl -->
<li>{intl-error_address}{error_address_position}</li>
<!-- END error_address_item_tpl -->

<!-- BEGIN error_phone_item_tpl -->
<li>{intl-error_phone}{error_phone_position}</li>
<!-- END error_phone_item_tpl -->

<!-- BEGIN error_online_item_tpl -->
<li>{intl-error_online}{error_online_position}</li>
<!-- END error_online_item_tpl -->

</ul>
<!-- END errors_tpl -->

<!-- BEGIN person_item_tpl -->
<h2>{intl-personal_headline}</h2>
<p>{intl-general_information}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	  <td width="50%">
	  <p class="boxtext">{intl-firstname}:</p>
	  <input class="halfbox" type="text" size="20" name="FirstName" value="{firstname}" />
	  <br /><br />
	  </td> 
	  <td width="50%" colspan="2">
	  <p class="boxtext">{intl-lastname}:</p>
	  <input class="halfbox" type="text" size="20" name="LastName" value="{lastname}" />
	  <br /><br />
	  </td>
</tr>
<tr>
   	<td rowspan="2">
	<p class="boxtext">{intl-company}:</p>
	<select multiple size="10" name="CompanyID[]">
	<!-- BEGIN company_select_tpl -->
	<option value="{company_id}" {is_selected}>{company_level}{company_name}</option>
	<!-- END company_select_tpl -->
	</select>
    	</td>
    	<td valign="top">
<p class="boxtext">{intl-birthday_headline}:*</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="1%" valign="bottom">
    <select name="BirthDay">
	<!-- BEGIN day_item_tpl -->
	<option value="{day_id}" {selected}>{day_value}</option>
	<!-- END day_item_tpl -->
	</select>
    </td>
    <td width="1%" valign="bottom">
    <select name="BirthMonth" >
	<option value="1" {select_january}>{intl-january}</option>
	<option value="2" {select_february}>{intl-february}</option>
	<option value="3" {select_march}>{intl-march}</option>
	<option value="4" {select_april}>{intl-april}</option>
	<option value="5" {select_may}>{intl-may}</option>
	<option value="6" {select_june}>{intl-june}</option>
	<option value="7" {select_july}>{intl-july}</option>
	<option value="8" {select_august}>{intl-august}</option>
	<option value="9" {select_september}>{intl-september}</option>
	<option value="10" {select_october}>{intl-october}</option>
	<option value="11" {select_november}>{intl-november}</option>
	<option value="12" {select_december}>{intl-december}</option>
	</select>
    </td>
    <td width="1%" valign="bottom">
        <input type="text" size="4" name="BirthYear" value="{birthyear}" />
    </td>
    <td width="97%" valign="bottom">
        &nbsp;
    </td>
</tr>
</table>
    	</td>
    </tr>
    <tr>
    	<td valign="top">
	<p class="boxtext">{intl-comment_headline}:</p>
	<textarea name="Comment" class="halfbox" rows="4" cols="20" wrap="soft">{comment}</textarea>
	</td>
    </tr>
</table>

<!-- END person_item_tpl -->

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
        <input type="text" size="4" name="Zip[]" value="{zip}" />&nbsp;&nbsp;<br />
		<br />
	</td>
	<td>
        <p class="boxtext">{intl-place}:</p>
        <input type="text" class="halfbox" size="20" name="Place[]" value="{place}" /><br />
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
	<p class="boxtext">{intl-phone_pos}&nbsp;{phone_position}:</p>
	<select name="PhoneTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN phone_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END phone_item_select_tpl -->

	    </select><br />
        <input type="text" class="halfbox" size="20" name="Phone[]" value="{phone_number}" />
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
        <input type="text" class="halfbox" size="20" name="Online[]" value="{online_value}" />
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

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <!-- BEGIN project_contact_item_tpl -->
    <tr>
	    <td width="50%" valign="top" rowspan="2">
	            <p class="boxtext">{intl-contact}:</p>
		    <select size="10" name="ContactID">
		    <!-- BEGIN contact_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_lastname}, {type_firstname}</option>
		    <!-- END contact_item_select_tpl -->
		    </select>
	    </td>
	    <td width="50%" valign="top">
	            <p class="boxtext">{intl-contact_group}:</p>
		    <select name="ContactGroupID">
		    <option value="-2" {none_selected}>{intl-group_none}</option>
		    <option value="-1" {all_selected}>{intl-group_all}</option>
		    <!-- BEGIN contact_group_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END contact_group_item_select_tpl -->
		    </select>
		    <input type="hidden" name="ContactPersonType" value="{contact_person_type}">
		    <br />
		    <input type="text" name="UserSearch" value="{user_search}">
		    <input type="submit" name="RefreshUsers" value="{intl-refresh}">
	    </td>
    </tr>
    <!-- END project_contact_item_tpl -->

    <tr>
	    <td valign="top" colspan="2">
	    	    <p>{intl-project_status_information}</p>
	            <p class="boxtext">{intl-state}:</p>
		    <select name="ProjectID">
		    <option value="-1">{intl-no_state}</option>
		    <!-- BEGIN project_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END project_item_select_tpl -->
		    </select>
	    </td>
    </tr>
</table>
<!-- END project_item_tpl -->

<input type="hidden" name="PersonID" value="{person_id}">

<br />

<p>{intl-address_optional}</p>

<p class="boxtext">Image:</p>
<!-- BEGIN image_item_tpl -->
<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
<div><input type="checkbox" name="DeleteImage"><span class="p"> Delete image</span></div><br />
<!-- END image_item_tpl -->

<input class="box" size="40" name="ImageFile" type="file" />
<br /><br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteMarked" value="{intl-delete_marked}">

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="NewAddress" value="{intl-new_address}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewPhone" value="{intl-new_phone}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewOnline" value="{intl-new_online}">
	</td>
</tr>
</table>

<!-- END edit_tpl -->

<!-- BEGIN confirm_tpl -->

<h1>{intl-confirm_headline}</h1>

<hr noshade size="4" />

<br />

<h2>{intl-confirm_delete}{lastname}, {firstname}{intl-confirm_delete_end}</h2>

<input type="hidden" name="Confirm" value="confirm">

<!-- END confirm_tpl -->
<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />
<!-- BEGIN delete_item_tpl -->

<!-- END delete_item_tpl -->

</form>
