<h1>{intl-edit_file} - {file_name}</h1>

<hr noshade="noshade" size="4" />

<br />

<form method="post" action="{www_dir}{index}/sitemanager/file/edit/{file_name}" >

<textarea class="box" name="Contents" cols="40" rows="25" wrap="soft">{file_contents}</textarea>

<br /><br />

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
