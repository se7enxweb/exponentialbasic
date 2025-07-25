{message_path_file}

<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{headline}</h1>

<!-- BEGIN errors_tpl -->

<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_missing_topic_item_tpl -->
<li>{intl-error_missing_topic}.
<!-- END error_missing_topic_item_tpl -->

<!-- BEGIN error_missing_body_item_tpl -->
<li>{intl-error_missing_body}.
<!-- END error_missing_body_item_tpl -->

</ul>

<hr noshade size="4" />

<br />
<!-- END errors_tpl -->

<form  method="post" action="{www_dir}{index}/forum/messageedit/{action_value}/{message_id}">
{message_hidden_form_file}
{message_form_file}
	<input class="okbutton" type="submit" name="PreviewButton" value="{intl-preview}" />
    &nbsp;
	<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
</div>