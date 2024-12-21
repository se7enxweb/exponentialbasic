<form method="post" action="{www_dir}{index}/user/login/login/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="{www_dir}/sitedesign/designsection1/images/login.gif" width="122" height="20"><br />
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenuboxtext">
	{intl-username}:
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	<input type="text" size="4" name="Username" style="width:100px" />
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenuboxtext">
	{intl-password}:
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	<input type="password" size="4" name="Password" style="width:100px" />
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	<input class="stdbutton" type="submit" value="Log in">
	</div>
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenu">
	<a class="rightmenu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a>
	</div></td>
</tr>
<!-- BEGIN standard_creation_tpl -->
<tr>
	<td>
	<div class="rightmenu">
	<a class="rightmenu" href="{www_dir}{index}{user_edit_url}">{intl-register}</a>
	</div>
	</td>
</tr>
<!-- END standard_creation_tpl -->
<!-- BEGIN extra_creation_tpl -->
{extra_userbox}
<!-- END extra_creation_tpl -->
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

