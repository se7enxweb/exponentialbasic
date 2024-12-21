<h1>{intl-login_page_title}</h1>

<hr noshade="noshade" size="4" />

<p>{intl-reg_text}</p>

<form method="post" action="{www_dir}{index}/user/login/login/">

<p class="boxtext">{intl-username}:</p>
<input type="text" class="halfbox" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" class="halfbox" size="20" name="Password"/><br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-login}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

</form>
