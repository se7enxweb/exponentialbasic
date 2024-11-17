<form method="post" action="/user/login/login/">

<h1>{intl-head_line}</h1>


<p class="boxtext">{intl-username}:</p>
<input tabindex="1" type="text" size="10" class="halfbox" name="Username"/>
<br />

<p class="boxtext">{intl-password}:</p>
<input tabindex="2" type="password" size="20" class="halfbox" name="Password" />
<br />
<br />

<!-- BEGIN buttons_tpl -->

<!-- END buttons_tpl -->


<input tabindex="3" class="okbutton" type="submit" value="  {intl-ok}  ">&nbsp;	

<input tabindex="4" class="okbutton" type="submit" Name="Forgot" value="{intl-forgot}">

<input type="hidden" name="RedirectURL" value="{redirect_url}">

</form>
