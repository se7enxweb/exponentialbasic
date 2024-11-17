<form method="post" action="{www_dir}{index}/sitemanager/file/list/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-files}</h1>

<hr noshade size="4"/>

<table width="100%" cellpadding="4" cellspacing="0" border="0" class="list">
<!-- BEGIN file_tpl -->
<tr>
	<td class="{td_class}" width="98%">
	{file_name}  
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/sitemanager/file/edit/{file_name}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezsitemanager{file_name}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezsitemanager{file_name}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a> 
	</td>
	<td align="right" class="{td_class}" width="1%">
	<input type="checkbox" name="FileDeleteArray[]" value="{file_name}" />
	<td>
</tr>
<!-- END file_tpl -->

<!-- BEGIN image_tpl -->
<tr>
	<td colspan="2" class="{td_class}" width="98%">
	{file_name}  
	</td>
	<td align="right" class="{td_class}" width="1%">
	<input type="checkbox" name="ImageDeleteArray[]" value="{file_name}" />
	<td>
</tr>
<!-- END image_tpl -->
</table>


<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-upload_file}:</p>
	<input class="stdbutton" size="20" name="userfile" type="file" />

	</td>
	<td>
	<p class="boxtext">{intl-upload_image}:</p>
	<input class="stdbutton" size="20" name="userimage" type="file" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<input class="stdbutton" type="submit" name="Upload" value="{intl-upload}" />
	<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />

</form>
