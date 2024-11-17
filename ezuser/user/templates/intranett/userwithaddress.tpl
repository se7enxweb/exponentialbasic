<form method="post" action="{www_dir}{index}/user/userwithaddress/{action_value}/{user_id}/">

<!-- BEGIN new_user_tpl -->
<h1>{intl-head_line}</h1>
<!-- END new_user_tpl -->
<!-- BEGIN edit_user_tpl -->
<h1>{intl-edit_head_line}</h1>
<!-- END edit_user_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN info_item_tpl -->
<ul>
    <!-- BEGIN info_updated_tpl -->
    <li>{intl-info_update_user}
    <!-- END info_updated_tpl -->
</ul>

<hr noshade size="4"/>

<br />
<!-- END info_item_tpl -->

<!-- BEGIN required_fields_error_tpl -->
<h3 class="error" >{intl-required_fields_error}</h3>
<!-- END required_fields_error_tpl -->

<!-- BEGIN user_exists_error_tpl -->
<h3 class="error" >{intl-user_exists_error}</h3>
<!-- END user_exists_error_tpl -->

<!-- BEGIN password_error_tpl -->
<h3 class="error" >{intl-password_error}</h3>
<!-- END password_error_tpl -->

<br />

<!-- BEGIN edit_user_info_tpl -->
<h3>{intl-edit_usage}</h3>
<!-- END edit_user_info_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-firstname}:</p>
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-lastname}:</p>
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-login}:</p>
<!-- BEGIN login_item_tpl -->
<input type="text" size="20" name="Login" value="{login_value}"/>
<!-- END login_item_tpl -->
<!-- BEGIN disabled_login_item_tpl -->
{login_value}<br />
<!-- END disabled_login_item_tpl -->

<p class="boxtext">{intl-email}:</p>
<input type="text" size="20" name="Email" value="{email_value}"/>

<p class="boxtext">{intl-street1}:</p>
<input type="text" size="20" name="Street1" value="{street1_value}"/>

<p class="boxtext">{intl-street2}:</p>
<input type="text" size="20" name="Street2" value="{street2_value}"/>

<p class="boxtext">{intl-zip}:</p>
<input type="text" size="20" name="Zip" value="{zip_value}"/>

<p class="boxtext">{intl-place}:</p>
<input type="text" size="20" name="Place" value="{place_value}"/>

<!-- BEGIN country_tpl -->
<p class="boxtext">{intl-country}:</p>
<select name="CountryID" size="5">
<!-- BEGIN country_option_tpl -->
<option {is_selected} value="{country_id}">{country_name}</option>
<!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->



<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
</table>

<br />

<hr noshade="noshade" size="4" />

<input type="hidden" name="AddressID" value="{address_id}">
<input type="hidden" name="UserID" value="{user_id}" />
<!-- BEGIN ok_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<!-- END ok_button_tpl -->
<!-- BEGIN submit_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="{intl-submit}" />
<!-- END submit_button_tpl -->

<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>



