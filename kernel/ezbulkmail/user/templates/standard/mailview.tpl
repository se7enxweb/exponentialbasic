<h1>{intl-mail_preview}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/bulkmail/bulklist/{category_id}/" >

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

<input type="submit" name="Back" value="{intl-back}" />

</form>