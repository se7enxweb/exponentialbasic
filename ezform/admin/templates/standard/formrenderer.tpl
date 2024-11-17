<!-- BEGIN text_field_item_tpl -->
<input type="text" size="{element_size}" name="{field_name}" value="{field_value}" />
<!-- END text_field_item_tpl -->

<!-- BEGIN text_area_item_tpl -->
<textarea class="box" name="{field_name}" cols="40" rows="5" wrap="soft">{field_value}</textarea>
<!-- END text_area_item_tpl -->

<!-- BEGIN multiple_select_item_tpl -->
<select name="{field_name}[]" multiple="multiple" >
<!-- BEGIN multiple_select_item_sub_item_tpl -->
<option value="{sub_value}">{sub_value}</option>
<!-- END multiple_select_item_sub_item_tpl -->
</select>
<!-- END multiple_select_item_tpl -->

<!-- BEGIN dropdown_item_tpl -->
<select name="{field_name}">
<!-- BEGIN dropdown_item_sub_item_tpl -->
<option value="{sub_value}">{sub_value}</option>
<!-- END dropdown_item_sub_item_tpl -->
</select>
<!-- END dropdown_item_tpl -->

<!-- BEGIN radiobox_item_tpl -->
<!-- BEGIN radiobox_item_sub_item_tpl -->
{sub_value}: <input type="radio" value="{sub_value}" name="{field_name}" />
<!-- END radiobox_item_sub_item_tpl -->
<!-- END radiobox_item_tpl -->

<!-- BEGIN checkbox_item_tpl -->
<!-- BEGIN checkbox_item_sub_item_tpl -->
{sub_value}: <input type="checkbox" value="{sub_value}" name="{field_name}[]" />
<!-- END checkbox_item_sub_item_tpl -->
<!-- END checkbox_item_tpl -->


<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_value} {error_message}.</div>
<!-- END error_item_tpl -->
<br />
<!-- END error_list_tpl -->

<!-- BEGIN form_list_tpl -->
<!-- BEGIN form_start_tag_tpl -->
<form action="{www_dir}{index}/form/form/process/{form_id}/{section_id}/" method="post">
<h2>{form_name}</h2>
<!-- END form_start_tag_tpl -->
<!-- BEGIN form_instructions_tpl -->
<a href="{www_dir}{index}{form_instruction_page}">{intl-instructions}</a>
<!-- END form_instructions_tpl -->
<input type="hidden" name="formID" value="{form_id}" />
<input type="hidden" name="mailSubject" value="{form_name}" />
<input type="hidden" name="redirectTo" value="{form_completed_page}" />
<!-- BEGIN form_sender_tpl -->
<p class="boxtext">{intl-form_sender}:</p>
<input type="text" class="box" name="formSender" value="{form_sender_value}" />
<br /><br />

<!-- END form_sender_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN form_item_tpl -->
	<td {colspan} >
	<p class="boxtext">
	{element_name}:
	</p>
	{element}<br /><br />
	</td>
<!-- BEGIN break_tpl -->
</tr>
<tr>
<!-- END break_tpl -->

<!-- END form_item_tpl -->
</tr>
</table>

<!-- BEGIN form_buttons_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
</tr>
</table>
<!-- END form_buttons_tpl -->

<!-- BEGIN form_end_tag_tpl -->
</form>
<!-- END form_end_tag_tpl -->

<!-- END form_list_tpl -->

ADMIN REMOVE