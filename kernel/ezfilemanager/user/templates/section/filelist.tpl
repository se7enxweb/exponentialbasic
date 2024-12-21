<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/filemanager/list/0/">{intl-file_root}</a>
	<!-- BEGIN path_item_tpl -->	
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/filemanager/list/{folder_id}/">{folder_name}</a>
	<!-- END path_item_tpl -->
	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-files}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/filemanager/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/filemanager/new/" enctype="multipart/form-data">
<!-- BEGIN current_folder_tpl -->

<!-- END current_folder_tpl -->

<div class="spacer"><div class="p">{current_folder_description}</div></div>

<!-- BEGIN folder_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td class="{td_class}" width="1%" valign="top">
	<img src="{www_dir}/images/folder.gif" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="98%" valign="top">
	<a href="{www_dir}{index}/filemanager/list/{folder_id}/">{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
        <!-- BEGIN folder_write_tpl -->
	<td class="{td_class}" width="1%" valign="top">
	<a href="{www_dir}{index}/filemanager/folder/edit/{folder_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{folder_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{folder_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%" valign="top">
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
	<img src="{www_dir}/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<a href="{www_dir}{index}/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%">
	<span class="small">{file_description}</span>
	<td class="{td_class}" width="1%" >
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/download/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-dl','','/images/downloadminimrk.gif',1)"><img name="ezf{file_id}-dl" border="0" src="{www_dir}/images/downloadmini.gif" width="16" height="16" align="top" alt="Download" /></a>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
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
<br />
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
<br />
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
