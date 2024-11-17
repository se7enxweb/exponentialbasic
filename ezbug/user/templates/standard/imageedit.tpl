<form method="post" action="{www_dir}{index}/bug/report/imageedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-imageupload}: {bug_name}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-imagetitle}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	
	<p class="boxtext">{intl-imagecaption}:</p>
	<input type="text" size="40" name="Caption" value="{caption_value}"/>
	
	<p class="boxtext">{intl-imagefile}:</p>
	<input size="40" name="userfile" type="file" />
	</td>
	<td>&nbsp;</td>
	<td>
	<!-- BEGIN image_tpl -->
	<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_tpl -->
	</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="BugID" value="{bug_id}" />
	<input type="hidden" name="ImageID" value="{image_id}" />
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



