<form method="post" action="{www_dir}{index}/user/login/login/">

<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_login}</h3>
<!-- END error_message_tpl -->

<!-- BEGIN max_message_tpl -->
<h3 class="error">{intl-max_logins}</h3>
<!-- END max_message_tpl -->

<p class="boxtext">{intl-username}:</p>
<input type="text" class="halfbox" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" class="halfbox" size="20" name="Password"/>

<input type="hidden" name="RefererURL" value="{referer_url}" />

<br /><br />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}" />

</form>
