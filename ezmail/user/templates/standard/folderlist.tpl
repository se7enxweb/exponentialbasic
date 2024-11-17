<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-folderlist}</h1>
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

<form method="post" action="{www_dir}{index}/mail/folderlist/" enctype="multipart/form-data" >
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="88%">{intl-name}:</th>
	<th width="5%">{intl-unread_mail}:</th>
	<th width="5%">{intl-total_mail}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN folders_item_tpl -->
<tr>
	<td class="{td_class}">
	{indent}<a href="{www_dir}{index}/mail/folder/{folder_id}">{folder_name}</a>
	</td>
	<td class="{td_class}" align="right">
        {folder_unread_mail_total}
	</td>        	
	<td class="{td_class}" align="right">
        {folder_mail_total}
	</td>        	
	<!-- BEGIN folders_item_edit_tpl -->
	<td class="{td_class}">
	  <a href="{www_dir}{index}/mail/folderedit/{folder_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{folder_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{folder_id}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="FolderArrayID[]" value="{folder_id}" />
	</td>
	<!-- END folders_item_edit_tpl -->
	<!-- BEGIN edit_empty_tpl -->
	<td class="{td_class}">&nbsp;</td>
	<td class="{td_class}">&nbsp;</td>
	<!-- END edit_empty_tpl -->

</tr>
<!-- END folders_item_tpl -->

</table>

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  	<td><input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" /></td>
	<td>&nbsp;</td>
	<td><input class="stdbutton" type="submit" name="EmptyTrash" value="{intl-empty_trash}" /></td>
</tr>
</table>

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