<table width="100%" cellspacing="0" cellpadding="2" border="0">
<form method="post" action="{www_dir}{index}/user/login/login/">
<tr>
	<td colspan="2" class="menuhead">{intl-head_line}</td>
</tr>
<tr>
	<td colspan="2" class="menubold">
	{intl-username}:
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	<input type="text" size="6" name="Username"/>
	</td>
</tr>
<tr>
	<td colspan="2" class="menubold">
	{intl-password}:
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	<input type="password" size="6" name="Password" />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="submit" value="{intl-ok}">
	</td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a></td>
</tr>
<!-- BEGIN standard_creation_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}{user_edit_url}">Registrer ny kunde</a></td>
</tr>
<!-- END standard_creation_tpl -->
<!-- BEGIN extra_creation_tpl -->
{extra_userbox}
<!-- END extra_creation_tpl -->
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>
</table>

