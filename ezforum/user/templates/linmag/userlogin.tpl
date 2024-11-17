<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<h1>{intl-login_page_title}</h1>
    </td>
</tr>
</table>

<p>{intl-explanation}</p>

<h2>{intl-user_login}</h2>

<p>{intl-reg_text}</p>

<form method="post" action="{www_dir}{index}/user/login/login/">

<p class="boxtext">{intl-username}:</p>
<input type="text" class="halfbox" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" class="halfbox" size="20" name="Password"/><br />
<br />

<input class="okbutton" type="submit" value="{intl-login}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>
<br /><br />

<h2>{intl-new_user}</h2>

<p>{intl-new_text}</p>

<form method="post" action="{www_dir}{index}/user/user/new/?RedirectURL={redirect_url}">

<input class="okbutton" type="submit" value="{intl-newuser}" />

</form>
