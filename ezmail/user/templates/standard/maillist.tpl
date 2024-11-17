<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-mail} - {current_folder_name}</h1>
        </td>
              <td rowspan="2" align="right">  
              <form action="{www_dir}{index}/mail/search/" method="post">
              <input type="text" name="SearchText" size="12" />
              <input class="stdbutton" type="submit" value="{intl-search}" />
              </form>
        </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/mail/folder/{current_folder_id}" enctype="multipart/form-data" >
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th widht="1%">&nbsp;</th>
	<th width="40%"><a href="{www_dir}{index}/mail/foldersort/{current_folder_id}/subject">{intl-subject}:</a></th>
	<th width="26%"><a href="{www_dir}{index}/mail/foldersort/{current_folder_id}/from">{intl-sender}:</a></th>
	<th width="7%"><a href="{www_dir}{index}/mail/foldersort/{current_folder_id}/size">{intl-size}:</a></th>
	<th width="24%"><a href="{www_dir}{index}/mail/foldersort/{current_folder_id}/date">{intl-date}:</a></th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
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
	
	<!-- BEGIN mail_status_renderer_tpl -->
	<!-- END mail_status_renderer_tpl -->
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/mail/view/{mail_id}">{mail_subject}</a>
	</td>
	<td class="{td_class}">
	{mail_sender}
	</td>
	<td class="{td_class}">
	{mail_size}
	</td>
	<td class="{td_class}">
	<span class="small">{mail_date}</span>
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
	<b>{mail_size}</b>
	</td>
	<td class="{td_class}">
	<b><span class="small">{mail_date}</span></b>
	</td>
	<td class="{td_class}">
	&nbsp;
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_unread_tpl -->

<!-- BEGIN mail_render_tpl -->

<!-- END mail_render_tpl -->


</table>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/mail/folder/{current_folder_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/mail/folder/{current_folder_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/mail/folder/{current_folder_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="NewFolder" value="{intl-new_folder}" /></td>
  <td>&nbsp;</td>
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
<hr noshade="noshade" size="4" />
<form action="{www_dir}{index}/mail/folder/{current_folder_id}/" method="post">
<select name="NumMessages">
<!-- BEGIN num_mail_element_tpl -->
<option value="{messages_number}" {is_selected} />{messages_number}</option>
<!-- END num_mail_element_tpl -->
</select>
<input type="submit" value="{intl-update}" />
</form>


