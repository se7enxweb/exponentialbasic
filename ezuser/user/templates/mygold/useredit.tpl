<form method="post" action="{www_dir}{index}/user/user/{action_value}/{user_id}/">

<h1>{head_line}</h1>

<hr noshade size="1" />

<!-- BEGIN required_fields_error_tpl -->
<h3 class="error" >{intl-required_fields_error}</h3>
<!-- END required_fields_error_tpl -->

<!-- BEGIN user_exists_error_tpl -->
<h3 class="error" >{intl-user_exists_error}</h3>
<!-- END user_exists_error_tpl -->

<!-- BEGIN password_error_tpl -->
<h3 class="error" >{intl-password_error}</h3>
<!-- END password_error_tpl -->

<!-- BEGIN email_error_tpl -->
<h3 class="error" >{intl-email_error}</h3>
<!-- END email_error_tpl -->

<br />

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
<input tabindex="3" type="text" {read_only} size="20" name="Login" value="{login_value}"/>

<p class="boxtext">{intl-email}:</p>
<input tabindex="4" type="text" size="20" name="Email" value="{email_value}"/>

<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
</table>
<p class="boxtext">{intl-infosubscription}:</p>
<input {info_subscription} type="checkbox" name="InfoSubscription" /
<br /><br />

<hr noshade size="1" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="UserID" value="{user_id}" />
	<input class="okbutton" type="submit" value="OK" />
	<input type="hidden" name="RedirectURL" value="{redirect_url}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="{www_dir}{index}/">
	<input class="okbutton" type="submit" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>
