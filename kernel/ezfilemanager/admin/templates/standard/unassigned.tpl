<table width="100%" border="0">
<tr>
	<td valign="bottom">
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

<form method="post" action="{www_dir}{index}/filemanager/unassigned/" >

<hr noshade="noshade" size="4" />

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
	<td class="{td_class}" width="1%">
	<img src="{www_dir}/design/admin/images/layout/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<a href="{www_dir}{index}/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%">
	{file_description}
	<td class="{td_class}" width="1%">
	{file_size}&nbsp;{file_unit}
	</td>

	<td class="{td_class}">
	<select name="FolderArrayID[]">
	<option	value="-1">{intl-do_not_update}</option>
	<!-- BEGIN value_tpl -->
	<option	value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
	<td class="{td_class}" width="1%">
	<input type="hidden" name="FileArrayID[]" value="{file_id}">
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','{www_dir}/design/admin/images/layout/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="{www_dir}/design/admin/images/layout/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">

</form>
