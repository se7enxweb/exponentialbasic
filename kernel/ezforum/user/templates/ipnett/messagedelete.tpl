<h1>{intl-delete_headline}</h1>

{message_path_file}

<br />

{message_body_file}

<form method="post" action="/forum/messageedit/{action_value}/{message_id}">

<input class="stdbutton" type="submit" name="EditButton" value="{intl-edit}" />

<input class="okbutton" type="submit" name="DeleteButton" value="{intl-delete}" />
&nbsp;
<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
