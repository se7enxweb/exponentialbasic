<form method="post" action="{www_dir}{index}/user/login/login/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
        <td class="menuhead" bgcolor="#c82828">{intl-head_line}</td>
</tr>
<tr>
        <td class="menutext">
        <p class="menutext">{intl-username}:</p>
        <input type="text" size="6" name="Username"/>
        </td>
</tr>
<tr>
        <td class="menutext">
        <p class="menutext">{intl-password}:</p>
        <input type="password" size="6" name="Password" />
        </td>
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
        <td class="menutext">
        <img src="{www_dir}/images/dot.gif" width="12" height="10"><a class="menu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a>
        </td>
</tr>
<tr>
        <td class="menutext">
        <img src="{www_dir}/images/dot.gif" width="12" height="10"><a class="menu" href="{www_dir}{index}/user/user/new/">{intl-register}
        </td>
</tr>
<tr>
        <td class="menuspacer">&nbsp;</td>
</tr>
</table>

<input type="hidden" name="RedirectURL" value="{redirect_url}">

</form>