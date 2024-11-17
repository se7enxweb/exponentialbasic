<form action="{www_dir}{index}/form/form/{action_value}/{form_id}" method="post">

<h1>{intl-form_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_forms_item_tpl -->
<div>{intl-no_forms_exist}</div>
<!-- END no_forms_item_tpl -->

<!-- BEGIN form_list_tpl -->
<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>{intl-form_name}:</th>
	<th>&nbsp;</th>
	<th>{intl-form_receiver}:</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN form_item_tpl -->
<tr>
    <td class="{td_class}">
        <a href="{www_dir}{index}/form/form/preview/{form_id}/">{form_name}</a>
    </td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">
        {form_receiver}
    </td>
    <td width="1%" class="{td_class}" align="center">
    <a href="{www_dir}{index}/form/form/edit/{form_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{article_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{article_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>

    <input type="checkbox" name="formDelete[]" value="{form_id}">
    </td>
</tr>
<!-- END form_item_tpl -->
</table>
<!-- END form_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected_forms}" />

</form>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/form/form/list/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/form/form/list/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/form/form/list/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
