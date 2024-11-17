<h1>{intl-mailedit}</h1>

<hr noshade="noshade" size="4">

<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-select_categories}</h3>
<hr noshade="noshade" size="4">
<!-- END error_message_tpl -->

<form method="post" action="{www_dir}{index}/bulkmail/mailedit/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-subject}:</p>
<input type="text" class="box" size="40" name="Subject" value="{subject_value}"/>

<br /><br />

<table width="100%" cellspace="0" cellpadding="0" border="0">
<tr>
  <td width="50%">
    <p class="boxtext">{intl-category_select}:</p>
    <select multiple size="4" name="CategoryArrayID[]">
    <!-- BEGIN multiple_value_tpl -->
    <option value="{category_id}" {multiple_selected}>{category_name}</option>
    <!-- END multiple_value_tpl -->
    </select>
  </td>

  <td width="50%" valign="top">
  <p class="boxtext">{intl-template_select}:</p>
  <select name="TemplateID">
  <option value="-1" >{intl-no_template}</option>
  <!-- BEGIN template_item_tpl -->
  <option value="{template_id}" {selected}>{template_name}</option>
  <!-- END template_item_tpl -->
  </select>
  </td>
</tr>
</table>

<br /><br />

<p class="boxtext">{intl-from}:</p>
<input type="text" class="box" size="40" name="From" value="{from_value}"/>

<p class="boxtext">{intl-from_name}:</p>
<input type="text" class="box" size="40" name="FromName" value="{from_name_value}"/>

<p class="boxtext">{intl-body}:</p>
<textarea name="MailBody" class="box" cols="40" rows="20" wrap="soft">{mail_body}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="Preview" value="{intl-preview}" />

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
