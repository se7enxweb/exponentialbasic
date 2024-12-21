<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-file_view}</h1>

<br />

<!-- BEGIN view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-file_name}:</p>
    {file_name}

    <p class="boxtext">{intl-file_description}:</p>
    {file_description}
    
    <p class="boxtext">{intl-file_owner}:</p>
    {file_owner}

    <p class="boxtext">{intl-file_size}:</p>
    {file_size} {file_unit}

</tr>
</table>
<!-- END view_tpl -->
<br />

<form method="post" action="{www_dir}{index}/filemanager/edit/{file_id}" enctype="multipart/form-data">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<!-- BEGIN delete_tpl -->
	<td>
	<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}">
	</td>
	<!-- END delete_tpl -->
	<!-- BEGIN edit_tpl -->
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="Edit" value="{intl-edit}">
	</td>
	<!-- END edit_tpl -->
</tr>
</table>
<br />
<!-- BEGIN download_tpl -->
<input class="okbutton" type="submit" name="Download" value="{intl-download}">
<!-- END download_tpl -->

</form>


