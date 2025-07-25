{message_path_file}
<!-- start #contentWrap -->
<div id="contentWrap">
<h1 class="mainHeading">{intl-preview_headline}</h1>

<!-- BEGIN moderated_tpl -->

<br />
<b>{intl-moderated_info_1}:</b>&nbsp
{intl-moderated_info_2}.&nbsp
{intl-moderated_info_3}.&nbsp
{intl-moderated_info_4}.&nbsp
<br />
<br />
<!-- END moderated_tpl -->

<br />
{message_body_file}

<form method="post" action="{www_dir}{index}/forum/messageedit/{action_value}/{message_id}">
{message_hidden_form_file}

    <input class="stdbutton" type="submit" name="EditButton" value="{intl-edit}" />

	<hr noshade="noshade" size="4" />
    
	<input class="okbutton" type="submit" name="PostButton" value="{intl-post}" />
    &nbsp;
	<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
</div>
<!-- end #contentWrap -->