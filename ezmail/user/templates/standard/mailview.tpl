<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-readmail}</h1>
        </td>
              <td rowspan="2" align="right">  
              <form action="{www_dir}{index}/mail/search/" method="post">
              <input type="text" name="SearchText" size="12" />
              <input class="stdbutton" type="submit" value="{intl-search}" />
              </form>
        </td>
</tr>
</table>

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/mail/view/{current_mail_id}" enctype="multipart/form-data" >

<p>{intl-to}: {to}</p>
<p>{intl-from}: {from}</p>
<p>{intl-date}: {date}</p>
<!-- BEGIN cc_value_tpl -->
<p>{intl-cc}: {cc}</p>
<!-- END cc_value_tpl -->

<!-- BEGIN bcc_value_tpl -->
<p>{intl-bcc}: {bcc}</p>
<!-- END bcc_value_tpl -->

<p>{intl-subject}: {subject}</p>
<table width="100%">
<tr>
  <td class="bgdark">
  {mail_body}
  </td>
<tr>
</table>

<!-- BEGIN inserted_attachments_tpl -->
<h2>{intl-attachments}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>{intl-file_name}:</th>
        <th>{intl-file_size}:</th>
</tr>
 
<!-- BEGIN attachment_tpl -->
<tr>
        <td width="90%" class="{td_class}">
        {file_name}
        </td>
        <td width="10%" class="{td_class}">
        {file_size}
        </td>
</tr>
<!-- END attachment_tpl -->
 
</table>
<!-- END inserted_attachments_tpl --> 

<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="Reply" value="{intl-reply}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="ReplyAll" value="{intl-replyall}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Forward" value="{intl-forward}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Delete" value="{intl-delete}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Link" value="{intl-link}" /></td>
</tr>
</table>
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" />
</form>
