<form method="post" action="{www_dir}{index}/user/login/logout/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead" bgcolor="#c0c0c0">Brukerinfo</td>
</tr>
<tr>
	<td>
	<div class="smallbold">{intl-userlogin}:</div>
	<div class="small">{first_name} {last_name}</div>
	</td>
</tr>
<tr>
	<td>
	<input type="submit" value="{intl-logout}" />
	</td>
</tr>
<tr>
	<td class="menutext">
	<img src="{www_dir}/images/dot.gif" width="12" height="10"><a class="menu" href="{www_dir}{index}{user_edit_url}/{user_id}/">{intl-change_user_info}</a>  
	</td>
</tr>
</table>

</form>
