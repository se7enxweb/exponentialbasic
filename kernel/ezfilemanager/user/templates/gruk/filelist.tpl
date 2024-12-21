<table width="100%" border="0">
<tr>
	<td>
	<h1>{folder_name}</h1>
	</td>
	<td align="right">
	<form action="/filemanager/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="/filemanager/new/" enctype="multipart/form-data">
<!-- BEGIN current_folder_tpl -->

<!-- END current_folder_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/filemanager/list/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_folder_description}</div></div>

<!-- BEGIN folder_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="/images/folder.gif" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="98%">
	<a href="/filemanager/list/{folder_id}/">{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
        <!-- BEGIN folder_write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/filemanager/folder/edit/{folder_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{folder_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{folder_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="FolderArrayID[]" value="{folder_id}">
	</td>
        <!-- END folder_write_tpl -->
</tr>
<!-- END folder_tpl -->

</table>
<!-- END folder_list_tpl -->

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
	<img src="/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<a href="/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%">
	<span class="small">{file_description}</span>
	<td class="{td_class}" width="1%">
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%">
	<a href="/filemanager/download/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-dl','','/images/downloadminimrk.gif',1)"><img name="ezf{file_id}-dl" border="0" src="/images/downloadmini.gif" width="16" height="16" align="top" alt="Download" /></a>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
	<!-- END write_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->


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
</tr>
</table>
<!-- END write_menu_tpl -->

</form>
