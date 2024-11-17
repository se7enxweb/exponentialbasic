<form method="post" action="{www_dir}{index}/user/useredit/{action_value}/{user_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
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

<p class="boxtext">{intl-email}:</p>
<input type="text" size="40" name="Email" value="{email_value}"/>

<p class="boxtext">{intl-login}:</p>
<input type="text" {read_only} size="20" name="Login" value="{login_value}"/>

<p class="boxtext">{intl-groups}:</p>
<select name="GroupArray[]" multiple size="5">
{group_item}
</select>

<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
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

<hr noshade size="4"/>

<input type="hidden" name="UserID" value="{user_id}" />
<input class="okbutton" type="submit" value="OK" />
<form method="post" action="{www_dir}{index}/user/userlist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>

