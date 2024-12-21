	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="menuhead" bgcolor="#323296">{intl-head_line}</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<form method="post" action="{www_dir}{index}/user/login/login/">
		<p class="smalltitle">{intl-username}:</p>
		<input type="text" size="6" name="Username"/>
		</td>
	</tr>
	<tr>
		<td>
		<p class="smalltitle">{intl-password}:</p>
		<input type="password" size="6" name="Password" />
	</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
				<input type="submit" value="{intl-ok}">
		</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<img src="{www_dir}/images/path-arrow.gif" width="15" height="10" /><a class="menu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a><br />
		<img src="{www_dir}/images/path-arrow.gif" width="15" height="10" /><a class="menu" href="{www_dir}{index}{user_edit_url}">Ny kunde</a>
		<input type="hidden" name="RedirectURL" value="{redirect_url}">

		</form>

		</td>
	</tr>
	<tr>
		<td>
		</td>
	</tr>
	
	</table>
	<br />	