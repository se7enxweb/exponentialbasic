<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; 
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">

<form method="post" action="{www_dir}{index}/user/login/login/">

<h1 class="mainHeading">{intl-head_line}</h1>

<br />

<p class="boxtext">{intl-username}:</p>
<input tabindex="1" type="text" size="12" name="Username"/>
<br />

<p class="boxtext">{intl-password}:</p>
<input tabindex="2" type="password" size="12" name="Password" />
<br />
<br />
<hr noshade="noshade" size="4">

<!-- BEGIN buttons_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input tabindex="4" class="stdbutton" type="submit" Name="Forgot" value="{intl-forgot}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input tabindex="5" class="stdbutton" type="submit" Name="Register" value="{intl-register}">
	</td>
</tr>
</table>

<hr noshade="noshade" size="4">
<!-- END buttons_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>

<tr>
	<td>
	<input tabindex="3" class="okbutton" type="submit" value="{intl-ok}">
	</td>
</tr>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</table>
</form>
</div>