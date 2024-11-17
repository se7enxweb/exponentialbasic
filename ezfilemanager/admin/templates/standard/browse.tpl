<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-files} {name}
	</td>
	<td align="right">
	<form action="{www_dir}{index}/filemanager/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}{action_url}" enctype="multipart/form-data">
<!-- BEGIN current_folder_tpl -->
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
   <td>
<img src="{www_dir}/ezfilemanager/user/{image_dir}/folder.gif" alt="" width="16" height="16" border="0" />&nbsp;<a href="{www_dir}{index}/filemanager/browse/{folder_id}/">{folder_name}</a><br />
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

<img src="{www_dir}/design/admin/images/layout/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/filemanager/browse/0/">{intl-file_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/layout/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/filemanager/browse/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_folder_description}</div></div>

<!-- BEGIN folder_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td class="{td_class}" width="1%" valign="top">
	<img src="{www_dir}/design/admin/images/layout/folder.gif" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="98%" valign="top">
	<a href="{www_dir}{index}/filemanager/browse/{folder_id}/">{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
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
	<td class="{td_class}" width="1%" valign="top">
	<img src="{www_dir}/design/admin/images/layout/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%" valign="top">
	<a href="{www_dir}{index}/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%" valign="top">
	{file_description}&nbsp;
	<td class="{td_class}" width="1%" valign="top">
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%" valign="top">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
	<!-- END read_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->

<hr noshade="noshade" size="4" />

<input type="submit" name="AddFiles" value="{intl-add_files}">&nbsp;

</form>
