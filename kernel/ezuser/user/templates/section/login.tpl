<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<form method="post" action="{www_dir}{index}/user/login/login/">

<h1>{intl-head_line}</h1>

<p class="boxtext">{intl-username}:</p>
<input tabindex="1" type="text" size="6" name="Username"/>
<br />

<p class="boxtext">{intl-password}:</p>
<input tabindex="2" type="password" size="6" name="Password" />
<br />
<br />

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

<!-- END buttons_tpl -->
<input tabindex="3" class="okbutton" type="submit" value="{intl-ok}">

<input type="hidden" name="RedirectURL" value="{redirect_url}">

</form>
