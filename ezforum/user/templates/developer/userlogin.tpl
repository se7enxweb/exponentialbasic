<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<h1>{intl-login_page_title}</h1>
    </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<p>{intl-explanation}</p>

<hr noshade="noshade" size="4" />

<h2>{intl-user_login}</h2>

<p>{intl-reg_text}</p>

<form method="post" action="{www_dir}{index}/user/login/login/">
<p class="boxtext">{intl-username}:</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" size="20" name="Password"/><br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-login}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>
<br />

<h2>{intl-new_user}</h2>

<p>{intl-new_text}</p>

<form method="post" action="{www_dir}{index}/user/user/new/?RedirectURL={redirect_url}">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-newuser}" />

</form>
