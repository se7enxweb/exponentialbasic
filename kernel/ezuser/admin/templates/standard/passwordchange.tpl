<form method="post" action="{www_dir}{index}/user/passwordchange/{action_value}/">
<h1>{intl-headline}</h1>

<hr noshade size="4">

<p class="error">{error_msg}</p>

<h2>{first_name} {last_name}</h2>

<p class="boxtext">{intl-oldpassword}:</p>
<input type="password" size="20" name="OldPassword"/>
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-newpassword}:</p>
	<input type="password" size="20" name="NewPassword"/>
	</td>
	<td>
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword"/>
	</td>
</tr>
</table>

<br />
<hr noshade size="4">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>	
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-abort}" />
	</td>
</tr>
</table>
</form>			
