<form method="post" action="{www_dir}{index}/user/login/logout/">
<table width="100%" cellspacing="5" cellpadding="1" border="0">
<tr>
	<td colspan="2" class="menubold">
	{intl-userlogin}:
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	{first_name} {last_name}
	</td>
</tr>
<tr>
	<td colspan="2">
	<input class="stdbutton" type="submit" value="{intl-logout}" />
	</td>
</tr>
<tr>
	<td colspan="2" valign="top">&nbsp;&#149&nbsp;<a class="menu" href="{www_dir}{index}{user_edit_url}/{user_id}/">{intl-change_user_info}</a></td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

