<form method="post" action="{www_dir}{index}/todo/prioritytypeedit/{action_value}/{priority_type_id}/">
<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}:</p>
<input type="text" size="20" name="Name" value="{priority_type_name}" />
<br /><br />

<hr noshade size="4"/>

<input type="hidden" name="PriorityID" value="{priority_type_id}">
<input type="hidden" name="Action" value="{action_value}">

<input class="okbutton" type="submit" value="{intl-ok}">&nbsp;
<input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}">

</form>