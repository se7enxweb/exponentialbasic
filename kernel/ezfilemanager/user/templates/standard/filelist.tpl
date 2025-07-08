<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-files}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/filemanager/search/" method="get">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/filemanager/new/" enctype="multipart/form-data">
<!-- BEGIN current_folder_tpl -->
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
   <td>
	<img src="{www_dir}/ezfilemanager/user/{image_dir}/folder.gif" alt="" width="16" height="16" border="0" />&nbsp;<a href="{www_dir}{index}/filemanager/list/{folder_id}/">{folder_name}</a><br />
   </td>
   <td>
   <p>
   {current_folder_description}
   </p>
   </td>
</table>
-->
<!-- END current_folder_tpl -->

<hr noshade="noshade" size="4" />

<img src="{www_dir}/design/admin/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/filemanager/list/0/">{intl-file_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/filemanager/list/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_folder_description}</div></div>
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN parent_folder_tpl -->
<tr>
<td class="{td_class_parent}" width="%1">
<img src="{www_dir}/design/admin/images/folder.gif" alt="" width="16" height="16" border="0" />
</td>
<td class="{td_class_parent}" colspan="3" width="99%">
<a href="{www_dir}{index}/filemanager/list/{parent_folder_id}/">..</a><br />
</td>
</tr>
<!-- END parent_folder_tpl -->
<!-- BEGIN folder_list_tpl -->
<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="{www_dir}/design/admin/images/folder.gif" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="98%">
	<a href="{www_dir}{index}/filemanager/list/{folder_id}/">{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
        <!-- BEGIN folder_write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/folder/edit/{folder_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{folder_id}-red','','{www_dir}/images/redigerminimrk.gif',1)"><img name="ezf{folder_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="FolderArrayID[]" value="{folder_id}">
	</td>
        <!-- END folder_write_tpl -->
</tr>
<!-- END folder_tpl -->
<!-- END folder_list_tpl -->
</table>

<!-- BEGIN file_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!--
<tr>
    <th>&nbsp;</th>
    <th>{intl-name}:</th>
    <th>{intl-size}:</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
-->
<!-- BEGIN file_tpl -->
<tr>
	<!-- BEGIN read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="{www_dir}/design/admin/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<span class="small"><a href="{www_dir}{index}/filemanager/fileview/{file_id}/" class="small" title="{file_description}">{original_file_name}</a></span><br />
	
			<!-- BEGIN description_edit_tpl -->
		<span class="small"><input type="text" name="NewDescriptionArray[]" size="70" value="{file_description}" STYLE="font-size:10px"/></span>
		<input type="hidden" name="FileUpdateIDArray[]" value="{file_id}">
		<!-- END description_edit_tpl -->

		<!-- BEGIN description_tpl -->
		<span class="small">{file_description}</span>
		<!-- END description_tpl -->
		</td>
	<td class="{td_class}" width="56%">
	<span class="small">{file_description}</span>
	<td class="{td_class}" width="1%">
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name_without_spaces}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-dl','','{www_dir}/design/admin/images/layout/downloadminimrk.gif',1)"><img name="ezf{file_id}-dl" border="0" src="{www_dir}/design/admin/images/layout/downloadmini.gif" width="16" height="16" align="top" alt="Download" /></a>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','{www_dir}/design/admin/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
	<!-- END write_tpl -->
	<!-- BEGIN no_write_tpl -->
	<td class="{td_class}" width="1%" align="center"></td>
	<td class="{td_class}" width="1%" align="center"></td>
	<!-- END no_write_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->

<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN prev_tpl -->
<td align="left">
<a class="path" href="{www_dir}{index}/filemanager/list/{folder_id}/{prev_offset}">&lt;&lt; {intl-previous}</a>
</td>
<!-- END prev_tpl -->
<!-- BEGIN next_tpl -->
<td align="right">
<a class="path" href="{www_dir}{index}/filemanager/list/{folder_id}/{next_offset}">{intl-next} &gt;&gt;</a>
</td>
<!-- END next_tpl -->
</tr>
</table>


<!-- BEGIN delete_menu_tpl -->
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<input class="stdbutton" type="submit" name="DeleteFiles" value="{intl-delete_files}">
	</td>
    <td>&nbsp;</td>
    <td>
	<input class="stdbutton" type="submit" name="DeleteFolders" value="{intl-delete_folders}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="UpdateFiles" value="{intl-update_files}">
	</td>
	
</tr>
</table>
<!-- END delete_menu_tpl -->

<!-- BEGIN write_menu_tpl -->
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<input class="stdbutton" type="submit" name="NewFile" value="{intl-new_file}">
	</td>
        <td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewFolder" value="{intl-new_folder}">
	<input type="hidden" name="FolderID" value="{main_folder_id}">
    </td>
</tr></table>
</form>

<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
<form method="post" action="{www_dir}{index}/filemanager/import/" enctype="multipart/form-data">
	<input type="text" name="SyncFileDir" size="20" value="{sync_dir}" />	
	<input class="stdbutton" type="submit" name="FileUpload" value="{intl-file_upload}" />
</form>
    </td>
</tr>
</table>
<!-- END write_menu_tpl -->
