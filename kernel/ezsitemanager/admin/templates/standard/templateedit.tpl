<h1>{intl-template_edit} - {file_name}</h1>

<hr noshade="noshade" size="4" />
<br />
<form method="post" action="{www_dir}{index}/sitemanager/template/edit/" >

<textarea name="Contents" cols="80" rows="25" wrap="soft">{file_contents}</textarea>
<br />

<hr noshade="noshade" size="4" />

<input type="hidden" name="FileName" value="{file_name}" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
