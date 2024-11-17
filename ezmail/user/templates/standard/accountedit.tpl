<h1>{intl-configure_account}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/mail/accountedit/{current_account_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-login}:</p>
<input type="text" size="40" name="Login" value="{login_value}"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" size="40" name="Password" value="{password_value}"/>

<br /><br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td>
    <p class="boxtext">{intl-server}:</p>
    <input type="text" size="40" name="Server" value="{server_value}"/>
  </td>
  <td>&nbsp;</td>
  <td>
    <p class="boxtext">{intl-port}:</p>
    <input type="text" size="10" name="Port" value="{port_value}"/>
  </td>
</tr>
</table>

<br />
<div class="check"><input type="checkbox" name="DelFromServer" {delete_from_server_checked} />&nbsp;{intl-delete_from_server}</div>

<br />
<hr noshade="noshade" size="4">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>

</form>