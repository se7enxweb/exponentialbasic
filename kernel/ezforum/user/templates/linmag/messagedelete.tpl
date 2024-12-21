<h1>{intl-delete_headline}</h1>

{message_path_file}

<br />

{message_body_file}

<form method="post" action="{www_dir}{index}/forum/messageedit/{action_value}/{message_id}">

<input class="stdbutton" type="submit" name="EditButton" value="{intl-edit}" />

<br /><br />

<input class="okbutton" type="submit" name="DeleteButton" value="{intl-delete}" />
&nbsp;
<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
