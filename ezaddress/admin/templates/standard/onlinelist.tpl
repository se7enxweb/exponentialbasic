<!-- BEGIN list_page -->
<form method="post" action="{www_dir}{index}{item_new_command}/">

<h1>{intl-list_headline}</h1>
<hr noshade="noshade" size="4" />
<br />
<!-- BEGIN list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-prefix}:</th>
	<th>{intl-prefix_link}:</th>
	<th>{intl-prefix_visual}:</th>
	<th colspan="5">&nbsp;</th>
</tr>
<!-- BEGIN line_item_tpl -->
<tr class="{bg_color}">
<!-- BEGIN item_plain_tpl -->
	<td>
        {item_name}
	</td>
<!-- END item_plain_tpl -->
<!-- BEGIN item_linked_tpl -->
	<td>
        <a href="{www_dir}{index}{item_sort_command}/{item_id}">{item_name}</a>
	</td>
<!-- END item_linked_tpl -->
	<td>
        {item_prefix}
	</td>

<!-- BEGIN item_link_true_tpl -->
	<td>
        {intl-yes}
	</td>
<!-- END item_link_true_tpl -->
<!-- BEGIN item_link_false_tpl -->
	<td>
        {intl-no}
	</td>
<!-- END item_link_false_tpl -->
<!-- BEGIN item_visual_true_tpl -->
	<td>
        {intl-yes}
	</td>
<!-- END item_visual_true_tpl -->
<!-- BEGIN item_visual_false_tpl -->
	<td>
        {intl-no}
	</td>
<!-- END item_visual_false_tpl -->

<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="{www_dir}{index}{item_down_command}/{item_id}"><img src="{www_dir}/design/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{www_dir}{index}{item_up_command}/{item_id}"><img src="{www_dir}/design/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->

	<td width="1%">
	<a href="{www_dir}{index}{item_edit_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-red','','{www_dir}/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{item_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<input type="checkbox" name="ItemArrayID[]" value="{item_id}">
	</td>

</tr>
<!-- END line_item_tpl -->
</table>
<!-- END list_item_tpl -->

<!-- BEGIN no_line_item_tpl -->
<p class="boxtext">{intl-no_item}</p>
<!-- END no_line_item_tpl -->

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="Back" value="{intl-new}">
	</td>
	<td>&nbsp;</td>
	<td>
        <input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}">
	</td>
</tr>
</table>
<!--  <input class="stdbutton" type="submit" name="Back" value="{intl-new}"> -->
</form>

<!-- END list_page -->
