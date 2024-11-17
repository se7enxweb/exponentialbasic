<form method="post" action="{www_dir}{index}/user/login/login/">
<table width="100%" cellspacing="5" cellpadding="1" border="0">
<tr>
	<td colspan="2" class="menubold">
	{intl-username}:
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	<input type="text" size="6" name="Username" style="width:100px" />
	</td>
</tr>
<tr>
	<td colspan="2" class="menubold">
	{intl-password}:
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	<input type="password" size="6" name="Password" style="width:100px" />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input class="stdbutton" type="submit" value="{intl-ok}">
	</td>
</tr>
<tr>
	<td colspan="2" valign="top">&nbsp;&#149&nbsp;<a class="menu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a></td>
</tr>
<!-- BEGIN standard_creation_tpl -->
<tr>
	<td colspan="2" valign="top">&nbsp;&#149&nbsp;<a class="menu" href="{www_dir}{index}{user_edit_url}">{intl-register}</a></td>
</tr>
<!-- END standard_creation_tpl -->
<!-- BEGIN extra_creation_tpl -->
{extra_userbox}
<!-- END extra_creation_tpl -->
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

