<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/fileedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-file_upload}: {event_name}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-file_title}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	
	<p class="boxtext">{intl-file_description}:</p>
	<input type="text" size="40" name="Description" value="{description_value}"/>
	
	<p class="boxtext">{intl-file}:</p>
	<input class="stdbutton" size="40" name="userfile" type="file" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="EventID" value="{event_id}" />
	<input type="hidden" name="FileID" value="{file_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>

	<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/filelist/{event_id}/">
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</form>

	</td>

</tr>
</table>
