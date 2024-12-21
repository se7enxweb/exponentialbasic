<h1>{intl-template_edit}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/bulkmail/templateedit/{template_id}" method="post">

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{template_name}" />
<br />

<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" cols="40" rows="5" wrap="soft">
{description}
</textarea>
<br />

<p class="boxtext">{intl-header}:</p>
<textarea name="Header" class="box" cols="40" rows="6" wrap="soft">
{template_header}
</textarea>
<br />

<p class="boxtext">{intl-footer}:</p>
<textarea name="Footer" class="box" cols="40" rows="6" wrap="soft">
{template_footer}
</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>