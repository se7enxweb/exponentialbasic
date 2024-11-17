<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<form method="post" action="{www_dir}{index}/bug/report/fileedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-file_upload}: {bug_name}</h1>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-file_title}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	
	<p class="boxtext">{intl-file_description}:</p>
	<input type="text" size="40" name="Description" value="{description_value}"/>
	
	<p class="boxtext">{intl-file}:</p>
	<input size="40" name="userfile" type="file" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>
<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="BugID" value="{bug_id}" />
	<input type="hidden" name="FileID" value="{file_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>

	<form method="post" action="{www_dir}{index}/bug/report/edit/{bug_id}/">
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</form>

	</td>

</tr>
</table>



