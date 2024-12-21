<h1>{intl-mailedit}</h1>
<!-- BEGIN error_message_tpl -->
<h3 class="error">{mail_error_message}</h3>
<!-- END error_message_tpl -->

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/mail/mailedit/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-to}:</p>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
    <input class="box" type="text" size="40" name="To" value="{to_value}" />
  </td>
  <td>
    <input class="stdbutton" type="submit" name="ToButton" value="{intl-to}" />
  </td>
</tr>
</table>
<input type="hidden" name="IDList" value="{id_value}" />
<input type="hidden" name="CompanyList" value="{company_value}" />
<p class="boxtext">{intl-from}:</p>
<input class="box" type="text" size="40" name="From" value="{from_value}" />

<!-- BEGIN cc_single_tpl -->
<p class="boxtext">{intl-cc}:</p>
<input class="box" type="text" size="40" name="Cc" value="{cc_value}" />
<!-- END cc_single_tpl -->

<!-- BEGIN bcc_single_tpl -->
<p class="boxtext">{intl-bcc}:</p>
<input class="box" type="text" size="40" name="Bcc" value="{bcc_value}" />
<!-- END bcc_single_tpl -->

<br /><br />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
    <input class="stdbutton" type="submit" name="CcButton" value="{intl-cc}:" />
  </td>
  <td>&nbsp;</td>
  <td>
    <input class="stdbutton" type="submit" name="BccButton" value="{intl-bcc}:" />
  </td>
</tr>
</table>


<p class="boxtext">{intl-subject}:</p>
<input class="box" type="text" size="40" name="Subject" value="{subject_value}" />

<p class="boxtext">{intl-body}:</p>
<textarea class="box" name="MailBody" cols="40" rows="20" wrap="soft">{mail_body}</textarea>
<br /><br />


<!-- BEGIN inserted_attachments_tpl -->
<h2>{intl-attachments}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>{intl-file_name}:</th>
        <th>{intl-file_size}:</th>
        <th>&nbsp;</th>
</tr>
 
<!-- BEGIN attachment_tpl -->
<tr>
        <td width="89%" class="{td_class}">
        {file_name}
        </td>
        <td width="10%" class="{td_class}">
        {file_size}
        </td>
        <td width="1%" class="{td_class}">
        <input type="checkbox" name="AttachmentArrayID[]" value="{file_id}" />
        </td>
</tr>
<!-- END attachment_tpl -->
 
</table>
<!-- END inserted_attachments_tpl --> 


<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="Preview" value="{intl-preview}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Signature" value="{intl-signature}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="AddAttachment" value="{intl-add_attachment}" /></td>
  <!-- BEGIN attachment_delete_tpl -->
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="DeleteAttachments" value="{intl-delete_attachments}" /></td>
  <!-- END attachment_delete_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Send" value="{intl-send}" /></td>
  <td>&nbsp;</td>  
  <td><input class="okbutton" type="submit" Name="Save" value="{intl-save}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>