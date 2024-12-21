<form method="post" action="{www_dir}{index}/user/login/login/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-username}:</p>
<input type="text" size="6" name="Username" />
<p class="boxtext">{intl-password}:</p>
<input type="password" size="6" name="Password" />

<br />
<hr noshade="noshade" size="4">
<input class="okbutton" type="submit" value="{intl-ok}">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>

<input type="hidden" name="RedirectURL" value="{redirect_url}">
</table>
</form>
