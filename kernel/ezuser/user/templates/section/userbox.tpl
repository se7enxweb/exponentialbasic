<form method="post" action="{www_dir}{index}/user/login/logout/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="{www_dir}/sitedesign/designsection1/images/user.gif" width="122" height="20"><br />
	<img src="{www_dir}/images/1x1.gif" width="1" height="5"><br />
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	{intl-userlogin}:
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenutext">
	{first_name} {last_name}
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	<input class="stdbutton" type="submit" value="{intl-logout}" />
	</div>
	</td>
</tr>
<tr>
	<td><div class="rightmenu"><a class="rightmenu" href="{www_dir}{index}{user_edit_url}/{user_id}/">{intl-change_user_info}</a></div></td>
</tr>
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

