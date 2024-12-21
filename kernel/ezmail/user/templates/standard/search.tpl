<form action="{www_dir}{index}/mail/search/" method="post" enctype="multipart/form-data">
<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-search_headline} - '{search_string}'</h1>
        </td>
              <td rowspan="2" align="right">  
              <input type="text" name="SearchText" size="12" value="{search_string}" />
              <input class="stdbutton" type="submit" value="{intl-search}" />
        </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th widht="1%">&nbsp;</th>
	<th width="40%">{intl-subject}:</th>
	<th width="26%">{intl-sender}:</th>
	<th width="31%">{intl-folder}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN mail_line_tpl -->
<!-- BEGIN mail_item_tpl -->
<tr> 
	<td class="{td_class}">
	<!-- BEGIN mail_unread_tpl -->
	<img src="{www_dir}/images/mail.gif" />
	<!-- END mail_unread_tpl -->

	<!-- BEGIN mail_read_tpl -->
	<img src="{www_dir}/images/mail_read.gif" />
	<!-- END mail_read_tpl -->

	<!-- BEGIN mail_forwarded_tpl -->
	<img src="{www_dir}/images/mail_forwarded.gif" />
	<!-- END mail_forwarded_tpl -->

	<!-- BEGIN mail_replied_tpl -->
	<img src="{www_dir}/images/mail_replied.gif" />
	<!-- END mail_replied_tpl -->

	<!-- BEGIN mail_repliedall_tpl -->
	<img src="{www_dir}/images/mail_repliedtoall.gif" />
	<!-- END mail_repliedall_tpl -->
	
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/mail/view/{mail_id}">{mail_subject}</a>
	</td>
	<td class="{td_class}">
	{mail_sender}
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/mail/folder/{mail_folder_id}">{mail_folder}</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN mail_edit_item_tpl -->
	  <a href="{www_dir}{index}/mail/mailedit/{mail_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{mail_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
          <img name="ezb{mail_id}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	<!-- END mail_edit_item_tpl -->
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_tpl -->
<!-- BEGIN mail_item_unread_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{www_dir}/images/mail.gif" />
	</td>
	<td class="{td_class}">
	<b><a href="{www_dir}{index}/mail/view/{mail_id}">{mail_subject}</a></b>
	</td>
	<td class="{td_class}">
	<b>{mail_sender}</b>
	</td>
	<td class="{td_class}">
	<b><a href="{www_dir}{index}/mail/folder/{mail_folder_id}">{mail_folder}</a></b>
	</td>
	<td class="{td_class}">
	&nbsp;
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_unread_tpl -->
<!-- END mail_line_tpl -->
</table>

<hr noshade="noshade" size="4" />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="Move" value="{intl-move}:" /></td>
  <td>&nbsp;</td>
  <td>
    <select name="FolderSelectID">
        <option value="-1">{intl-choose_dest}</option>
    	<!-- BEGIN folder_item_tpl -->
	<option value="{folder_id}">{folder_name}</option>
	<!-- END folder_item_tpl -->
    </select>
  </td>
</tr>
</table>

</form>
