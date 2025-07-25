<form method="post" action="{www_dir}{index}/link/unacceptededit/">

<h1>{intl-unaccepted_links}</h1>

<div class="boxtext">({link_start}-{link_end}/{link_total})</div>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN link_item_tpl -->
<div class="boxtext">{intl-name}:</div>
<input type="text" class="box" size="40" name="Name[]" value="{link_name}" />
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="top">
    <div class="boxtext">{intl-category}:</div>
    <select name="LinkCategoryID[]">
    <!-- BEGIN category_item_tpl -->
        <option value="{link_category_id}" {is_selected}>{option_level}{link_category_name}</option>
    <!-- END category_item_tpl -->
    </select>
    </td align="left" valign="top">
    <td>
    <p class="boxtext">{intl-multiple_categories}:</p>
    <select multiple size="{num_select_categories}" name="CategoryArray[{i}][]">
    <!-- BEGIN multiple_category_tpl -->
        <option value="{link_category_id}" {multiple_selected}>&nbsp;{option_level} {link_category_name}</option>
    <!-- END multiple_category_tpl -->
    </select>
    </td>
</tr>
</table>

<br /><br />

<a href="http://{link_url}" target="_blank"><div class="boxtext">{intl-url}:</div></a>
<input type="text" class="box" size="40" name="Url[]" value="{link_url}" />
<br /><br />

<div class="boxtext">{intl-keywords}:</div>
<textarea class="box" cols="40" rows="4" name="Keywords[]">{link_keywords}</textarea>
<br /><br />

<div class="boxtext">{intl-description}:</div>
<textarea class="box" cols="40" rows="4" name="Description[]">{link_description}</textarea>
<br /><br />

<div class="boxtext">{intl-action}:</div>
<select name="ActionValueArray[{i}]">
<option value="Defer" selected/>{intl-defer}</option>
<option value="Accept">{intl-accept}</option>
<option value="Update">{intl-update_not_accept}</option>
<option value="Delete">{intl-delete}</option>
</select>
<br /><br />

<hr noshade="noshade" size="4" />

<input type="hidden" name="LinkArrayID[]" value="{link_id}">

<!-- END link_item_tpl -->

<input class="stdbutton" type="submit" value="{intl-update}">

</form>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/link/unacceptedlist/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/link/unacceptedlist/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/link/unacceptedlist/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
