<h1>{intl-subscription_login}</h1>

<hr noshade="noshade" size="4" />
<!-- BEGIN error_message_tpl -->
<h3 class="error">{error_message}</h3>
<!-- END error_message_tpl -->

<form action="{www_dir}{index}/bulkmail/login" method="post">

<p class="boxtext">{intl-email}:</p>
<input type="text" name="Email">

<p class="boxtext">{intl-password}:</p>
<input type="password" name="Password">

<!-- BEGIN second_password_tpl -->
<p class="boxtext">{intl-password_confirm}:</p>
<input type="password" name="Password2">
<!-- END second_password_tpl -->

<!-- BEGIN new_tpl -->
<br /><br />
<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/bulkmail/newsubscription">{intl-new_address}</a>
<!-- END new_tpl -->

<!-- BEGIN login_tpl -->
<br /><br />
<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/bulkmail/login">{intl-normal_login}</a>
<!-- END login_tpl -->

<br /><br />
<hr noshade="noshade" size="4" />

<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="okbutton" name="Ok" value="{intl-ok}" /></td>
</tr>
</table>

</form>