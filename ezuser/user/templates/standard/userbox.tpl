<form method="post" action="{www_dir}{index}/user/login/logout/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-userinfo}</td>
</tr>
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
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}{user_edit_url}/{user_id}/">{intl-change_user_info}</a></td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

