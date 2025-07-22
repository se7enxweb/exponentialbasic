<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; 
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
<!-- BEGIN new_user_tpl -->
<h1 class="mainHeading">{intl-head_line}</h1>
<!-- END new_user_tpl -->
<!-- BEGIN edit_user_tpl -->
<h1 class="mainHeading">{intl-edit_head_line}</h1>
<!-- END edit_user_tpl -->

<form method="post" name="AddressForm" action="{www_dir}{index}/user/userwithaddress/{action_value}/{user_id}/">

<!-- BEGIN info_item_tpl -->
<ul>
    <!-- BEGIN info_updated_tpl -->
    <li>{intl-info_update_user}</li>
    <!-- END info_updated_tpl -->
</ul>

<!-- END info_item_tpl -->

<!-- BEGIN errors_item_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_login_tpl -->
    <li>{intl-error_login}</li>
    <!-- END error_login_tpl -->

    <!-- BEGIN error_login_exists_tpl -->
    <li>{intl-error_login_exists}</li>
    <!-- END error_login_exists_tpl -->

    <!-- BEGIN error_first_name_tpl -->
    <li>{intl-error_first_name}</li>
    <!-- END error_first_name_tpl -->

    <!-- BEGIN error_last_name_tpl -->
    <li>{intl-error_last_name}</li>
    <!-- END error_last_name_tpl -->

    <!-- BEGIN error_email_tpl -->
    <li>{intl-error_email}</li>
    <!-- END error_email_tpl -->

    <!-- BEGIN error_email_not_valid_tpl -->
    <li>{intl-error_email_not_valid}</li>
    <!-- END error_email_not_valid_tpl -->

    <!-- BEGIN error_password_too_short_tpl -->
    <li>{intl-error_password_too_short}</li>
    <!-- END error_password_too_short_tpl -->

    <!-- BEGIN error_password_match_tpl -->
    <li>{intl-error_passwordmatch_item}</li>
    <!-- END error_password_match_tpl -->
               	
    <!-- BEGIN error_address_street1_tpl -->
    <li>{intl-error_street1}</li>
    <!-- END error_address_street1_tpl -->

    <!-- BEGIN error_address_street2_tpl -->
    <li>{intl-error_street2}</li>
    <!-- END error_address_street2_tpl -->

    <!-- BEGIN error_address_zip_tpl -->
    <li>{intl-error_zip}</li>
    <!-- END error_address_zip_tpl -->

    <!-- BEGIN error_address_place_tpl -->
    <li>{intl-error_place}</li>
    <!-- END error_address_place_tpl -->

    <!-- BEGIN error_missing_address_tpl -->
    <li>{intl-error_missing_address}</li>
    <!-- END error_missing_address_tpl -->

    <!-- BEGIN error_missing_country_tpl -->
    <li>{intl-error_missing_country}</li>
    <!-- END error_missing_country_tpl -->
</ul>

<!-- END errors_item_tpl -->

<!-- BEGIN edit_user_info_tpl -->

<!--
<span>{intl-edit_usage}</span><span style="position: relative; top: 0px; left: 180px;"><input tabindex="1" class="okbutton" type="submit" name="OK" value="{intl-submit}" /></span><br /><br />
-->

<span>{intl-edit_address_warning}</span>
<!-- END edit_user_info_tpl -->

<style>
/* input.submit */
.stdttsubmit
{
	border: 3px double #999999;	
	border-top-color: #CCCCCC;	
	border-left-color: #CCCCCC;
	padding: 0.25em;
	background-color: #FFFFFF;
	background-image: url('/design/tt/images/bg_input.gif');
	background-repeat: repeat-x;
	color: #333333;
	font-size: 75%;
	font-weight: bold;
	font-family: Verdana, Helvetica, Arial, sans-serif;
}
</style>

<span align="right" style="position: relative; top: 0px; left: 7px;"><a href="{back_url}" tabindex="1" style="font-size:12px;"><< Back</a>&nbsp|&nbsp<a href="{skip_url}" tabindex="2" style="font-size: 12px;">Skip >></a><br /></span>
<br />
<span align="right" style="position: relative; top: 0px; left: 7px; font-size:12px;"><span style="color: red;">{intl-note} :</span>&nbsp;{intl-add_addresss_note}<br /></span><br />



<!-- BEGIN user_preff_tpl -->

<input type="hidden" name="FirstName" value="{first_name_value}" />
<input type="hidden" name="LastName" value="{last_name_value}" />

<input type="hidden" name="Email" value="{email_value}" />
<input type="hidden" name="Password" value="{password_value}" />
<input type="hidden" name="VerifyPassword" value="{password_value}" />

<!-- BEGIN login_item_tpl -->
<!-- <input type="text" size="20" name="Login" value="{login_value}"/></p> -->
<!-- END login_item_tpl -->
<!-- BEGIN disabled_login_item_tpl -->
<!-- {login_value}</p> --> </p>
<!-- END disabled_login_item_tpl -->

<!-- END user_preff_tpl -->

<table border="0" width="95%" style="position: relative; top: -7px;">
<tr>
<td valign="top" colspan="2">

<!-- BEGIN ok_button_tpl -->
<!-- <input class="okbutton" type="submit" name="OK" value="{intl-ok}" /> -->
<!-- END ok_button_tpl -->
<!-- BEGIN submit_button_tpl -->
<!-- <input class="okbutton" type="submit" name="OK" value="{intl-submit}" /> -->
<!-- END submit_button_tpl -->

