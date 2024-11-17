<form method="post" action="{www_dir}{index}/mail/fileedit/{mail_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-attachment_upload}: {mail_subject}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-file}:</p>
<input size="40" name="userfile" type="file" />

<br /><br />
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" name="Ok" type="submit" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" name="Cancel" type="submit" value="{intl-cancel}" /></td>
</tr>
</table>

</form>
