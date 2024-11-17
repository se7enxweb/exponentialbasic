<h1>{intl-mail_preview}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/bulkmail/preview/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-subject}:</p>
<div class="p">{subject}</div>

<p class="boxtext">{intl-category}:</p>
<div class="p">{category}</div>

<p class="boxtext">{intl-from}:</p>
<div class="p">{from}</div>
<br />

<table width="100%" cellpadding="4" cellspacing="0" border="0">
<tr>
  <td class="bglight">
  {mail_body}
  </td>
<tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspace="0" cellpadding="0" border="0">
<tr>
  <!-- BEGIN send_button_tpl -->
  <td><input class="okbutton" type="submit" Name="Send" value="{intl-send}" /></td>
  <td>&nbsp;</td>
  <!-- END send_button_tpl -->
  <!-- BEGIN edit_button_tpl -->
  <td><input class="okbutton" type="submit" Name="Edit" value="{intl-edit}" /></td>
  <!-- END edit_button_tpl -->
</tr>
</table>
</form>