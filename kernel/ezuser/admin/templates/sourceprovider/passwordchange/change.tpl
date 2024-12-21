<form method="post" action="{www_dir}{index}/user/passwordchange/{action_value}/">

<h1>Endre passord</h1>

<hr noshade size="4">

<h2>{first_name} {last_name}</h2>

<p class="boxtext">{intl-oldpassword}:</p>
<input type="password" size="20" name="OldPassword"/>

<p class="boxtext">{intl-newpassword}:</p>
<input type="password" size="20" name="NewPassword"/>

<p class="boxtext">{intl-verifypassword}:</p>
<input type="password" size="20" name="VerifyPassword"/>
<br /><br />

<hr noshade size="4">

<input class="okbutton" type="submit" value="OK" />

</form>

