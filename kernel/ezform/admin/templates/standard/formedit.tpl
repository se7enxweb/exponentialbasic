<form action="{www_dir}{index}/form/form/{action_value}/{form_id}" method="post">

<h1>{intl-form_edit}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_message}.</div>
<!-- END error_item_tpl -->
<hr noshade="noshade" size="4" />
<br />
<!-- END error_list_tpl -->

<!-- BEGIN no_types_item_tpl -->
<h2 class="error">{intl-error_types}</h2>
<div class="error">{intl-no_types_1}.</div>
<div class="error">{intl-no_types_2}. {intl-no_types_3}.</div>
<hr noshade="noshade" size="4" />
<br />
<!-- END no_types_item_tpl -->


<!-- BEGIN form_item_tpl -->
<p class="boxtext">{intl-form_name}:</p>
<input type="text" class="box" size="40" name="formName" value="{form_name}" />
<br /><br />

<table width="100%" cellpaddning="0" cellspacing="0" border="0">
<tr>
    <td>
	<p class="boxtext">{intl-form_receiver}:</p>
    <input type="text" class="halfbox" size="20" name="formReceiver" value="{form_receiver}" />
	</td>
    <td>
	<p class="boxtext">{intl-form_cc}:</p>
    <input type="text" class="halfbox" size="20" name="formCC" value="{form_cc}" />
	</td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-form_completed_page}:</p>
    <input type="text" class="halfbox" size="20" name="formCompletedPage" value="{form_completed_page}" />
	</td>
    <td>
	<p class="boxtext">{intl-form_instruction_page}:</p>
    <input type="text" class="halfbox" size="20" name="formInstructionPage" value="{form_instruction_page}" />
	</td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-form_sender}:</p>
    <input type="text" class="halfbox" size="20" name="formSender" value="{form_sender}" />
	</td>
    <td valign="bottom">
   	<input type="checkbox" {checked} name="formSendAsUser" value="{form_send_as_user}" />&nbsp;<span class="boxtext">{intl-form_send_as_user}</span>
	</td>
</tr>
</table>
<input type="hidden" name="FormID" value="{form_id}" />
<!-- END form_item_tpl -->
<br/>

<!-- BEGIN no_elements_item_tpl -->
<div class="error">{intl-no_elements_exist}</div>
<!-- END no_elements_item_tpl -->

<!-- BEGIN element_list_tpl -->
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
	<th>{intl-element_name}:</th>
	<th>&nbsp;</th>
	<th>{intl-element_type}:</th>
	<th>&nbsp;</th>
	<th>{intl-size}</th>
	<th>{intl-break}</th>
	<th>{intl-element_required}:</th>
	<th colspan="3">&nbsp;</th>

<!-- BEGIN element_item_tpl -->
<tr>
    <td class="{td_class}"><input type="hidden" name="elementID[]" value="{element_id}"><input type="text" class="halfbox" size="20" name="elementName[]" value="{element_name}"></td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><select name="elementTypeID[]">
    <option value="0">{intl-select_type}</option>
<!-- BEGIN typelist_item_tpl -->
    <option value="{element_type_id}" {selected}>{intl-{element_type_name}}</option>
<!-- END typelist_item_tpl -->
    </select>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN fixed_values_tpl -->
    <a href="{www_dir}{index}/form/form/fixedvalues/{form_id}/{element_id}/">{intl-fixed_values}</a> 
    <!-- END fixed_values_tpl -->
    </td>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN size_tpl -->
    <input type="text" size="3" name="Size[{element_nr}]" value="{element_size}" />&nbsp;
    <!-- END size_tpl -->
    </td>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN break_tpl -->
    <input type="checkbox" {element_is_breaking} name="ElementBreak[]" value="{element_id}" />
    <!-- END break_tpl -->
    </td>

    <td width="1%" class="{td_class}" align="center">
        <input type="checkbox" {element_required} name="elementRequired[]" value="{element_id}" />
    </td>
    
<!-- BEGIN item_move_down_tpl -->
	<td width="1%" class="{td_class}">
        <a href="{www_dir}{index}/form/form/down/{form_id}/?ElementID={element_id}"><img src="{www_dir}/design/admin/images/move-down.gif" height="12" width="12" border="0" alt="{intl-move_up}" /></a>
    </td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%" class="{td_class}">&nbsp;</td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%" class="{td_class}">
        <a href="{www_dir}{index}/form/form/up/{form_id}/?ElementID={element_id}"><img src="{www_dir}/design/admin/images/move-up.gif" height="12" width="12" border="0" alt="{intl-move_down}" /></a>
    </td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%" class="{td_class}">&nbsp;</td>
<!-- END no_item_move_up_tpl -->
    <td class="{td_class}" >
        <input type="checkbox" name="elementDelete[]" value="{element_id}" />
    </td>
</tr>
<!-- END element_item_tpl -->
</table>
<!-- END element_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewElement" value="{intl-add_element}" />
<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />
<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected_elements}" />
<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Preview" value="{intl-preview}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