<!-- BEGIN address_actions_tpl -->

<input tabindex="3" class="stdttsubmit" type="submit" value="{intl-new_address}" name="NewAddress" size="7" style="font-size: 11px;" />

<input tabindex="4" class="stdttsubmit" type="submit" value="{intl-delete_address}" name="DeleteAddress" size="7" style="font-size: 11px;" />

<!-- Original (Plain) Submit Buttons

<input tabindex="3" class="stdbutton" type="submit" value="{intl-new_address}" name="NewAddress" size="7" style="font-size: 11px;" />
<input tabindex="4" class="stdbutton" type="submit" value="{intl-delete_address}" name="DeleteAddress" size="7" style="font-size: 11px;" />

-->

<!-- END address_actions_tpl -->

<input type="hidden" name="userID" value="{user_id}" />
<input type="hidden" name="GlobalSectionIDOverride" value="{global_section_id}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />

</td>
</tr>

<tr>
<!-- BEGIN address_tpl -->
<td valign="top">
<b>{intl-address_number} {address_number}</b> 
<br /><input type="hidden" name="AddressArrayID[]" value="{address_id}">

<!-- BEGIN main_address_tpl -->
<input {is_checked} tabindex="4" type="radio" name="MainAddressID" value="{address_id}"><span class="check">{intl-main_address}</span><br />
<!-- END main_address_tpl -->

<!--

!-- BEGIN shpping_address_tpl --
<input {is_checked} tabindex="5" type="radio" name="ShppingAddressID" value="{address_id}"><span class="check">{intl-shpping_address}</span><br />
!-- END shpping_address_tpl --

!-- BEGIN billing_address_tpl --
<input {is_checked} tabindex="6" type="radio" name="BillingAddressID" value="{address_id}"><span class="check">{intl-billing_address}</span><br />
!-- END billing_address_tpl --

-->

<!-- BEGIN delete_address_tpl -->
<input tabindex="5" type="checkbox" name="DeleteAddressArrayID[]" value="{address_id}">
<span class="check">{intl-delete}</span>
<!-- END delete_address_tpl -->
<input type="hidden" name="AddressID[]" value="{address_id}"/>
<input type="hidden" name="RealAddressID[]" value="{real_address_id}"/>

<p class="boxtext">{intl-street}:</p>
<input tabindex="10" type="text" size="20" name="Street1[]" value="{street1_value}"/><br />
<input tabindex="11" type="text" size="20" name="Street2[]" value="{street2_value}"/>

<p class="boxtext">{intl-place}:</p>
<input tabindex="12" type="text" size="20" name="Place[]" value="{place_value}"/>

<!-- BEGIN region_tpl -->
<p class="boxtext"><a href="#" name="region"></a>{intl-region}:</p>
<select tabindex="13" name="RegionID[]" size="1" style="font-size: 10px;">
<!-- BEGIN region_option_tpl -->
<option {is_selected} value="{region_id}">{region_name}</option>
<!-- END region_option_tpl -->
</select>
<!-- END region_tpl -->

<p class="boxtext">{intl-zip}:</p>
<input tabindex="14" type="text" size="20" name="Zip[]" value="{zip_value}"/>

<!-- BEGIN country_tpl -->
<p class="boxtext">{intl-country}:</p>
<select tabindex="15" name="CountryID[]" size="1" onchange="document.AddressForm.action='{www_dir}{index}/user/userwithaddress/{action_value}/{user_id}/#region'; document.AddressForm.submit()" style="font-size: 10px;">
<!-- BEGIN country_option_tpl -->
<option {is_selected} value="{country_id}">{country_name}</option>
<!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->

  <p class="boxtext">Phone:</p>
  <input tabindex="16" type="text" size="20" name="Phone[]" value="{phone_value}"/>
</td>
<!-- END address_tpl -->
</tr>
</table>

<input tabindex="17" class="stdttsubmit" type="submit" name="OK" value="{intl-submit}" />

<table width="100%" cellpadding="3" cellspacing="2" border="0">
<tr>
<td valign="top">
<!-- Original (Plain) Submit Buttons
<input tabindex="17" class="okbutton" type="submit" name="OK" value="{intl-submit}" />
<input tabindex="17" class="stdttsubmit" type="submit" name="OK" value="{intl-submit}" />
-->

<div align="left" style="position: relative; top: 10px; left: 0px; padding-right: 0px;"><a href="{back_url}" tabindex="20" style="font-size:12px;"><<&nbsp;Back</a>&nbsp|&nbsp<a href="{skip_url}" tabindex="21" style="font-size: 12px;">Skip >></a><br /></div>

<!--
	<span style="float: left; padding-right: 50px; vertical-align: top;"> <span>{intl-edit_usage}</span>&nbsp;<input class="okbutton" type="submit" name="OK" value="{intl-submit}" /></span><br />
	<div align="left" style="position: relative; top: 10px; left: 7px; padding-right: 50px;"><a href="{back_url}" style="font-size:12px;"><<&nbsp;Back</a>&nbsp|&nbsp<a href="{skip_url}" style="font-size: 12px;">Skip >></a><br /></div>
-->

</td>
</tr>
</table>

</form>

</div>
