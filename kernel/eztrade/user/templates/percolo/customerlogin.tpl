        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><a href="{www_dir}{index}/tema/bildegalleri"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="Bygg mer enn hus..." width="140" height="100" border="0" /></a><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">Kunderegistrering</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		<tr>
		    <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td colspan="2">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">

<h2>{intl-new_customer}</h2>

<p>{intl-new_text}</p>

<form method="post" action="{www_dir}{index}/user/userwithaddress/new/?RedirectURL={redirect_url}">

<input class="okbutton" class="stdbutton" type="submit" value="{intl-newuser}" />

</form>

	</td>
	<td align="right" valign="top">
<form method="post" action="{www_dir}{index}/user/forgot/">

<h2>Glemt passord?</h2>

<p>Hvis du er registrert som kunde, men har glemt ditt passord så kan du få et nytt her.</p>

<input class="okbutton" type="submit" value="Nytt passord" />

</form>
	</td>
</tr>
</table>


<br /><br /><br />

<h2>{intl-customer_login}</h2>

<p>{intl-reg_text} Etter at du er logget inn kan du endre dine kundedata fra menyen på venstre side.</p>

<form method="post" action="{www_dir}{index}/user/login/login/">
<p class="boxtext">Brukernavn:</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" size="20" name="Password"/><br />
<br />

<input class="okbutton" type="submit" value="Logg inn" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

<br />

</td>
</tr>
</table>
